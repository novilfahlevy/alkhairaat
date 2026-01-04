<?php

namespace App\Imports\Concerns;

use App\Models\JabatanGuru;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JabatanGuruBulkProcessor
{
    /**
     * Bulk insert jabatan guru
     * 
     * @param Collection $guruData Original validated data with jabatan info
     * @param Collection $guruModels Collection of Guru models
     * @param int $idSekolah School ID
     * @return int Number of jabatan inserted
     */
    public function process(Collection $guruData, Collection $guruModels, int $idSekolah): int
    {
        Log::channel('guru_bulk_import')->info('JabatanGuruBulkProcessor: Starting', [
            'total_rows' => $guruData->count(),
            'school_id' => $idSekolah
        ]);

        // Map data dengan guru models
        $jabatanData = $this->prepareJabatanData($guruData, $guruModels, $idSekolah);
        
        if ($jabatanData->isEmpty()) {
            Log::channel('guru_bulk_import')->warning('JabatanGuruBulkProcessor: No data to insert');
            return 0;
        }

        // Bulk insert
        $insertCount = $this->bulkInsertJabatan($jabatanData);
        
        Log::channel('guru_bulk_import')->info('JabatanGuruBulkProcessor: Completed', [
            'inserted_count' => $insertCount
        ]);

        return $insertCount;
    }

    /**
     * Prepare jabatan data for bulk insert
     */
    private function prepareJabatanData(
        Collection $guruData, 
        Collection $guruModels, 
        int $idSekolah
    ): Collection {
        $jabatanData = collect();

        foreach ($guruData as $data) {
            // Find corresponding guru model
            $guru = $this->findGuruModel($data, $guruModels);
            
            if (!$guru) {
                Log::channel('guru_bulk_import')->error('JabatanGuruBulkProcessor: Guru not found', [
                    'nik' => $data['nik']
                ]);
                continue;
            }

            $jabatanData->push([
                'id_guru' => $guru->id,
                'id_sekolah' => $idSekolah,
                'jenis_jabatan' => $data['jenis_jabatan'] ?? JabatanGuru::JENIS_JABATAN_GURU,
                'keterangan_jabatan' => $data['keterangan_jabatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $jabatanData;
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
     * Bulk insert jabatan
     */
    private function bulkInsertJabatan(Collection $jabatanData): int
    {
        try {
            $insertArray = $jabatanData->toArray();
            
            // Chunk untuk menghindari query terlalu besar
            $chunks = array_chunk($insertArray, 500);
            $totalInserted = 0;
            
            foreach ($chunks as $chunk) {
                DB::table('jabatan_guru')->insert($chunk);
                $totalInserted += count($chunk);
            }
            
            Log::channel('guru_bulk_import')->info('JabatanGuruBulkProcessor: Bulk insert successful', [
                'count' => $totalInserted
            ]);
            
            return $totalInserted;
            
        } catch (\Exception $e) {
            Log::channel('guru_bulk_import')->error('JabatanGuruBulkProcessor: Bulk insert failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual inserts
            return $this->fallbackIndividualInsert($jabatanData);
        }
    }

    /**
     * Fallback: Insert individually if bulk fails
     */
    private function fallbackIndividualInsert(Collection $jabatanData): int
    {
        Log::channel('guru_bulk_import')->warning('JabatanGuruBulkProcessor: Using fallback individual insert');
        
        $insertedCount = 0;
        
        foreach ($jabatanData as $data) {
            try {
                DB::table('jabatan_guru')->insert($data);
                $insertedCount++;
            } catch (\Exception $e) {
                Log::channel('guru_bulk_import')->error('JabatanGuruBulkProcessor: Insert failed', [
                    'id_guru' => $data['id_guru'],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $insertedCount;
    }

    /**
     * Optional: Remove duplicate jabatan before insert
     * Berguna jika ingin menghindari duplicate jabatan untuk guru + sekolah yang sama
     */
    public function removeDuplicates(Collection $guruModels, int $idSekolah): int
    {
        $guruIds = $guruModels->pluck('id')->toArray();
        
        $deleted = DB::table('jabatan_guru')
            ->whereIn('id_guru', $guruIds)
            ->where('id_sekolah', $idSekolah)
            ->delete();
            
        Log::channel('guru_bulk_import')->info('JabatanGuruBulkProcessor: Removed duplicates', [
            'count' => $deleted
        ]);
        
        return $deleted;
    }
}