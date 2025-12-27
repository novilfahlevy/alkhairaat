<?php

namespace App\Imports;

use App\Models\Murid;
use App\Models\SekolahMurid;
use App\Models\Alamat;
use App\Models\Scopes\MuridNauanganScope;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MuridImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function __construct(private int $idSekolah) {}

    public function collection(Collection $rows)
    {
        // 1. Validasi dan siapkan data
        $validRows = $this->validateRows($rows);

        if ($validRows->isEmpty()) {
            return;
        }

        // 2. Ambil semua NISN dari chunk ini
        $nisnList = $validRows->pluck('nisn')->filter()->unique()->toArray();

        // 3. Bulk query - ambil NISN yang sudah ada dalam 1 query
        $existingMurids = Murid::withoutGlobalScope(MuridNauanganScope::class)
            ->whereIn('nisn', $nisnList)
            ->get()
            ->keyBy('nisn');

        // 4. Filter murid baru dan murid yang sudah ada
        $newMuridRows = $validRows->reject(function ($row) use ($existingMurids) {
            return $existingMurids->has($row['nisn']);
        });

        $existingMuridRows = $validRows->filter(function ($row) use ($existingMurids) {
            return $existingMurids->has($row['nisn']);
        });

        // 5. Proses dalam 1 transaction untuk semua murid
        DB::transaction(function () use ($newMuridRows, $existingMuridRows, $existingMurids) {
            $sekolahMuridData = [];
            $alamatData = [];
            $newMuridData = [];
            $updatedMuridIds = [];

            // Proses murid baru
            if (!$newMuridRows->isEmpty()) {
                foreach ($newMuridRows as $row) {
                    // Siapkan data untuk bulk insert murid baru
                    $newMuridData[] = [
                        'nisn' => $row['nisn'],
                        'nama' => $row['nama'],
                        'nik' => $row['nik'],
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => $row['tanggal_lahir'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'nama_ayah' => $row['nama_ayah'],
                        'nomor_hp_ayah' => $row['nomor_hp_ayah'],
                        'nama_ibu' => $row['nama_ibu'],
                        'nomor_hp_ibu' => $row['nomor_hp_ibu'],
                        'kontak_wa_hp' => $row['kontak_wa_hp'],
                        'kontak_email' => $row['kontak_email'],
                        'tanggal_update_data' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert murid baru
                try {
                    DB::table('murid')->insert($newMuridData);
                    Log::channel('murid_bulk_import')->info('Bulk inserted new murid', ['count' => count($newMuridData)]);
                } catch (\Exception $e) {
                    // Jika bulk insert gagal, fallback ke insert satu-satu
                    Log::channel('murid_bulk_import')->warning('Bulk insert failed, falling back to individual inserts', [
                        'error' => $e->getMessage()
                    ]);

                    foreach ($newMuridData as $data) {
                        try {
                            DB::table('murid')->insert($data);
                        } catch (Exception $e2) {
                            if ($e2 instanceof \Illuminate\Database\QueryException && isset($e2->errorInfo[1]) && $e2->errorInfo[1] == 1062) {
                                // Duplicate entry, ignore
                            } else {
                                throw $e2;
                            }
                        }
                    }
                }

                // Refresh untuk mendapatkan ID murid yang berhasil diinsert
                $nisnList = collect($newMuridData)->pluck('nisn')->toArray();
                $insertedMurids = Murid::withoutGlobalScope(MuridNauanganScope::class)
                    ->whereIn('nisn', $nisnList)
                    ->get()
                    ->keyBy('nisn');
                    
                // Gabungkan dengan murid yang sudah ada
                $allMurids = $existingMurids->merge($insertedMurids);
            } else {
                $allMurids = $existingMurids;
            }

            // Proses data SekolahMurid dan Alamat untuk semua murid (baru dan yang sudah ada)
            $allMuridRows = $newMuridRows->merge($existingMuridRows);
            
            foreach ($allMuridRows as $row) {
                $murid = $allMurids->get($row['nisn']);
                
                if (!$murid) {
                    Log::channel('murid_bulk_import')->warning('Murid not found', ['nisn' => $row['nisn']]);
                    continue;
                }

                // Update data murid yang sudah ada
                if ($existingMuridRows->contains(function ($existingRow) use ($row) {
                    return $existingRow['nisn'] === $row['nisn'];
                })) {
                    $updatedMuridIds[] = $murid->id;
                    
                    // Update data murid
                    $murid->update([
                        'nama' => $row['nama'],
                        'nik' => $row['nik'],
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => $row['tanggal_lahir'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'nama_ayah' => $row['nama_ayah'],
                        'nomor_hp_ayah' => $row['nomor_hp_ayah'],
                        'nama_ibu' => $row['nama_ibu'],
                        'nomor_hp_ibu' => $row['nomor_hp_ibu'],
                        'kontak_wa_hp' => $row['kontak_wa_hp'],
                        'kontak_email' => $row['kontak_email'],
                        'tanggal_update_data' => now(),
                    ]);
                }

                // Cek apakah murid sudah terdaftar di sekolah ini
                $sekolahMurid = SekolahMurid::where('id_murid', $murid->id)
                    ->where('id_sekolah', $this->idSekolah)
                    ->first();

                if ($sekolahMurid) {
                    // Update data sekolah_murid yang sudah ada
                    $sekolahMurid->update([
                        'tahun_masuk' => $row['tahun_masuk'],
                        'kelas' => $row['kelas'],
                    ]);
                } else {
                    // Data sekolah_murid baru
                    $sekolahMuridData[] = [
                        'id_murid' => $murid->id,
                        'id_sekolah' => $this->idSekolah,
                        'tahun_masuk' => $row['tahun_masuk'],
                        'kelas' => $row['kelas'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Cek dan update alamat
                if ($row['alamat_lengkap'] || $row['provinsi']) {
                    $alamat = Alamat::where('id_murid', $murid->id)
                        ->where('jenis', Alamat::JENIS_ASLI)
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
                            'koordinat_x' => $row['latitude_koordinat_x'],
                            'koordinat_y' => $row['longitude_koordinat_y'],
                        ]);
                    } else {
                        // Data alamat baru
                        $alamatData[] = [
                            'id_murid' => $murid->id,
                            'jenis' => Alamat::JENIS_ASLI,
                            'provinsi' => $row['provinsi'],
                            'kabupaten' => $row['kabupaten'],
                            'kecamatan' => $row['kecamatan'],
                            'kelurahan' => $row['kelurahan'],
                            'rt' => $row['rt'],
                            'rw' => $row['rw'],
                            'kode_pos' => $row['kode_pos'],
                            'alamat_lengkap' => $row['alamat_lengkap'],
                            'koordinat_x' => $row['latitude_koordinat_x'],
                            'koordinat_y' => $row['longitude_koordinat_y'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Batch insert sekolah_murid baru
            if (!empty($sekolahMuridData)) {
                DB::table('sekolah_murid')->insert($sekolahMuridData);
                Log::channel('murid_bulk_import')->info('Processed new sekolah_murid', ['count' => count($sekolahMuridData)]);
            }

            // Batch insert alamat baru
            if (!empty($alamatData)) {
                DB::table('alamat')->insert($alamatData);
                Log::channel('murid_bulk_import')->info('Processed new alamat', ['count' => count($alamatData)]);
            }

            Log::channel('murid_bulk_import')->info('Import completed', [
                'new_murid_count' => count($newMuridData),
                'updated_murid_count' => count($updatedMuridIds),
                'total_processed' => $allMuridRows->count()
            ]);
        });
    }

    private function validateRows(Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $row = $row->toArray();

            // Validasi field wajib: nisn, nama, jenis_kelamin, tahun_masuk
            if (empty($row['nisn']) || empty($row['nama']) || empty($row['jenis_kelamin']) || empty($row['tahun_masuk'])) {
                return null;
            }

            return [
                'nisn' => trim((string)$row['nisn']),
                'nama' => trim($row['nama']),
                'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L') === 'P' ? 'P' : 'L',
                'tahun_masuk' => (int)($row['tahun_masuk'] ?? date('Y')),
                'nik' => isset($row['nik']) ? trim((string)$row['nik']) : null,
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir'] ?? null),
                'kelas' => $row['kelas'] ?? null,
                'nama_ayah' => $row['nama_ayah'] ?? null,
                'nomor_hp_ayah' => $row['nomor_hp_ayah'] ?? null,
                'nama_ibu' => $row['nama_ibu'] ?? null,
                'nomor_hp_ibu' => $row['nomor_hp_ibu'] ?? null,
                'kontak_wa_hp' => $row['kontak_wa_hp'] ?? null,
                'kontak_email' => $row['kontak_email'] ?? null,
                'provinsi' => $row['provinsi'] ?? null,
                'kabupaten' => $row['kabupaten'] ?? null,
                'kecamatan' => $row['kecamatan'] ?? null,
                'kelurahan' => $row['kelurahan'] ?? null,
                'rt' => $row['rt'] ?? null,
                'rw' => $row['rw'] ?? null,
                'kode_pos' => $row['kode_pos'] ?? null,
                'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
                'latitude_koordinat_x' => isset($row['latitude_koordinat_x']) ? (float)$row['latitude_koordinat_x'] : null,
                'longitude_koordinat_y' => isset($row['longitude_koordinat_y']) ? (float)$row['longitude_koordinat_y'] : null,
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
        } catch (\Exception $e) {
            return null;
        }
    }
}