<?php

namespace App\Jobs;

use App\Imports\MuridImport;
use App\Models\TambahMuridBulkFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessMuridBulkFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = [30, 60, 120];

    /**
     * Create a new job instance.
     */
    public function __construct(private TambahMuridBulkFile $bulkFile)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $filePath = Storage::disk('local')->path($this->bulkFile->file_path);

            // Check if file exists
            if (!file_exists($filePath)) {
                $errorDetails = [
                    [
                        'row' => 0,
                        'nisn' => '-',
                        'nama' => '-',
                        'error' => 'File tidak ditemukan di penyimpanan'
                    ]
                ];

                $this->bulkFile->update([
                    'is_finished' => false,
                    'error_message' => 'File tidak ditemukan di penyimpanan',
                    'error_details' => $errorDetails // Save as array, Laravel will automatically JSON encode
                ]);
                return;
            }

            // Create import instance
            $import = new MuridImport($this->bulkFile->id_sekolah);

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
                'error_details' => $errors->isEmpty() ? null : $errors->toArray(), // Save as array
                'error_message' => $errors->isEmpty() ? null : "{$errors->count()} baris mengalami error saat import"
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error processing murid bulk file', [
                'file_id' => $this->bulkFile->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorDetails = [
                [
                    'row' => 0,
                    'nisn' => '-',
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
