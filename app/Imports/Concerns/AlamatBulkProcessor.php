<?php

namespace App\Imports\Concerns;

use App\Models\Alamat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlamatBulkProcessor
{
    /**
     * Bulk upsert alamat for guru
     * 
     * @param Collection $guruData Original validated data with alamat info
     * @param Collection $guruModels Collection of Guru models
     * @param Collection $existingGurus Collection of existing Guru (untuk determine update vs insert)
     * @return array ['inserted' => int, 'updated' => int]
     */
    public function process(
        Collection $guruData, 
        Collection $guruModels,
        Collection $existingGurus
    ): array {
        Log::channel('guru_bulk_import')->info('AlamatBulkProcessor: Starting', [
            'total_rows' => $guruData->count()
        ]);

        $stats = [
            'inserted' => 0,
            'updated' => 0
        ];

        // Get existing alamat untuk semua guru
        $guruIds = $guruModels->pluck('id')->toArray();
        $existingAlamat = $this->getExistingAlamatForGuru($guruIds);
        
        Log::channel('guru_bulk_import')->info('AlamatBulkProcessor: Found existing alamat', [
            'count' => $existingAlamat->count()
        ]);

        // Prepare data untuk insert dan update
        $preparation = $this->prepareAlamatDataForGuru(
            $guruData, 
            $guruModels, 
            $existingGurus,
            $existingAlamat
        );

        // Bulk insert new alamat
        if (!$preparation['toInsert']->isEmpty()) {
            $stats['inserted'] = $this->bulkInsertAlamat($preparation['toInsert']);
        }

        // Bulk update existing alamat
        if (!$preparation['toUpdate']->isEmpty()) {
            $stats['updated'] = $this->bulkUpdateAlamat($preparation['toUpdate']);
        }

        Log::channel('guru_bulk_import')->info('AlamatBulkProcessor: Completed', $stats);

        return $stats;
    }
    
    /**
     * Bulk upsert alamat for murid
     * 
     * @param Collection $muridData Original validated data with alamat info
     * @param Collection $muridModels Collection of Murid models
     * @param Collection $existingMurids Collection of existing Murid (untuk determine update vs insert)
     * @return array ['inserted' => int, 'updated' => int]
     */
    public function processForMurid(
        Collection $muridData, 
        Collection $muridModels,
        Collection $existingMurids
    ): array {
        Log::channel('murid_bulk_import')->info('AlamatBulkProcessor: Starting for murid', [
            'total_rows' => $muridData->count()
        ]);

        $stats = [
            'inserted' => 0,
            'updated' => 0
        ];

        // Get existing alamat untuk semua murid
        $muridIds = $muridModels->pluck('id')->toArray();
        $existingAlamat = $this->getExistingAlamatForMurid($muridIds);
        
        Log::channel('murid_bulk_import')->info('AlamatBulkProcessor: Found existing alamat for murid', [
            'count' => $existingAlamat->count()
        ]);

        // Prepare data untuk insert dan update
        $preparation = $this->prepareAlamatDataForMurid(
            $muridData, 
            $muridModels, 
            $existingMurids,
            $existingAlamat
        );

        // Bulk insert new alamat
        if (!$preparation['toInsert']->isEmpty()) {
            $stats['inserted'] = $this->bulkInsertAlamat($preparation['toInsert']);
        }

        // Bulk update existing alamat
        if (!$preparation['toUpdate']->isEmpty()) {
            $stats['updated'] = $this->bulkUpdateAlamat($preparation['toUpdate']);
        }

        Log::channel('murid_bulk_import')->info('AlamatBulkProcessor: Completed for murid', $stats);

        return $stats;
    }

    /**
     * Get existing alamat for guru IDs
     */
    private function getExistingAlamatForGuru(array $guruIds): Collection
    {
        return Alamat::whereIn('id_guru', $guruIds)
            ->whereIn('jenis', ['asli', 'domisili'])
            ->get();
    }
    
    /**
     * Get existing alamat for murid IDs
     */
    private function getExistingAlamatForMurid(array $muridIds): Collection
    {
        return Alamat::whereIn('id_murid', $muridIds)
            ->whereIn('jenis', ['asli', 'domisili', 'ayah', 'ibu'])
            ->get();
    }

    /**
     * Prepare alamat data for insert and update (guru)
     */
    private function prepareAlamatDataForGuru(
        Collection $guruData,
        Collection $guruModels,
        Collection $existingGurus,
        Collection $existingAlamat
    ): array {
        $toInsert = collect();
        $toUpdate = collect();

        foreach ($guruData as $data) {
            // Find corresponding guru model
            $guru = $this->findGuruModel($data, $guruModels);
            
            if (!$guru) {
                Log::channel('guru_bulk_import')->warning('AlamatBulkProcessor: Guru not found', [
                    'nik' => $data['nik']
                ]);
                continue;
            }

            // Check if this is existing guru
            $isExistingGuru = $this->isExistingGuru($guru, $existingGurus);

            // Process alamat asli dan domisili
            foreach (['asli', 'domisili'] as $jenisAlamat) {
                $alamatKey = "alamat_{$jenisAlamat}";
                
                if (!isset($data[$alamatKey])) {
                    continue;
                }

                $alamatData = $data[$alamatKey];
                
                // Skip if empty
                if (!array_filter($alamatData, fn($v) => $v !== null && $v !== '')) {
                    continue;
                }

                // Check if alamat exists
                $existingAlamatRecord = $existingAlamat->first(function($a) use ($guru, $jenisAlamat) {
                    return $a->id_guru === $guru->id && $a->jenis === $jenisAlamat;
                });

                $alamatRecord = [
                    'id_guru' => $guru->id,
                    'jenis' => $jenisAlamat,
                    'provinsi' => $alamatData['provinsi'],
                    'kabupaten' => $alamatData['kabupaten'],
                    'kecamatan' => $alamatData['kecamatan'],
                    'kelurahan' => $alamatData['kelurahan'],
                    'rt' => $alamatData['rt'],
                    'rw' => $alamatData['rw'],
                    'kode_pos' => $alamatData['kode_pos'],
                    'alamat_lengkap' => $alamatData['alamat_lengkap'],
                    'koordinat_x' => $alamatData['koordinat_x'],
                    'koordinat_y' => $alamatData['koordinat_y'],
                ];

                if ($existingAlamatRecord) {
                    // Update existing
                    $toUpdate->push([
                        'id' => $existingAlamatRecord->id,
                        'data' => array_merge($alamatRecord, [
                            'updated_at' => now()
                        ])
                    ]);
                } else {
                    // Insert new
                    $toInsert->push(array_merge($alamatRecord, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }

        return [
            'toInsert' => $toInsert,
            'toUpdate' => $toUpdate
        ];
    }
    
    /**
     * Prepare alamat data for insert and update (murid)
     */
    private function prepareAlamatDataForMurid(
        Collection $muridData,
        Collection $muridModels,
        Collection $existingMurids,
        Collection $existingAlamat
    ): array {
        $toInsert = collect();
        $toUpdate = collect();

        foreach ($muridData as $data) {
            // Find corresponding murid model
            $murid = $this->findMuridModel($data, $muridModels);
            
            if (!$murid) {
                Log::channel('murid_bulk_import')->warning('AlamatBulkProcessor: Murid not found', [
                    'nisn' => $data['nisn']
                ]);
                continue;
            }

            // Check if this is existing murid
            $isExistingMurid = $this->isExistingMurid($murid, $existingMurids);

            // Process alamat asli, domisili, ayah, dan ibu
            foreach (['asli', 'domisili', 'ayah', 'ibu'] as $jenisAlamat) {
                $alamatKey = "alamat_{$jenisAlamat}";
                
                if (!isset($data[$alamatKey])) {
                    continue;
                }

                $alamatData = $data[$alamatKey];
                
                // Skip if empty
                if (!array_filter($alamatData, fn($v) => $v !== null && $v !== '')) {
                    continue;
                }

                // Check if alamat exists
                $existingAlamatRecord = $existingAlamat->first(function($a) use ($murid, $jenisAlamat) {
                    return $a->id_murid === $murid->id && $a->jenis === $jenisAlamat;
                });

                $alamatRecord = [
                    'id_murid' => $murid->id,
                    'jenis' => $jenisAlamat,
                    'provinsi' => $alamatData['provinsi'],
                    'kabupaten' => $alamatData['kabupaten'],
                    'kecamatan' => $alamatData['kecamatan'],
                    'kelurahan' => $alamatData['kelurahan'],
                    'rt' => $alamatData['rt'],
                    'rw' => $alamatData['rw'],
                    'kode_pos' => $alamatData['kode_pos'],
                    'alamat_lengkap' => $alamatData['alamat_lengkap'],
                    'koordinat_x' => $alamatData['koordinat_x'],
                    'koordinat_y' => $alamatData['koordinat_y'],
                ];

                if ($existingAlamatRecord) {
                    // Update existing
                    $toUpdate->push([
                        'id' => $existingAlamatRecord->id,
                        'data' => array_merge($alamatRecord, [
                            'updated_at' => now()
                        ])
                    ]);
                } else {
                    // Insert new
                    $toInsert->push(array_merge($alamatRecord, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }

        return [
            'toInsert' => $toInsert,
            'toUpdate' => $toUpdate
        ];
    }

    /**
     * Find guru model by identifiers
     */
    private function findGuruModel(array $data, Collection $guruModels): mixed
    {
        // Priority 1: NIK
        $guru = $guruModels->firstWhere('nik', $data['nik']);
        if ($guru) return $guru;
        
        // Priority 2: NPK
        if (!empty($data['npk'])) {
            $guru = $guruModels->firstWhere('npk', $data['npk']);
            if ($guru) return $guru;
        }
        
        // Priority 3: NUPTK
        if (!empty($data['nuptk'])) {
            $guru = $guruModels->firstWhere('nuptk', $data['nuptk']);
            if ($guru) return $guru;
        }
        
        return null;
    }
    
    /**
     * Find murid model by NISN
     */
    private function findMuridModel(array $data, Collection $muridModels): mixed
    {
        return $muridModels->firstWhere('nisn', $data['nisn']);
    }

    /**
     * Check if guru is existing (not newly inserted in this import)
     */
    private function isExistingGuru($guru, Collection $existingGurus): bool
    {
        return $existingGurus->contains(function($existingGuru) use ($guru) {
            return $existingGuru->id === $guru->id;
        });
    }
    
    /**
     * Check if murid is existing (not newly inserted in this import)
     */
    private function isExistingMurid($murid, Collection $existingMurids): bool
    {
        return $existingMurids->contains(function($existingMurid) use ($murid) {
            return $existingMurid->id === $murid->id;
        });
    }

    /**
     * Bulk insert alamat
     */
    private function bulkInsertAlamat(Collection $alamatData): int
    {
        try {
            $insertArray = $alamatData->toArray();
            
            // Chunk untuk menghindari query terlalu besar
            $chunks = array_chunk($insertArray, 500);
            $totalInserted = 0;
            
            foreach ($chunks as $chunk) {
                DB::table('alamat')->insert($chunk);
                $totalInserted += count($chunk);
            }
            
            Log::channel('murid_bulk_import')->info('AlamatBulkProcessor: Bulk insert successful', [
                'count' => $totalInserted
            ]);
            
            return $totalInserted;
            
        } catch (\Exception $e) {
            Log::channel('murid_bulk_import')->error('AlamatBulkProcessor: Bulk insert failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual inserts
            return $this->fallbackIndividualInsert($alamatData);
        }
    }

    /**
     * Bulk update alamat using CASE WHEN
     */
    private function bulkUpdateAlamat(Collection $alamatData): int
    {
        if ($alamatData->isEmpty()) {
            return 0;
        }

        try {
            $ids = $alamatData->pluck('id')->toArray();
            
            // Build CASE WHEN statements for each field
            $fields = [
                'provinsi', 'kabupaten', 'kecamatan', 'kelurahan',
                'rt', 'rw', 'kode_pos', 'alamat_lengkap',
                'koordinat_x', 'koordinat_y', 'updated_at'
            ];

            $cases = [];
            foreach ($fields as $field) {
                $whenClauses = [];
                foreach ($alamatData as $item) {
                    $value = $item['data'][$field] ?? null;
                    $valueStr = $value === null ? 'NULL' : DB::connection()->getPdo()->quote($value);
                    $whenClauses[] = "WHEN {$item['id']} THEN {$valueStr}";
                }
                $cases[$field] = "CASE id " . implode(' ', $whenClauses) . " END";
            }

            // Build update query
            $setClauses = [];
            foreach ($cases as $field => $case) {
                $setClauses[] = "`{$field}` = {$case}";
            }

            $query = "UPDATE alamat SET " . implode(', ', $setClauses) . 
                     " WHERE id IN (" . implode(',', $ids) . ")";

            $affected = DB::update($query);
            
            Log::channel('murid_bulk_import')->info('AlamatBulkProcessor: Bulk update successful', [
                'count' => $affected
            ]);
            
            return $affected;
            
        } catch (\Exception $e) {
            Log::channel('murid_bulk_import')->error('AlamatBulkProcessor: Bulk update failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual updates
            return $this->fallbackIndividualUpdate($alamatData);
        }
    }

    /**
     * Fallback: Insert individually
     */
    private function fallbackIndividualInsert(Collection $alamatData): int
    {
        Log::channel('murid_bulk_import')->warning('AlamatBulkProcessor: Using fallback individual insert');
        
        $insertedCount = 0;
        
        foreach ($alamatData as $data) {
            try {
                DB::table('alamat')->insert($data);
                $insertedCount++;
            } catch (\Exception $e) {
                Log::channel('murid_bulk_import')->error('AlamatBulkProcessor: Insert failed', [
                    'id_guru' => $data['id_guru'] ?? $data['id_murid'] ?? null,
                    'jenis' => $data['jenis'],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $insertedCount;
    }

    /**
     * Fallback: Update individually
     */
    private function fallbackIndividualUpdate(Collection $alamatData): int
    {
        Log::channel('murid_bulk_import')->warning('AlamatBulkProcessor: Using fallback individual update');
        
        $updatedCount = 0;
        
        foreach ($alamatData as $item) {
            try {
                DB::table('alamat')
                    ->where('id', $item['id'])
                    ->update($item['data']);
                $updatedCount++;
            } catch (\Exception $e) {
                Log::channel('murid_bulk_import')->error('AlamatBulkProcessor: Update failed', [
                    'id' => $item['id'],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $updatedCount;
    }
}