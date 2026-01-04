<?php

namespace App\Imports\Concerns;

use App\Models\SekolahMurid;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SekolahMuridBulkProcessor
{
    /**
     * Bulk upsert sekolah murid data
     * 
     * @param Collection $muridData Original validated data with sekolah info
     * @param Collection $muridModels Collection of Murid models
     * @param int $idSekolah School ID
     * @return array ['inserted' => int, 'updated' => int]
     */
    public function process(Collection $muridData, Collection $muridModels, int $idSekolah): array
    {
        Log::channel('murid_bulk_import')->info('SekolahMuridBulkProcessor: Starting', [
            'total_rows' => $muridData->count(),
            'school_id' => $idSekolah
        ]);

        $stats = [
            'inserted' => 0,
            'updated' => 0
        ];

        // Get existing sekolah murid untuk semua murid
        $muridIds = $muridModels->pluck('id')->toArray();
        $existingSekolahMurid = $this->getExistingSekolahMurid($muridIds, $idSekolah);
        
        Log::channel('murid_bulk_import')->info('SekolahMuridBulkProcessor: Found existing sekolah murid', [
            'count' => $existingSekolahMurid->count()
        ]);

        // Prepare data untuk insert dan update
        $preparation = $this->prepareSekolahMuridData(
            $idSekolah,
            $muridData, 
            $muridModels, 
            $existingSekolahMurid
        );

        // Bulk insert new sekolah murid
        if (!$preparation['toInsert']->isEmpty()) {
            $stats['inserted'] = $this->bulkInsertSekolahMurid($preparation['toInsert']);
        }

        // Bulk update existing sekolah murid
        if (!$preparation['toUpdate']->isEmpty()) {
            $stats['updated'] = $this->bulkUpdateSekolahMurid($preparation['toUpdate']);
        }

        Log::channel('murid_bulk_import')->info('SekolahMuridBulkProcessor: Completed', $stats);

        return $stats;
    }

    /**
     * Get existing sekolah murid for murid IDs and school ID
     */
    private function getExistingSekolahMurid(array $muridIds, int $idSekolah): Collection
    {
        return SekolahMurid::whereIn('id_murid', $muridIds)
            ->where('id_sekolah', $idSekolah)
            ->get();
    }

    /**
     * Prepare sekolah murid data for insert and update
     */
    private function prepareSekolahMuridData(
        int $idSekolah,
        Collection $muridData,
        Collection $muridModels,
        Collection $existingSekolahMurid
    ): array {
        $toInsert = collect();
        $toUpdate = collect();

        foreach ($muridData as $data) {
            // Find corresponding murid model
            $murid = $this->findMuridModel($data, $muridModels);
            
            if (!$murid) {
                Log::channel('murid_bulk_import')->warning('SekolahMuridBulkProcessor: Murid not found', [
                    'nisn' => $data['nisn']
                ]);
                continue;
            }

            // Check if sekolah murid exists
            $existingRecord = $existingSekolahMurid->firstWhere('id_murid', $murid->id);

            $sekolahMuridRecord = [
                'id_murid' => $murid->id,
                'id_sekolah' => $data['id_sekolah'] ?? null,
                'tahun_masuk' => $data['tahun_masuk'],
                'tahun_keluar' => $data['tahun_keluar'],
                'tahun_mutasi_masuk' => $data['tahun_mutasi_masuk'],
                'alasan_mutasi_masuk' => $data['alasan_mutasi_masuk'],
                'tahun_mutasi_keluar' => $data['tahun_mutasi_keluar'],
                'alasan_mutasi_keluar' => $data['alasan_mutasi_keluar'],
                'kelas' => $data['kelas'],
                'status_kelulusan' => $data['status_kelulusan'] ?? null,
            ];

            if ($existingRecord) {
                // Update existing
                $toUpdate->push([
                    'id' => $existingRecord->id,
                    'data' => array_merge($sekolahMuridRecord, [
                        'updated_at' => now()
                    ])
                ]);
            } else {
                // Insert new
                $toInsert->push(array_merge($sekolahMuridRecord, [
                    'id_sekolah' => $idSekolah, // Use the school ID from constructor
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        return [
            'toInsert' => $toInsert,
            'toUpdate' => $toUpdate
        ];
    }

    /**
     * Find murid model by NISN
     */
    private function findMuridModel(array $data, Collection $muridModels): mixed
    {
        return $muridModels->firstWhere('nisn', $data['nisn']);
    }

    /**
     * Bulk insert sekolah murid
     */
    private function bulkInsertSekolahMurid(Collection $sekolahMuridData): int
    {
        try {
            $insertArray = $sekolahMuridData->toArray();
            
            // Chunk untuk menghindari query terlalu besar
            $chunks = array_chunk($insertArray, 500);
            $totalInserted = 0;
            
            foreach ($chunks as $chunk) {
                DB::table('sekolah_murid')->insert($chunk);
                $totalInserted += count($chunk);
            }
            
            Log::channel('murid_bulk_import')->info('SekolahMuridBulkProcessor: Bulk insert successful', [
                'count' => $totalInserted
            ]);
            
            return $totalInserted;
            
        } catch (\Exception $e) {
            Log::channel('murid_bulk_import')->error('SekolahMuridBulkProcessor: Bulk insert failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual inserts
            return $this->fallbackIndividualInsert($sekolahMuridData);
        }
    }

    /**
     * Bulk update sekolah murid using CASE WHEN
     */
    private function bulkUpdateSekolahMurid(Collection $sekolahMuridData): int
    {
        if ($sekolahMuridData->isEmpty()) {
            return 0;
        }

        try {
            $ids = $sekolahMuridData->pluck('id')->toArray();
            
            // Build CASE WHEN statements for each field
            $fields = [
                'tahun_masuk', 'tahun_keluar', 'tahun_mutasi_masuk', 
                'alasan_mutasi_masuk', 'tahun_mutasi_keluar', 
                'alasan_mutasi_keluar', 'kelas', 'status_kelulusan', 'updated_at'
            ];

            $cases = [];
            foreach ($fields as $field) {
                $whenClauses = [];
                foreach ($sekolahMuridData as $item) {
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

            $query = "UPDATE sekolah_murid SET " . implode(', ', $setClauses) . 
                     " WHERE id IN (" . implode(',', $ids) . ")";

            $affected = DB::update($query);
            
            Log::channel('murid_bulk_import')->info('SekolahMuridBulkProcessor: Bulk update successful', [
                'count' => $affected
            ]);
            
            return $affected;
            
        } catch (\Exception $e) {
            Log::channel('murid_bulk_import')->error('SekolahMuridBulkProcessor: Bulk update failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual updates
            return $this->fallbackIndividualUpdate($sekolahMuridData);
        }
    }

    /**
     * Fallback: Insert individually
     */
    private function fallbackIndividualInsert(Collection $sekolahMuridData): int
    {
        Log::channel('murid_bulk_import')->warning('SekolahMuridBulkProcessor: Using fallback individual insert');
        
        $insertedCount = 0;
        
        foreach ($sekolahMuridData as $data) {
            try {
                DB::table('sekolah_murid')->insert($data);
                $insertedCount++;
            } catch (\Exception $e) {
                Log::channel('murid_bulk_import')->error('SekolahMuridBulkProcessor: Insert failed', [
                    'id_murid' => $data['id_murid'],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $insertedCount;
    }

    /**
     * Fallback: Update individually
     */
    private function fallbackIndividualUpdate(Collection $sekolahMuridData): int
    {
        Log::channel('murid_bulk_import')->warning('SekolahMuridBulkProcessor: Using fallback individual update');
        
        $updatedCount = 0;
        
        foreach ($sekolahMuridData as $item) {
            try {
                DB::table('sekolah_murid')
                    ->where('id', $item['id'])
                    ->update($item['data']);
                $updatedCount++;
            } catch (\Exception $e) {
                Log::channel('murid_bulk_import')->error('SekolahMuridBulkProcessor: Update failed', [
                    'id' => $item['id'],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $updatedCount;
    }
}