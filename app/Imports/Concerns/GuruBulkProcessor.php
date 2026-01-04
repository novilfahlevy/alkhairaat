<?php

namespace App\Imports\Concerns;

use App\Models\Guru;
use App\Models\Scopes\GuruSekolahNauanganScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuruBulkProcessor
{
    /**
     * Bulk upsert guru data
     * 
     * @param Collection $guruData Collection of validated guru data
     * @return Collection Collection of Guru models (existing + newly inserted)
     */
    public function process(Collection $guruData): Collection
    {
        Log::channel('guru_bulk_import')->info('GuruBulkProcessor: Starting', [
            'total_rows' => $guruData->count()
        ]);

        // Ambil semua identifiers untuk query existing
        $identifiers = $this->extractIdentifiers($guruData);
        
        // Query existing guru
        $existingGurus = $this->findExistingGurus($identifiers);
        
        Log::channel('guru_bulk_import')->info('GuruBulkProcessor: Found existing', [
            'count' => $existingGurus->count()
        ]);

        // Klasifikasi new vs existing
        $classification = $this->classifyGuruData($guruData, $existingGurus);
        
        // Process new guru (bulk insert)
        $insertedGurus = $this->bulkInsertNewGuru($classification['new']);
        
        // Process existing guru (bulk update if needed)
        $this->bulkUpdateExistingGuru($classification['existing'], $existingGurus);
        
        // Return all guru (existing + new)
        $allGurus = $existingGurus->merge($insertedGurus);
        
        Log::channel('guru_bulk_import')->info('GuruBulkProcessor: Completed', [
            'new_count' => $insertedGurus->count(),
            'existing_count' => $existingGurus->count(),
            'total_count' => $allGurus->count()
        ]);

        return $allGurus;
    }

    /**
     * Extract all identifiers (NIK, NPK, NUPTK) from data
     */
    private function extractIdentifiers(Collection $guruData): array
    {
        return [
            'nik' => $guruData->pluck('nik')->filter()->unique()->toArray(),
            'npk' => $guruData->pluck('npk')->filter()->unique()->toArray(),
            'nuptk' => $guruData->pluck('nuptk')->filter()->unique()->toArray(),
        ];
    }

    /**
     * Find existing guru by identifiers
     */
    private function findExistingGurus(array $identifiers): Collection
    {
        return Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
            ->where(function($query) use ($identifiers) {
                $query->whereIn('nik', $identifiers['nik']);
                
                if (!empty($identifiers['npk'])) {
                    $query->orWhereIn('npk', $identifiers['npk']);
                }
                
                if (!empty($identifiers['nuptk'])) {
                    $query->orWhereIn('nuptk', $identifiers['nuptk']);
                }
            })
            ->get();
    }

    /**
     * Classify data into new and existing
     */
    private function classifyGuruData(Collection $guruData, Collection $existingGurus): array
    {
        $new = collect();
        $existing = collect();

        foreach ($guruData as $data) {
            $guru = $this->findGuruByIdentifiers($data, $existingGurus);
            
            if ($guru) {
                $existing->push([
                    'data' => $data,
                    'guru' => $guru
                ]);
            } else {
                $new->push($data);
            }
        }

        return [
            'new' => $new,
            'existing' => $existing
        ];
    }

    /**
     * Find guru by identifiers (NIK > NPK > NUPTK)
     */
    private function findGuruByIdentifiers(array $data, Collection $gurus): ?Guru
    {
        // Priority 1: NIK
        $guru = $gurus->firstWhere('nik', $data['nik']);
        if ($guru) return $guru;
        
        // Priority 2: NPK
        if (!empty($data['npk'])) {
            $guru = $gurus->firstWhere('npk', $data['npk']);
            if ($guru) return $guru;
        }
        
        // Priority 3: NUPTK
        if (!empty($data['nuptk'])) {
            $guru = $gurus->firstWhere('nuptk', $data['nuptk']);
            if ($guru) return $guru;
        }
        
        return null;
    }

    /**
     * Bulk insert new guru
     */
    private function bulkInsertNewGuru(Collection $newGuruData): Collection
    {
        if ($newGuruData->isEmpty()) {
            return collect();
        }

        Log::channel('guru_bulk_import')->info('GuruBulkProcessor: Bulk inserting', [
            'count' => $newGuruData->count()
        ]);

        $insertData = $newGuruData->map(function($data) {
            return [
                'status' => $data['status'] ?? Guru::STATUS_AKTIF,
                'nama_gelar_depan' => $data['gelar_depan'],
                'nama' => $data['nama'],
                'nama_gelar_belakang' => $data['gelar_belakang'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'status_perkawinan' => $data['status_perkawinan'],
                'nik' => $data['nik'],
                'status_kepegawaian' => $data['status_kepegawaian'],
                'npk' => $data['npk'],
                'nuptk' => $data['nuptk'],
                'kontak_wa_hp' => $data['kontak_wa_hp'],
                'kontak_email' => $data['kontak_email'],
                'nomor_rekening' => $data['nomor_rekening'],
                'rekening_atas_nama' => $data['rekening_atas_nama'],
                'bank_rekening' => $data['bank_rekening'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        try {
            // Bulk insert
            DB::table('guru')->insert($insertData);
            
            // Retrieve inserted guru
            $nikList = collect($insertData)->pluck('nik')->toArray();
            return Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
                ->whereIn('nik', $nikList)
                ->get();
                
        } catch (\Exception $e) {
            Log::channel('guru_bulk_import')->error('GuruBulkProcessor: Bulk insert failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual inserts
            return $this->fallbackIndividualInsert($insertData);
        }
    }

    /**
     * Fallback: Insert individually if bulk fails
     */
    private function fallbackIndividualInsert(array $insertData): Collection
    {
        Log::channel('guru_bulk_import')->warning('GuruBulkProcessor: Using fallback individual insert');
        
        $insertedNiks = [];
        
        foreach ($insertData as $data) {
            try {
                DB::table('guru')->insert($data);
                $insertedNiks[] = $data['nik'];
            } catch (\Exception $e) {
                if ($e instanceof \Illuminate\Database\QueryException && 
                    isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                    Log::channel('guru_bulk_import')->warning('GuruBulkProcessor: Duplicate ignored', [
                        'nik' => $data['nik']
                    ]);
                } else {
                    Log::channel('guru_bulk_import')->error('GuruBulkProcessor: Insert failed', [
                        'nik' => $data['nik'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        
        return Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
            ->whereIn('nik', $insertedNiks)
            ->get();
    }

    /**
     * Bulk update existing guru (optional - jika perlu update data existing)
     */
    private function bulkUpdateExistingGuru(Collection $existingData, Collection $existingGurus): void
    {
        if ($existingData->isEmpty()) {
            return;
        }

        // Implementasi update jika diperlukan
        // Bisa menggunakan DB::table()->upsert() atau update individual
        Log::channel('guru_bulk_import')->info('GuruBulkProcessor: Skipping update for existing guru', [
            'count' => $existingData->count()
        ]);
    }
}