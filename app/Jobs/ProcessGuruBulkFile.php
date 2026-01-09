<?php

namespace App\Jobs;

use App\Models\TambahGuruBulkFile;
use App\Imports\GuruImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessGuruBulkFile implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;
    public int $backoff = 60;
    public bool $failOnTimeout = true;

    public function __construct(
        private TambahGuruBulkFile $bulkFile
    ) {}

    public function handle(): void
    {
        try {
            $filePath = Storage::disk('local')->path($this->bulkFile->file_path);

            if (!file_exists($filePath)) {
                $errorDetails = [
                    [
                        'row' => 0,
                        'nik' => '-',
                        'nama' => '-',
                        'error' => 'File tidak ditemukan di penyimpanan'
                    ]
                ];
                
                $this->bulkFile->update([
                    'is_finished' => false,
                    'error_message' => 'File tidak ditemukan di penyimpanan',
                    'error_details' => $errorDetails // Save as array
                ]);
                return;
            }

            // Create import instance
            $import = new GuruImport($this->bulkFile->id_sekolah);
            
            // Import the file
            Excel::import($import, $filePath);
            
            // Get results
            $errors = $import->getErrors();
            $successCount = $import->getSuccessCount();
            
            // Update bulk file record
            $this->bulkFile->update([
                'is_finished' => $errors->isEmpty(),
                'processed_rows' => $successCount,
                'error_rows' => $errors->count(),
                'error_details' => $errors->isEmpty() ? null : $errors->toArray(),
                'error_message' => $errors->isEmpty() ? null : "{$errors->count()} baris mengalami error saat import"
            ]);

            Log::info('Bulk guru processed via Laravel Excel', [
                'file_id' => $this->bulkFile->id,
                'sekolah_id' => $this->bulkFile->id_sekolah,
                'success_count' => $successCount,
                'error_count' => $errors->count()
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error processing guru bulk file', [
                'file_id' => $this->bulkFile->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorDetails = [
                [
                    'row' => 0,
                    'nik' => '-',
                    'nama' => '-',
                    'error' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()
                ]
            ];
            
            // Update bulk file record with error
            $this->bulkFile->update([
                'is_finished' => false,
                'error_message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage(),
                'error_details' => $errorDetails // Save as array
            ]);
        }
    }
}