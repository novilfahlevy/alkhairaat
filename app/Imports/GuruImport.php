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

        // Bulk query - ambil semua guru yang sudah ada
        $existingGuru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
            ->whereIn('nik', $nikList)->get()->keyBy('nik');

        DB::transaction(function () use ($validRows, $existingGuru) {
            $jabatanGuruData = [];
            $alamatData = [];
            $newGuruData = [];

            foreach ($validRows as $row) {
                $guru = $existingGuru->get($row['nik']);
                if (!$guru) {
                    $newGuruData[] = [
                        'status' => Guru::STATUS_AKTIF,
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
            }

            // Bulk insert guru baru
            if (!empty($newGuruData)) {
                try {
                    DB::table('guru')->insert($newGuruData);
                    $nikList = collect($newGuruData)->pluck('nik')->toArray();
                    $newlyInserted = Guru::whereIn('nik', $nikList)->get()->keyBy('nik');
                    $existingGuru = $existingGuru->merge($newlyInserted);
                    Log::channel('guru_bulk_import')->info('Bulk inserted new guru', ['count' => count($newGuruData)]);
                } catch (Exception $e) {
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
                    $nikList = collect($newGuruData)->pluck('nik')->toArray();
                    $newlyInserted = Guru::whereIn('nik', $nikList)->get()->keyBy('nik');
                    $existingGuru = $existingGuru->merge($newlyInserted);
                }
            }

            // Siapkan data jabatan_guru dan alamat
            foreach ($validRows as $row) {
                $guru = $existingGuru->get($row['nik']);
                if (!$guru) {
                    Log::channel('guru_bulk_import')->warning('Guru not found after insert', ['nik' => $row['nik']]);
                    continue;
                }
                // JabatanGuru
                $jabatanGuruData[] = [
                    'id_guru' => $guru->id,
                    'id_sekolah' => $this->idSekolah,
                    'jenis_jabatan' => JabatanGuru::JENIS_JABATAN_GURU,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // Alamat
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
            // Upsert JabatanGuru
            if (!empty($jabatanGuruData)) {
                foreach ($jabatanGuruData as $data) {
                    JabatanGuru::updateOrCreate([
                        'id_guru' => $data['id_guru'],
                        'id_sekolah' => $data['id_sekolah'],
                        'jenis_jabatan' => JabatanGuru::JENIS_JABATAN_GURU,
                    ], $data);
                }
                Log::channel('guru_bulk_import')->info('Processed jabatan_guru', ['count' => count($jabatanGuruData)]);
            }
            // Upsert Alamat
            if (!empty($alamatData)) {
                foreach ($alamatData as $data) {
                    Alamat::updateOrCreate([
                        'id_guru' => $data['id_guru'],
                        'jenis' => $data['jenis'],
                    ], $data);
                }
                Log::channel('guru_bulk_import')->info('Processed alamat', ['count' => count($alamatData)]);
            }
        });
    }

    private function validateRows(Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $row = $row->toArray();
            if (empty($row['nik']) || empty($row['nama'])) {
                return null;
            }
            return [
                'nik' => trim((string)$row['nik']),
                'nuptk' => isset($row['nuptk']) ? trim((string)$row['nuptk']) : null,
                'npk' => isset($row['npk']) ? trim((string)$row['npk']) : null,
                'gelar_depan' => $row['gelar_depan'] ?? null,
                'nama' => trim($row['nama']),
                'gelar_belakang' => $row['gelar_belakang'] ?? null,
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir'] ?? null),
                'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L') === 'P' ? 'P' : 'L',
                'status_perkawinan' => $row['status_perkawinan'] ?? null,
                'status_kepegawaian' => $row['status_kepegawaian'] ?? null,
                'kontak_wa_hp' => $row['kontak_wa_hp'] ?? null,
                'email' => $row['email'] ?? null,
                'nomor_rekening' => $row['nomor_rekening'] ?? null,
                'rekening_atas_nama' => $row['rekening_atas_nama'] ?? null,
                'bank_rekening' => $row['bank_rekening'] ?? null,
                'provinsi' => $row['provinsi'] ?? null,
                'kabupaten' => $row['kabupaten'] ?? null,
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
