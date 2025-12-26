<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Alamat;
use App\Models\Scopes\GuruSekolahNauanganScope;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function __construct(private int $idSekolah) {}

    public function collection(Collection $rows)
    {
        $validRows = $this->validateRows($rows);
        if ($validRows->isEmpty()) {
            return;
        }

        // Ambil semua NIK dari chunk ini
        $nikList = $validRows->pluck('nik')->filter()->unique()->toArray();

        // Bulk query - ambil NIK yang sudah ada dalam 1 query
        $existingNik = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
            ->whereIn('nik', $nikList)
            ->whereHas('jabatanGuru', function ($query) {
                $query->where('id_sekolah', $this->idSekolah);
            })
            ->pluck('nik')
            ->toArray();

        // Filter hanya guru dengan NIK yang belum ada
        $newGuruRows = $validRows->reject(function ($row) use ($existingNik) {
            return in_array($row['nik'], $existingNik);
        });

        // Skip jika tidak ada guru baru untuk diproses
        if ($newGuruRows->isEmpty()) {
            Log::channel('guru_bulk_import')->info('No new guru to import - all NIK already exist', [
                'existing_count' => count($existingNik),
                'total_rows' => $validRows->count()
            ]);
            return;
        }

        DB::transaction(function () use ($newGuruRows, $validRows) {
            $jabatanGuruData = [];
            $alamatData = [];
            $newGuruData = [];

            foreach ($newGuruRows as $row) {
                // Siapkan data untuk bulk insert guru baru
                $newGuruData[] = [
                    'status' => $row['status'] ?? Guru::STATUS_AKTIF,
                    'nama_gelar_depan' => $row['gelar_depan'],
                    'nama' => $row['nama'],
                    'nama_gelar_belakang' => $row['gelar_belakang'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'jenis_kelamin' => strtoupper($row['jenis_kelamin']) === 'P' ? 'P' : 'L',
                    'status_perkawinan' => $row['status_perkawinan'],
                    'nik' => $row['nik'],
                    'status_kepegawaian' => $row['status_kepegawaian'],
                    'npk' => $row['npk'],
                    'nuptk' => $row['nuptk'],
                    'kontak_wa_hp' => $row['kontak_wa_hp'],
                    'kontak_email' => $row['email'],
                    'nomor_rekening' => $row['nomor_rekening'],
                    'rekening_atas_nama' => $row['rekening_atas_nama'],
                    'bank_rekening' => $row['bank_rekening'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert guru baru
            try {
                DB::table('guru')->insert($newGuruData);
                
                // Ambil guru yang baru saja diinsert untuk mendapatkan ID
                $nikList = collect($newGuruData)->pluck('nik')->toArray();
                $insertedGuru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
                    ->whereIn('nik', $nikList)
                    ->get()
                    ->keyBy('nik');
                
                Log::channel('guru_bulk_import')->info('Bulk inserted new guru', ['count' => count($newGuruData)]);
            } catch (Exception $e) {
                // Jika bulk insert gagal (mungkin ada duplicate dari chunk lain yang concurrent)
                // Fallback ke insert satu-satu dengan error handling
                Log::channel('guru_bulk_import')->warning('Bulk insert failed, fallback to individual inserts', ['error' => $e->getMessage()]);
                
                foreach ($newGuruData as $data) {
                    try {
                        DB::table('guru')->insert($data);
                    } catch (Exception $e2) {
                        if ($e2 instanceof \Illuminate\Database\QueryException && isset($e2->errorInfo[1]) && $e2->errorInfo[1] == 1062) {
                            // Duplicate entry, ignore
                        } else {
                            throw $e2;
                        }
                    }
                }
                
                // Refresh untuk mendapatkan ID guru yang berhasil diinsert
                $nikList = collect($newGuruData)->pluck('nik')->toArray();
                $insertedGuru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
                    ->whereIn('nik', $nikList)
                    ->get()
                    ->keyBy('nik');
            }

            // Siapkan data jabatan_guru dan alamat hanya untuk guru baru
            foreach ($newGuruRows as $row) {
                $guru = $insertedGuru->get($row['nik']);
                
                if (!$guru) {
                    Log::channel('guru_bulk_import')->warning('Guru not found after insert', ['nik' => $row['nik']]);
                    continue;
                }
                
                // Data jabatan_guru
                $jabatanGuruData[] = [
                    'id_guru' => $guru->id,
                    'id_sekolah' => $this->idSekolah,
                    'jenis_jabatan' => $row['jenis_jabatan'] ?? JabatanGuru::JENIS_JABATAN_GURU,
                    'keterangan_jabatan' => $row['keterangan_jabatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Data alamat jika ada
                if ($row['alamat_lengkap'] || $row['provinsi']) {
                    $alamatData[] = [
                        'id_guru' => $guru->id,
                        'jenis' => 'domisili',
                        'provinsi' => $row['provinsi'],
                        'kabupaten' => $row['kabupaten'],
                        'kecamatan' => $row['kecamatan'],
                        'kelurahan' => $row['kelurahan'],
                        'rt' => $row['rt'],
                        'rw' => $row['rw'],
                        'kode_pos' => $row['kode_pos'],
                        'alamat_lengkap' => $row['alamat_lengkap'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            // Batch insert jabatan_guru untuk guru baru
            if (!empty($jabatanGuruData)) {
                DB::table('jabatan_guru')->insert($jabatanGuruData);
                Log::channel('guru_bulk_import')->info('Processed jabatan_guru for new guru', ['count' => count($jabatanGuruData)]);
            }
            
            // Batch insert alamat untuk guru baru
            if (!empty($alamatData)) {
                DB::table('alamat')->insert($alamatData);
                Log::channel('guru_bulk_import')->info('Processed alamat for new guru', ['count' => count($alamatData)]);
            }
            
            Log::channel('guru_bulk_import')->info('Import completed', [
                'new_guru_count' => count($newGuruData),
                'skipped_existing_count' => $validRows->count() - $newGuruRows->count(),
                'total_processed' => $validRows->count()
            ]);
        });
    }

    private function validateRows(Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $row = $row->toArray();
            // Validasi field wajib: nama, nik, jenis_kelamin, status, status_kepegawaian, keterangan_jabatan
            if (empty($row['nik']) || empty($row['nama']) || empty($row['jenis_kelamin']) || 
                empty($row['status']) || empty($row['status_kepegawaian']) || empty($row['keterangan_jabatan'])) {
                return null;
            }
            
            // Validasi field wajib alamat: provinsi, kabupaten
            if (empty($row['provinsi']) || empty($row['kabupaten'])) {
                return null;
            }
            
            return [
                'jenis_jabatan' => $row['jenis_jabatan'] ?? JabatanGuru::JENIS_JABATAN_GURU,
                'keterangan_jabatan' => $row['keterangan_jabatan'],
                'nama' => trim($row['nama']),
                'nik' => trim((string)$row['nik']),
                'jenis_kelamin' => strtoupper(trim($row['jenis_kelamin'] ?? '')) === 'P' ? 'P' : 'L',
                'status' => $row['status'] ?? Guru::STATUS_AKTIF,
                'status_kepegawaian' => $row['status_kepegawaian'],
                'nuptk' => isset($row['nuptk']) ? trim((string)$row['nuptk']) : null,
                'npk' => isset($row['npk']) ? trim((string)$row['npk']) : null,
                'gelar_depan' => $row['gelar_depan'] ?? null,
                'gelar_belakang' => $row['gelar_belakang'] ?? null,
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir'] ?? null),
                'status_perkawinan' => $row['status_perkawinan'] ?? null,
                'kontak_wa_hp' => $row['kontak_wa_hp'] ?? null,
                'email' => $row['email'] ?? null,
                'nomor_rekening' => $row['nomor_rekening'] ?? null,
                'rekening_atas_nama' => $row['rekening_atas_nama'] ?? null,
                'bank_rekening' => $row['bank_rekening'] ?? null,
                'provinsi' => $row['provinsi'],
                'kabupaten' => $row['kabupaten'],
                'kecamatan' => $row['kecamatan'] ?? null,
                'kelurahan' => $row['kelurahan'] ?? null,
                'rt' => $row['rt'] ?? null,
                'rw' => $row['rw'] ?? null,
                'kode_pos' => $row['kode_pos'] ?? null,
                'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
            ];
        })->filter();
    }

    public function chunkSize(): int
    {
        return 500;
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }
}