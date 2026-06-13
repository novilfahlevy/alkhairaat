<?php

namespace App\Console\Commands;

use App\Jobs\ProcessGuruBulkFile;
use App\Jobs\ProcessMuridBulkFile;
use App\Models\TambahGuruBulkFile;
use App\Models\TambahMuridBulkFile;
use Illuminate\Console\Command;

class ProcessPendingBulkImports extends Command
{
    protected $signature = 'bulk-import:process-pending
                            {--limit=10 : Maksimal file per tipe yang diproses per eksekusi}';

    protected $description = 'Proses file Excel murid dan guru yang masih menunggu impor';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $muridCount = $this->dispatchPendingMuridFiles($limit);
        $guruCount = $this->dispatchPendingGuruFiles($limit);

        if ($muridCount === 0 && $guruCount === 0) {
            $this->comment('Tidak ada file impor yang menunggu diproses.');

            return self::SUCCESS;
        }

        $this->info("Murid: {$muridCount} file dijadwalkan. Guru: {$guruCount} file dijadwalkan.");

        return self::SUCCESS;
    }

    private function dispatchPendingMuridFiles(int $limit): int
    {
        $files = TambahMuridBulkFile::query()
            ->whereNull('is_finished')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        foreach ($files as $bulkFile) {
            ProcessMuridBulkFile::dispatch($bulkFile);

            $this->line(sprintf(
                'Murid bulk file #%d (%s) dijadwalkan.',
                $bulkFile->id,
                $bulkFile->file_original_name ?? basename($bulkFile->file_path)
            ));
        }

        return $files->count();
    }

    private function dispatchPendingGuruFiles(int $limit): int
    {
        $files = TambahGuruBulkFile::query()
            ->whereNull('is_finished')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        foreach ($files as $bulkFile) {
            ProcessGuruBulkFile::dispatch($bulkFile);

            $this->line(sprintf(
                'Guru bulk file #%d (%s) dijadwalkan.',
                $bulkFile->id,
                $bulkFile->file_original_name ?? basename($bulkFile->file_path)
            ));
        }

        return $files->count();
    }
}
