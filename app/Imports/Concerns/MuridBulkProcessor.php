<?php

namespace App\Imports\Concerns;

use App\Models\Murid;
use App\Models\Scopes\MuridNauanganScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MuridBulkProcessor
{
    /**
     * Bulk upsert murid data
     * 
     * @param Collection $muridData Collection of validated murid data
     * @param Collection $existingMurids Collection of existing Murid models
     * @return Collection Collection of Murid models (existing + newly inserted)
     */
    public function process(Collection $muridData, Collection $existingMurids): Collection
    {
        Log::channel('murid_bulk_import')->info('MuridBulkProcessor: Starting', [
            'total_rows' => $muridData->count(),
            'existing_count' => $existingMurids->count()
        ]);

        // Klasifikasi new vs existing
        $classification = $this->classifyMuridData($muridData, $existingMurids);
        
        // Process new murid (bulk insert)
        $insertedMurids = $this->bulkInsertNewMurid($classification['new']);
        
        // Process existing murid (bulk update)
        $this->bulkUpdateExistingMurid($classification['existing'], $existingMurids);
        
        // Return all murid (existing + new)
        $allMurids = $existingMurids->merge($insertedMurids);
        
        Log::channel('murid_bulk_import')->info('MuridBulkProcessor: Completed', [
            'new_count' => $insertedMurids->count(),
            'existing_count' => $existingMurids->count(),
            'total_count' => $allMurids->count()
        ]);

        return $allMurids;
    }

    /**
     * Classify data into new and existing
     */
    private function classifyMuridData(Collection $muridData, Collection $existingMurids): array
    {
        $new = collect();
        $existing = collect();

        foreach ($muridData as $data) {
            $murid = $existingMurids->firstWhere('nisn', $data['nisn']);
            
            if ($murid) {
                $existing->push([
                    'data' => $data,
                    'murid' => $murid
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
     * Bulk insert new murid
     */
    private function bulkInsertNewMurid(Collection $newMuridData): Collection
    {
        if ($newMuridData->isEmpty()) {
            return collect();
        }

        Log::channel('murid_bulk_import')->info('MuridBulkProcessor: Bulk inserting', [
            'count' => $newMuridData->count()
        ]);

        $insertData = $newMuridData->map(function($data) {
            return [
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'nik' => $data['nik'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_ayah' => $data['nama_ayah'],
                'nomor_hp_ayah' => $data['nomor_hp_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'nomor_hp_ibu' => $data['nomor_hp_ibu'],
                'kontak_wa_hp' => $data['kontak_wa_hp'],
                'kontak_email' => $data['kontak_email'],
                'status_alumni' => false,
                'tanggal_update_data' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        try {
            // Bulk insert
            DB::table('murid')->insert($insertData);
            
            // Retrieve inserted murid
            $nisnList = collect($insertData)->pluck('nisn')->toArray();
            return Murid::withoutGlobalScope(MuridNauanganScope::class)
                ->whereIn('nisn', $nisnList)
                ->get();
                
        } catch (\Exception $e) {
            Log::channel('murid_bulk_import')->error('MuridBulkProcessor: Bulk insert failed', [
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
        Log::channel('murid_bulk_import')->warning('MuridBulkProcessor: Using fallback individual insert');
        
        $insertedNisns = [];
        
        foreach ($insertData as $data) {
            try {
                DB::table('murid')->insert($data);
                $insertedNisns[] = $data['nisn'];
            } catch (\Exception $e) {
                if ($e instanceof \Illuminate\Database\QueryException && 
                    isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                    Log::channel('murid_bulk_import')->warning('MuridBulkProcessor: Duplicate ignored', [
                        'nisn' => $data['nisn']
                    ]);
                } else {
                    Log::channel('murid_bulk_import')->error('MuridBulkProcessor: Insert failed', [
                        'nisn' => $data['nisn'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        
        return Murid::withoutGlobalScope(MuridNauanganScope::class)
            ->whereIn('nisn', $insertedNisns)
            ->get();
    }

    /**
     * Bulk update existing murid
     */
    private function bulkUpdateExistingMurid(Collection $existingData, Collection $existingMurids): void
    {
        if ($existingData->isEmpty()) {
            return;
        }

        Log::channel('murid_bulk_import')->info('MuridBulkProcessor: Bulk updating', [
            'count' => $existingData->count()
        ]);

        $updateData = $existingData->map(function($item) {
            return [
                'id' => $item['murid']->id,
                'nama' => $item['data']['nama'],
                'nik' => $item['data']['nik'],
                'tempat_lahir' => $item['data']['tempat_lahir'],
                'tanggal_lahir' => $item['data']['tanggal_lahir'],
                'jenis_kelamin' => $item['data']['jenis_kelamin'],
                'nama_ayah' => $item['data']['nama_ayah'],
                'nomor_hp_ayah' => $item['data']['nomor_hp_ayah'],
                'nama_ibu' => $item['data']['nama_ibu'],
                'nomor_hp_ibu' => $item['data']['nomor_hp_ibu'],
                'kontak_wa_hp' => $item['data']['kontak_wa_hp'],
                'kontak_email' => $item['data']['kontak_email'],
                'status_alumni' => false,
                'tanggal_update_data' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        try {
            // Build CASE WHEN statements for each field
            $fields = [
                'nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'nama_ayah', 'nomor_hp_ayah', 'nama_ibu', 'nomor_hp_ibu',
                'kontak_wa_hp', 'kontak_email', 'status_alumni', 
                'tanggal_update_data', 'updated_at'
            ];

            $cases = [];
            foreach ($fields as $field) {
                $whenClauses = [];
                foreach ($updateData as $item) {
                    $value = $item[$field] ?? null;
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

            $ids = array_column($updateData, 'id');
            $query = "UPDATE murid SET " . implode(', ', $setClauses) . 
                     " WHERE id IN (" . implode(',', $ids) . ")";

            $affected = DB::update($query);
            
            Log::channel('murid_bulk_import')->info('MuridBulkProcessor: Bulk update successful', [
                'count' => $affected
            ]);
            
        } catch (\Exception $e) {
            Log::channel('murid_bulk_import')->error('MuridBulkProcessor: Bulk update failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: individual updates
            $this->fallbackIndividualUpdate($updateData);
        }
    }

    /**
     * Fallback: Update individually
     */
    private function fallbackIndividualUpdate(array $updateData): void
    {
        Log::channel('murid_bulk_import')->warning('MuridBulkProcessor: Using fallback individual update');
        
        foreach ($updateData as $data) {
            try {
                $id = $data['id'];
                unset($data['id']);
                
                DB::table('murid')
                    ->where('id', $id)
                    ->update($data);
            } catch (\Exception $e) {
                Log::channel('murid_bulk_import')->error('MuridBulkProcessor: Update failed', [
                    'id' => $id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}