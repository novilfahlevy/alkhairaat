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
        $existingNisn = Murid::withoutGlobalScope(MuridNauanganScope::class)
            ->whereIn('nisn', $nisnList)
            ->whereHas('sekolahMurid', function ($query) {
                $query->where('id_sekolah', $this->idSekolah);
            })
            ->pluck('nisn')
            ->toArray();

        // 4. Filter hanya murid dengan NISN yang belum ada
        $newMuridRows = $validRows->reject(function ($row) use ($existingNisn) {
            return in_array($row['nisn'], $existingNisn);
        });

        // Skip jika tidak ada murid baru untuk diproses
        if ($newMuridRows->isEmpty()) {
            Log::channel('murid_bulk_import')->info('No new murid to import - all NISN already exist', [
                'existing_count' => count($existingNisn),
                'total_rows' => $validRows->count()
            ]);
            return;
        }

        // 5. Proses dalam 1 transaction untuk murid baru saja
        DB::transaction(function () use ($newMuridRows, $validRows) {
            $sekolahMuridData = [];
            $alamatData = [];
            $newMuridData = [];

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

            // 6. Bulk insert murid baru
            try {
                DB::table('murid')->insert($newMuridData);

                // Ambil murid yang baru saja diinsert untuk mendapatkan ID
                $nisnList = collect($newMuridData)->pluck('nisn')->toArray();
                $insertedMurid = Murid::withoutGlobalScope(MuridNauanganScope::class)
                    ->whereIn('nisn', $nisnList)
                    ->get()
                    ->keyBy('nisn');

                Log::channel('murid_bulk_import')->info('Bulk inserted new murid', ['count' => count($newMuridData)]);
            } catch (\Exception $e) {
                // Jika bulk insert gagal (mungkin ada duplicate dari chunk lain yang concurrent)
                // Fallback ke insert satu-satu dengan error handling
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

                // Refresh untuk mendapatkan ID murid yang berhasil diinsert
                $nisnList = collect($newMuridData)->pluck('nisn')->toArray();
                $insertedMurid = Murid::withoutGlobalScope(MuridNauanganScope::class)
                    ->whereIn('nisn', $nisnList)
                    ->get()
                    ->keyBy('nisn');
            }

            // 7. Siapkan data untuk batch insert sekolah_murid dan alamat hanya untuk murid baru
            foreach ($newMuridRows as $row) {
                $murid = $insertedMurid->get($row['nisn']);

                if (!$murid) {
                    Log::channel('murid_bulk_import')->warning('Murid not found after insert', ['nisn' => $row['nisn']]);
                    continue;
                }

                // Data sekolah_murid
                $sekolahMuridData[] = [
                    'id_murid' => $murid->id,
                    'id_sekolah' => $this->idSekolah,
                    'tahun_masuk' => $row['tahun_masuk'],
                    'kelas' => $row['kelas'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Data alamat jika ada
                if ($row['alamat_lengkap'] || $row['provinsi']) {
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
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // 8. Batch insert sekolah_murid untuk murid baru
            if (!empty($sekolahMuridData)) {
                DB::table('sekolah_murid')->insert($sekolahMuridData);
                Log::channel('murid_bulk_import')->info('Processed sekolah_murid for new murid', ['count' => count($sekolahMuridData)]);
            }

            // 9. Batch insert alamat untuk murid baru
            if (!empty($alamatData)) {
                DB::table('alamat')->insert($alamatData);
                Log::channel('murid_bulk_import')->info('Processed alamat for new murid', ['count' => count($alamatData)]);
            }

            Log::channel('murid_bulk_import')->info('Import completed', [
                'new_murid_count' => count($newMuridData),
                'skipped_existing_count' => $validRows->count() - $newMuridRows->count(),
                'total_processed' => $validRows->count()
            ]);
        });
    }

    private function validateRows(Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $row = $row->toArray();

            // Skip jika data tidak valid
            if (empty($row['nisn']) || empty($row['nama'])) {
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
                'kontak_wa_hp' => $row['kontak_wa_hp'] ?? null,
                'kontak_email' => $row['kontak_email'] ?? null,
                'nama_ayah' => $row['nama_ayah'] ?? null,
                'nomor_hp_ayah' => $row['nomor_hp_ayah'] ?? null,
                'nama_ibu' => $row['nama_ibu'] ?? null,
                'nomor_hp_ibu' => $row['nomor_hp_ibu'] ?? null,
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
        return 500; // Naikkan chunk size karena sekarang lebih efisien
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
