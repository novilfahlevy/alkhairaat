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

        // 2. Ambil semua NISN dan NIK dari chunk ini
        $nisnList = $validRows->pluck('nisn')->filter()->unique()->toArray();

        // 3. Bulk query - ambil semua murid yang sudah ada dalam 1 query
        $existingMurid = Murid::withoutGlobalScope(MuridNauanganScope::class)
            ->whereIn('nisn', $nisnList)
            ->get()
            ->keyBy('nisn'); // Index by NISN untuk fast lookup

        // 4. Proses dalam 1 transaction untuk seluruh chunk
        DB::transaction(function () use ($validRows, $existingMurid) {
            $sekolahMuridData = [];
            $alamatData = [];
            $newMuridData = [];

            foreach ($validRows as $row) {
                // Cek apakah murid sudah ada
                $murid = $existingMurid->get($row['nisn']);

                // Jika belum ada, siapkan data untuk bulk insert
                if (!$murid) {
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
            }

            // 5. Bulk insert murid baru jika ada
            if (!empty($newMuridData)) {
                try {
                    DB::table('murid')->insert($newMuridData);
                    
                    // Refresh existing murid setelah bulk insert
                    $nisnList = collect($newMuridData)->pluck('nisn')->toArray();
                    $newlyInserted = Murid::withoutGlobalScope(MuridNauanganScope::class)
                        ->whereIn('nisn', $nisnList)
                        ->get()
                        ->keyBy('nisn');
                    
                    $existingMurid = $existingMurid->merge($newlyInserted);
                    
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
                            if ($e2->errorInfo[1] != 1062) {
                                throw $e2;
                            }
                        }
                    }
                    
                    // Refresh lagi
                    $nisnList = collect($newMuridData)->pluck('nisn')->toArray();
                    $newlyInserted = Murid::withoutGlobalScope(MuridNauanganScope::class)
                        ->whereIn('nisn', $nisnList)
                        ->get()
                        ->keyBy('nisn');
                    
                    $existingMurid = $existingMurid->merge($newlyInserted);
                }
            }

            // 6. Siapkan data untuk batch upsert sekolah_murid dan alamat
            foreach ($validRows as $row) {
                $murid = $existingMurid->get($row['nisn']);
                
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
                    'status_kelulusan' => $row['status_kelulusan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Data alamat jika ada
                if ($row['alamat_lengkap'] || $row['provinsi']) {
                    $alamatData[] = [
                        'id_murid' => $murid->id,
                        'jenis' => 'asli',
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

            // 7. Batch upsert sekolah_murid
            if (!empty($sekolahMuridData)) {
                foreach ($sekolahMuridData as $data) {
                    SekolahMurid::updateOrCreate(
                        [
                            'id_murid' => $data['id_murid'],
                            'id_sekolah' => $data['id_sekolah']
                        ],
                        $data
                    );
                }
                Log::channel('murid_bulk_import')->info('Processed sekolah_murid', ['count' => count($sekolahMuridData)]);
            }

            // 8. Batch upsert alamat
            if (!empty($alamatData)) {
                foreach ($alamatData as $data) {
                    Alamat::updateOrCreate(
                        [
                            'id_murid' => $data['id_murid'],
                            'jenis' => $data['jenis']
                        ],
                        $data
                    );
                }
                Log::channel('murid_bulk_import')->info('Processed alamat', ['count' => count($alamatData)]);
            }
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
                'nik' => isset($row['nik']) ? trim((string)$row['nik']) : null,
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir'] ?? null),
                'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L') === 'P' ? 'P' : 'L',
                'nama_ayah' => $row['nama_ayah'] ?? null,
                'nomor_hp_ayah' => $row['nomor_hp_ayah'] ?? null,
                'nama_ibu' => $row['nama_ibu'] ?? null,
                'nomor_hp_ibu' => $row['nomor_hp_ibu'] ?? null,
                'kontak_wa_hp' => $row['kontak_wa_hp'] ?? null,
                'kontak_email' => $row['kontak_email'] ?? null,
                'tahun_masuk' => (int)($row['tahun_masuk'] ?? date('Y')),
                'kelas' => $row['kelas'] ?? null,
                'status_kelulusan' => strtolower($row['status_kelulusan'] ?? '') === 'ya' ? 'ya' : 'tidak',
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