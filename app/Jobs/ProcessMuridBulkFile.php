<?php

namespace App\Jobs;

use App\Models\TambahMuridBulkFile;
use App\Imports\MuridImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessMuridBulkFile implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;
    public int $backoff = 60;
    public bool $failOnTimeout = true;

    public function __construct(
        private TambahMuridBulkFile $bulkFile
    ) {}

    public function handle(): void
    {
        try {
            $filePath = Storage::disk('local')->path($this->bulkFile->file_path);

            if (!file_exists($filePath)) {
                throw new \Exception('File tidak ditemukan: ' . $this->bulkFile->file_path);
            }

            // Memulai proses import menggunakan class MuridImport
            Excel::import(new MuridImport($this->bulkFile->id_sekolah), Storage::disk('local')->path($this->bulkFile->file_path));

            // Mark job sebagai selesai
            $this->bulkFile->update(['is_finished' => true]);

            Log::info('Bulk murid processed via Laravel Excel', [
                'file_id' => $this->bulkFile->id,
                'sekolah_id' => $this->bulkFile->id_sekolah
            ]);

        } catch (\Exception $e) {
            $this->bulkFile->update(['is_finished' => false]);

            Log::error('Bulk import failed', [
                'id' => $this->bulkFile->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}