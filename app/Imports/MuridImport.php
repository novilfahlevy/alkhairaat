<?php

namespace App\Imports;

use App\Imports\Concerns\AlamatBulkProcessor;
use App\Imports\Concerns\MuridBulkProcessor;
use App\Imports\Concerns\SekolahMuridBulkProcessor;
use App\Models\Murid;
use App\Models\Scopes\MuridNauanganScope;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MuridImport implements ToCollection, WithStartRow, WithChunkReading
{
    private ?array $headers = null;
    private ?array $columnMap = null;
    
    private MuridBulkProcessor $muridProcessor;
    private SekolahMuridBulkProcessor $sekolahMuridProcessor;
    private AlamatBulkProcessor $alamatProcessor;

    public function __construct(private int $idSekolah) 
    {
        $this->muridProcessor = new MuridBulkProcessor();
        $this->sekolahMuridProcessor = new SekolahMuridBulkProcessor();
        $this->alamatProcessor = new AlamatBulkProcessor();
    }

    /**
     * Start from row 2 (header in Excel)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Main collection handler - orchestrates the import process
     */
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::channel('murid_bulk_import')->warning('Collection is empty');
            return;
        }

        Log::channel('murid_bulk_import')->info('=== CHUNK START ===', [
            'total_rows' => $rows->count(), 
            'school_id' => $this->idSekolah
        ]);

        // Step 1: Parse headers from first row
        $this->parseHeaders($rows->first());
        
        // Step 2: Validate and transform rows
        $validatedData = $this->validateRows($rows);

        if ($validatedData->isEmpty()) {
            Log::channel('murid_bulk_import')->warning('No valid rows after validation');
            return;
        }

        Log::channel('murid_bulk_import')->info('Rows validated', [
            'valid_count' => $validatedData->count()
        ]);

        // Step 3: Get existing murid untuk comparison
        $existingMurids = $this->getExistingMurids($validatedData);

        // Step 4: Process dalam transaction
        DB::transaction(function () use ($validatedData, $existingMurids) {
            // 4.1: Process Murid (insert new, update existing)
            $allMurids = $this->muridProcessor->process($validatedData, $existingMurids);
            
            // 4.2: Process Sekolah Murid (insert new, update existing)
            $sekolahMuridStats = $this->sekolahMuridProcessor->process(
                $validatedData, 
                $allMurids, 
                $this->idSekolah
            );
            
            // 4.3: Process Alamat (insert new, update existing)
            $alamatStats = $this->alamatProcessor->processForMurid(
                $validatedData,
                $allMurids,
                $existingMurids
            );
            
            Log::channel('murid_bulk_import')->info('=== CHUNK COMPLETED ===', [
                'murid_processed' => $allMurids->count(),
                'sekolah_murid_inserted' => $sekolahMuridStats['inserted'],
                'sekolah_murid_updated' => $sekolahMuridStats['updated'],
                'alamat_inserted' => $alamatStats['inserted'],
                'alamat_updated' => $alamatStats['updated']
            ]);
        });
    }

    /**
     * Get existing murid from database
     */
    private function getExistingMurids(Collection $validatedData): Collection
    {
        $nisnList = $validatedData->pluck('nisn')->filter()->unique()->toArray();

        return Murid::withoutGlobalScope(MuridNauanganScope::class)
            ->whereIn('nisn', $nisnList)
            ->get();
    }

    /**
     * Validate and transform rows
     */
    private function validateRows(Collection $rows): Collection
    {
        return $rows->skip(1) // Skip header row
            ->map(function ($row) {
                $data = $row->toArray();
                
                Log::channel('murid_bulk_import')->debug('Raw row data received', [
                    'row_type' => get_class($row),
                    'data_type' => is_array($data) ? 'array' : get_class($data),
                    'data_count' => count($data),
                    'data_sample' => array_slice($data, 0, 10),
                ]);

                // Get values using column mapping
                $nisn = $this->getValueByKey($data, 'nisn');
                $nama = $this->getValueByKey($data, 'nama');
                $jenisKelamin = $this->getValueByKey($data, 'jenis_kelamin');
                $tahunMasuk = $this->getValueByKey($data, 'tahun_masuk');

                // Validate mandatory fields
                if (empty($nisn) || empty($nama) || empty($jenisKelamin) || empty($tahunMasuk)) {
                    Log::channel('murid_bulk_import')->warning('Row validation failed - missing required fields', [
                        'nisn' => $nisn,
                        'nama' => $nama,
                        'jenis_kelamin' => $jenisKelamin,
                        'tahun_masuk' => $tahunMasuk,
                    ]);
                    return null;
                }

                Log::channel('murid_bulk_import')->debug('Row validation passed', [
                    'nisn' => $nisn,
                    'nama' => $nama,
                ]);

                // Extract alamat data
                $alamatAsli = $this->extractAlamatBySection($data, 'asli');
                $alamatDomisili = $this->extractAlamatBySection($data, 'domisili');
                $alamatAyah = $this->extractAlamatBySection($data, 'ayah');
                $alamatIbu = $this->extractAlamatBySection($data, 'ibu');

                return [
                    'nisn' => trim((string)$nisn),
                    'nama' => trim($nama),
                    'jenis_kelamin' => strtoupper($jenisKelamin ?? 'L') === 'P' ? 'P' : 'L',
                    'tahun_masuk' => (int)($tahunMasuk ?? date('Y')),
                    'nik' => $this->getValueByKey($data, 'nik'),
                    'tempat_lahir' => $this->getValueByKey($data, 'tempat_lahir'),
                    'tanggal_lahir' => $this->transformDate($this->getValueByKey($data, 'tanggal_lahir')),
                    'kelas' => $this->getValueByKey($data, 'kelas'),
                    'nama_ayah' => $this->getValueByKey($data, 'nama_ayah'),
                    'nomor_hp_ayah' => $this->getValueByKey($data, 'nomor_hp_ayah'),
                    'nama_ibu' => $this->getValueByKey($data, 'nama_ibu'),
                    'nomor_hp_ibu' => $this->getValueByKey($data, 'nomor_hp_ibu'),
                    'kontak_wa_hp' => $this->getValueByKey($data, 'kontak_wa_hp'),
                    'kontak_email' => $this->getValueByKey($data, 'kontak_email'),
                    'tahun_keluar' => $this->getValueByKey($data, 'tahun_keluar'),
                    'tahun_mutasi_masuk' => $this->getValueByKey($data, 'tahun_mutasi_masuk'),
                    'alasan_mutasi_masuk' => $this->getValueByKey($data, 'alasan_mutasi_masuk'),
                    'tahun_mutasi_keluar' => $this->getValueByKey($data, 'tahun_mutasi_keluar'),
                    'alasan_mutasi_keluar' => $this->getValueByKey($data, 'alasan_mutasi_keluar'),
                    'alamat_asli' => $alamatAsli,
                    'alamat_domisili' => $alamatDomisili,
                    'alamat_ayah' => $alamatAyah,
                    'alamat_ibu' => $alamatIbu,
                ];
            })->filter();
    }

    /**
     * Parse headers for dynamic column mapping
     */
    private function parseHeaders($headerRow): void
    {
        if ($headerRow instanceof Collection) {
            $headerRow = $headerRow->toArray();
        }

        // Normalize headers: lowercase, trim, dan ganti spasi dengan underscore
        $this->headers = array_map(
            fn($h) => str_replace(' ', '_', strtolower(trim((string)$h))), 
            $headerRow
        );
        
        Log::channel('murid_bulk_import')->debug('Raw headers parsed', [
            'headers' => $this->headers,
        ]);
        
        $this->columnMap = [];

        // Map kolom data pribadi
        $this->mapColumns([
            'nisn', 'nama', 'nik', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'kontak_wa_hp', 'kontak_email'
        ]);

        // Map kolom data sekolah & pendidikan
        $this->mapColumns([
            'kelas', 'tahun_masuk', 'tahun_keluar', 'tahun_mutasi_masuk', 
            'alasan_mutasi_masuk', 'tahun_mutasi_keluar', 'alasan_mutasi_keluar'
        ]);

        // Map kolom orang tua
        $this->mapColumns(['nama_ayah', 'nomor_hp_ayah', 'nama_ibu', 'nomor_hp_ibu']);
        
        Log::channel('murid_bulk_import')->debug('Column mapping result', [
            'columnMap' => $this->columnMap,
        ]);
    }

    /**
     * Map kolom dari header row
     */
    private function mapColumns(array $columnNames): void
    {
        foreach ($columnNames as $colName) {
            $key = array_search(strtolower($colName), $this->headers);
            if ($key !== false) {
                $this->columnMap[$colName] = $key;
            }
        }
    }

    /**
     * Ambil nilai berdasarkan nama kolom
     */
    private function getValueByKey(array $row, string $key): mixed
    {
        Log::channel('murid_bulk_import')->debug('getValueByKey called', [
            'key' => $key,
            'columnMap_exists' => isset($this->columnMap[$key]),
            'columnMap' => $this->columnMap,
            'row_count' => count($row),
        ]);
        
        if (!$this->columnMap || !isset($this->columnMap[$key])) {
            Log::channel('murid_bulk_import')->debug('Key not found in columnMap, using direct array access', [
                'key' => $key,
            ]);
            return $row[$key] ?? null;
        }

        $columnIndex = $this->columnMap[$key];
        $value = $row[$columnIndex] ?? null;
        
        Log::channel('murid_bulk_import')->debug('Value retrieved from columnIndex', [
            'key' => $key,
            'columnIndex' => $columnIndex,
            'value' => $value,
        ]);
        
        return $value;
    }

    /**
     * Extract alamat data berdasarkan section
     */
    private function extractAlamatBySection(array $row, string $section): array
    {
        // Tentukan starting column berdasarkan section
        $startCol = match($section) {
            'asli' => 22,      // W
            'domisili' => 33,  // AH
            'ayah' => 44,      // AS
            'ibu' => 55,       // BD
            default => 0,
        };

        // Kolom alamat: provinsi(0), kabupaten(1), kecamatan(2), kelurahan(3), rt(4), rw(5), kode_pos(6), alamat_lengkap(7), latitude(8), longitude(9)
        return [
            'provinsi' => $row[$startCol] ?? null,
            'kabupaten' => $row[$startCol + 1] ?? null,
            'kecamatan' => $row[$startCol + 2] ?? null,
            'kelurahan' => $row[$startCol + 3] ?? null,
            'rt' => $row[$startCol + 4] ?? null,
            'rw' => $row[$startCol + 5] ?? null,
            'kode_pos' => $row[$startCol + 6] ?? null,
            'alamat_lengkap' => $row[$startCol + 7] ?? null,
            'koordinat_x' => isset($row[$startCol + 8]) ? (float)$row[$startCol + 8] : null,
            'koordinat_y' => isset($row[$startCol + 9]) ? (float)$row[$startCol + 9] : null,
        ];
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}