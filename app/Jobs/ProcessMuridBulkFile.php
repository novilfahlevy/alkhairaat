<?php

namespace App\Jobs;

use App\Models\TambahMuridBulkFile;
use App\Models\Murid;
use App\Models\SekolahMurid;
use App\Models\Alamat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProcessMuridBulkFile implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 3600; // 1 hour

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Indicate if the job should be marked as failed on any exception.
     */
    public bool $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private TambahMuridBulkFile $bulkFile
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get file path
            $filePath = Storage::disk('local')->path($this->bulkFile->file_path);

            if (!file_exists($filePath)) {
                throw new \Exception('File tidak ditemukan: ' . $this->bulkFile->file_path);
            }

            // Parse file dengan maatwebsite/excel
            $rows = Excel::toArray(null, $filePath)[0] ?? [];

            if (empty($rows)) {
                throw new \Exception('File kosong atau format tidak valid');
            }

            // Skip header row
            $dataRows = array_slice($rows, 1);

            if (empty($dataRows)) {
                throw new \Exception('Tidak ada data murid di file');
            }

            // Process dengan batching untuk efisiensi
            $this->processBatch($dataRows);

            // Mark sebagai berhasil
            $this->bulkFile->update(['is_finished' => true]);

            Log::info('Murid bulk file processed successfully', [
                'id' => $this->bulkFile->id,
                'sekolah_id' => $this->bulkFile->id_sekolah,
                'rows_processed' => count($dataRows),
            ]);
        } catch (\Exception $e) {
            // Mark sebagai gagal
            $this->bulkFile->update(['is_finished' => false]);

            Log::error('Failed to process murid bulk file', [
                'id' => $this->bulkFile->id,
                'sekolah_id' => $this->bulkFile->id_sekolah,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Process data dalam batch untuk efisiensi
     */
    private function processBatch(array $dataRows): void
    {
        $batchSize = 100;
        $totalBatches = ceil(count($dataRows) / $batchSize);

        for ($batch = 0; $batch < $totalBatches; $batch++) {
            $startIndex = $batch * $batchSize;
            $batchData = array_slice($dataRows, $startIndex, $batchSize);

            DB::transaction(function () use ($batchData) {
                foreach ($batchData as $row) {
                    $this->processRow($row);
                }
            });

            Log::debug('Processed batch', [
                'batch' => $batch + 1,
                'total_batches' => $totalBatches,
                'bulkFile_id' => $this->bulkFile->id,
            ]);
        }
    }

    /**
     * Process single row dari file
     */
    private function processRow(array $row): void
    {
        // Map kolom dari file
        $data = [
            'nama' => $row[0] ?? null,
            'nisn' => $row[1] ?? null,
            'nik' => $row[2] ?? null,
            'tempat_lahir' => $row[3] ?? null,
            'tanggal_lahir' => $row[4] ?? null,
            'jenis_kelamin' => $row[5] ?? null,
            'tahun_masuk' => $row[6] ?? null,
            'kelas' => $row[7] ?? null,
            'status_kelulusan' => $row[8] ?? null,
            'kontak_wa_hp' => $row[9] ?? null,
            'kontak_email' => $row[10] ?? null,
            'nama_ayah' => $row[11] ?? null,
            'nomor_hp_ayah' => $row[12] ?? null,
            'nama_ibu' => $row[13] ?? null,
            'nomor_hp_ibu' => $row[14] ?? null,
            'provinsi' => $row[15] ?? null,
            'kabupaten' => $row[16] ?? null,
            'kecamatan' => $row[17] ?? null,
            'kelurahan' => $row[18] ?? null,
            'rt' => $row[19] ?? null,
            'rw' => $row[20] ?? null,
            'kode_pos' => $row[21] ?? null,
            'alamat_lengkap' => $row[22] ?? null,
        ];

        // Validasi data minimal
        if (empty($data['nama']) || empty($data['nisn'])) {
            Log::warning('Skipping row: missing required fields', ['row' => $data]);
            return;
        }

        // Sanitize data
        $data['nisn'] = (string) $data['nisn'];
        $data['nik'] = $data['nik'] ? (string) $data['nik'] : null;
        $data['jenis_kelamin'] = in_array($data['jenis_kelamin'], ['L', 'P']) ? $data['jenis_kelamin'] : 'L';
        $data['tahun_masuk'] = $data['tahun_masuk'] ? (int) $data['tahun_masuk'] : date('Y');
        $data['status_kelulusan'] = in_array($data['status_kelulusan'], ['ya', 'tidak']) ? $data['status_kelulusan'] : null;

        // Parse tanggal lahir
        if (!empty($data['tanggal_lahir'])) {
            try {
                $data['tanggal_lahir'] = $this->parseDate($data['tanggal_lahir']);
            } catch (\Exception $e) {
                Log::warning('Invalid date format', ['date' => $data['tanggal_lahir']]);
                $data['tanggal_lahir'] = null;
            }
        }

        // Create or find murid
        $murid = Murid::firstOrCreate(
            ['nisn' => $data['nisn']],
            [
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
                'tanggal_update_data' => now(),
            ]
        );

        // Create or update sekolah_murid record
        SekolahMurid::updateOrCreate(
            [
                'id_murid' => $murid->id,
                'id_sekolah' => $this->bulkFile->id_sekolah,
            ],
            [
                'tahun_masuk' => $data['tahun_masuk'],
                'kelas' => $data['kelas'],
                'status_kelulusan' => $data['status_kelulusan'],
            ]
        );

        // Create or update alamat record jika ada data alamat
        if (!empty($data['alamat_lengkap']) || !empty($data['provinsi'])) {
            Alamat::updateOrCreate(
                [
                    'id_murid' => $murid->id,
                    'jenis' => 'asli', // Default sebagai alamat asli
                ],
                [
                    'provinsi' => $data['provinsi'],
                    'kabupaten' => $data['kabupaten'],
                    'kecamatan' => $data['kecamatan'],
                    'kelurahan' => $data['kelurahan'],
                    'rt' => $data['rt'],
                    'rw' => $data['rw'],
                    'kode_pos' => $data['kode_pos'],
                    'alamat_lengkap' => $data['alamat_lengkap'],
                ]
            );
        }
    }

    /**
     * Parse tanggal dari berbagai format
     */
    private function parseDate($dateString): ?string
    {
        $dateString = trim($dateString);

        if (empty($dateString)) {
            return null;
        }

        // Coba format DD/MM/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return "$year-$month-$day";
        }

        // Coba format YYYY-MM-DD
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateString, $matches)) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return "$year-$month-$day";
        }

        // Coba format tanggal Excel (numeric)
        if (is_numeric($dateString)) {
            $excelDate = (int) $dateString;
            // Excel date dimulai dari 1 Januari 1900
            $date = new \DateTime('1900-01-01');
            $date->modify('+' . ($excelDate - 2) . ' days');
            return $date->format('Y-m-d');
        }

        throw new \Exception('Format tanggal tidak valid: ' . $dateString);
    }
}
