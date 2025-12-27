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

        // Ambil semua NIK, NPK, dan NUPTK dari chunk ini
        $nikList = $validRows->pluck('nik')->filter()->unique()->toArray();
        $npkList = $validRows->pluck('npk')->filter()->unique()->toArray();
        $nuptkList = $validRows->pluck('nuptk')->filter()->unique()->toArray();

        // Bulk query - ambil guru yang sudah ada berdasarkan NIK, NPK, atau NUPTK
        $existingGurus = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
            ->whereIn('nik', $nikList)
            ->orWhereIn('npk', $npkList)
            ->orWhereIn('nuptk', $nuptkList)
            ->get()
            ->keyBy(function($guru) {
                return $guru->nik . '_' . ($guru->npk ?? '') . '_' . ($guru->nuptk ?? '');
            });

        // Filter guru baru dan guru yang sudah ada
        $newGuruRows = $validRows->reject(function ($row) use ($existingGurus) {
            $key = $row['nik'] . '_' . ($row['npk'] ?? '') . '_' . ($row['nuptk'] ?? '');
            return $existingGurus->has($key);
        });

        $existingGuruRows = $validRows->filter(function ($row) use ($existingGurus) {
            $key = $row['nik'] . '_' . ($row['npk'] ?? '') . '_' . ($row['nuptk'] ?? '');
            return $existingGurus->has($key);
        });

        // Skip jika tidak ada data untuk diproses
        if ($newGuruRows->isEmpty() && $existingGuruRows->isEmpty()) {
            Log::channel('guru_bulk_import')->info('No valid guru data to import');
            return;
        }

        DB::transaction(function () use ($newGuruRows, $existingGuruRows, $existingGurus) {
            $jabatanGuruData = [];
            $alamatData = [];
            $newGuruData = [];
            $updatedGuruIds = [];
            $newJabatanGuruIds = [];

            // Proses guru baru
            if (!$newGuruRows->isEmpty()) {
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
                    Log::channel('guru_bulk_import')->info('Bulk inserted new guru', ['count' => count($newGuruData)]);
                } catch (Exception $e) {
                    // Jika bulk insert gagal, fallback ke insert satu-satu
                    Log::channel('guru_bulk_import')->warning('Bulk insert failed, fallback to individual inserts', ['error' => $e->getMessage()]);
                    
                    foreach ($newGuruData as $data) {
                        try {
                            DB::table('guru')->insert($data);
                        } catch (Exception $e2) {
                            if ($e2 instanceof \Illuminate\Database\QueryException && isset($e2->errorInfo[1]) && $e2->errorInfo[1] == 1062) {
                                // Duplicate entry, log and continue
                                Log::channel('guru_bulk_import')->warning('Duplicate NPK, NIK, or NUPTK found, skipping', [
                                    'nik' => $data['nik'],
                                    'npk' => $data['npk'],
                                    'nuptk' => $data['nuptk']
                                ]);
                                continue;
                            } else {
                                throw $e2;
                            }
                        }
                    }
                }

                // Refresh untuk mendapatkan ID guru yang berhasil diinsert
                $nikList = collect($newGuruData)->pluck('nik')->toArray();
                $insertedGurus = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
                    ->whereIn('nik', $nikList)
                    ->get()
                    ->keyBy('nik');
                
                // Gabungkan dengan guru yang sudah ada
                $allGurus = $existingGurus->merge($insertedGurus);
            } else {
                $allGurus = $existingGurus;
            }

            // Proses data JabatanGuru dan Alamat untuk semua guru (baru dan yang sudah ada)
            $allGuruRows = $newGuruRows->merge($existingGuruRows);
            
            foreach ($allGuruRows as $row) {
                // Cari guru berdasarkan NIK (prioritas), NPK, atau NUPTK
                $guru = $allGurus->first(function ($g) use ($row) {
                    return $g->nik === $row['nik'] || 
                           ($g->npk && $g->npk === $row['npk']) || 
                           ($g->nuptk && $g->nuptk === $row['nuptk']);
                });
                
                if (!$guru) {
                    Log::channel('guru_bulk_import')->warning('Guru not found', [
                        'nik' => $row['nik'],
                        'npk' => $row['npk'],
                        'nuptk' => $row['nuptk']
                    ]);
                    continue;
                }

                // Update data guru yang sudah ada
                if ($existingGuruRows->contains(function ($existingRow) use ($row) {
                    return $existingRow['nik'] === $row['nik'] || 
                           ($existingRow['npk'] && $existingRow['npk'] === $row['npk']) || 
                           ($existingRow['nuptk'] && $existingRow['nuptk'] === $row['nuptk']);
                })) {
                    $updatedGuruIds[] = $guru->id;
                    
                    // Update data guru
                    $guru->update([
                        'status' => $row['status'] ?? Guru::STATUS_AKTIF,
                        'nama_gelar_depan' => $row['gelar_depan'],
                        'nama' => $row['nama'],
                        'nama_gelar_belakang' => $row['gelar_belakang'],
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => $row['tanggal_lahir'],
                        'jenis_kelamin' => strtoupper($row['jenis_kelamin']) === 'P' ? 'P' : 'L',
                        'status_perkawinan' => $row['status_perkawinan'],
                        'status_kepegawaian' => $row['status_kepegawaian'],
                        'npk' => $row['npk'],
                        'nuptk' => $row['nuptk'],
                        'kontak_wa_hp' => $row['kontak_wa_hp'],
                        'kontak_email' => $row['email'],
                        'nomor_rekening' => $row['nomor_rekening'],
                        'rekening_atas_nama' => $row['rekening_atas_nama'],
                        'bank_rekening' => $row['bank_rekening'],
                    ]);
                }

                // SELALU buat record JabatanGuru baru untuk setiap baris data
                // Ini memungkinkan guru memiliki multiple jabatan di sekolah yang sama
                $jabatanGuruData[] = [
                    'id_guru' => $guru->id,
                    'id_sekolah' => $this->idSekolah,
                    'jenis_jabatan' => $row['jenis_jabatan'] ?? JabatanGuru::JENIS_JABATAN_GURU,
                    'keterangan_jabatan' => $row['keterangan_jabatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $newJabatanGuruIds[] = $guru->id;
                
                // Cek dan update alamat
                if ($row['alamat_lengkap'] || $row['provinsi']) {
                    $alamat = Alamat::where('id_guru', $guru->id)
                        ->where('jenis', 'domisili')
                        ->first();

                    if ($alamat) {
                        // Update alamat yang sudah ada
                        $alamat->update([
                            'provinsi' => $row['provinsi'],
                            'kabupaten' => $row['kabupaten'],
                            'kecamatan' => $row['kecamatan'],
                            'kelurahan' => $row['kelurahan'],
                            'rt' => $row['rt'],
                            'rw' => $row['rw'],
                            'kode_pos' => $row['kode_pos'],
                            'alamat_lengkap' => $row['alamat_lengkap'],
                        ]);
                    } else {
                        // Data alamat baru
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
            }

            // Batch insert jabatan_guru baru
            if (!empty($jabatanGuruData)) {
                DB::table('jabatan_guru')->insert($jabatanGuruData);
                Log::channel('guru_bulk_import')->info('Processed new jabatan_guru', ['count' => count($jabatanGuruData)]);
            }
            
            // Batch insert alamat baru
            if (!empty($alamatData)) {
                DB::table('alamat')->insert($alamatData);
                Log::channel('guru_bulk_import')->info('Processed new alamat', ['count' => count($alamatData)]);
            }
            
            Log::channel('guru_bulk_import')->info('Import completed', [
                'new_guru_count' => count($newGuruData),
                'updated_guru_count' => count($updatedGuruIds),
                'new_jabatan_count' => count($newJabatanGuruIds),
                'total_processed' => $allGuruRows->count()
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
            
            // Pastikan NIK, NPK, dan NUPTK adalah string, bukan angka
            $nik = isset($row['nik']) ? (string)$row['nik'] : '';
            $npk = isset($row['npk']) ? (string)$row['npk'] : null;
            $nuptk = isset($row['nuptk']) ? (string)$row['nuptk'] : null;
            
            // Jika NIK dalam format notasi ilmiah (scientific notation), konversi ke string
            if (is_numeric($nik) && strpos($nik, 'E') !== false) {
                $nik = number_format($nik, 0, '', '');
            }
            
            // Jika NPK dalam format notasi ilmiah, konversi ke string
            if (is_numeric($npk) && strpos($npk, 'E') !== false) {
                $npk = number_format($npk, 0, '', '');
            }
            
            // Jika NUPTK dalam format notasi ilmiah, konversi ke string
            if (is_numeric($nuptk) && strpos($nuptk, 'E') !== false) {
                $nuptk = number_format($nuptk, 0, '', '');
            }
            
            return [
                'jenis_jabatan' => $row['jenis_jabatan'] ?? JabatanGuru::JENIS_JABATAN_GURU,
                'keterangan_jabatan' => $row['keterangan_jabatan'],
                'nama' => trim($row['nama']),
                'nik' => trim($nik),
                'jenis_kelamin' => strtoupper(trim($row['jenis_kelamin'] ?? '')) === 'P' ? 'P' : 'L',
                'status' => $row['status'] ?? Guru::STATUS_AKTIF,
                'status_kepegawaian' => $row['status_kepegawaian'],
                'nuptk' => trim($nuptk),
                'npk' => trim($npk),
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