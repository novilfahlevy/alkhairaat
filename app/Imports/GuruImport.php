<?php

namespace App\Imports;

use App\Imports\Concerns\AlamatBulkProcessor;
use App\Imports\Concerns\GuruBulkProcessor;
use App\Imports\Concerns\JabatanGuruBulkProcessor;
use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Scopes\GuruSekolahNauanganScope;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToCollection, WithStartRow, WithChunkReading
{
    private ?array $headers = null;
    private ?array $columnMap = null;
    private Collection $errors;
    private Collection $successRows;
    
    private GuruBulkProcessor $guruProcessor;
    private JabatanGuruBulkProcessor $jabatanProcessor;
    private AlamatBulkProcessor $alamatProcessor;

    public function __construct(private int $idSekolah) 
    {
        $this->guruProcessor = new GuruBulkProcessor();
        $this->jabatanProcessor = new JabatanGuruBulkProcessor();
        $this->alamatProcessor = new AlamatBulkProcessor();
        $this->errors = collect();
        $this->successRows = collect();
    }

    /**
     * Start from row 2 (sub-header in Excel)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Get errors collection
     */
    public function getErrors(): Collection
    {
        return $this->errors;
    }

    /**
     * Get success rows count
     */
    public function getSuccessCount(): int
    {
        return $this->successRows->count();
    }

    /**
     * Main collection handler - orchestrates the import process
     */
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::channel('guru_bulk_import')->warning('Collection is empty');
            return;
        }

        Log::channel('guru_bulk_import')->info('=== CHUNK START ===', [
            'total_rows' => $rows->count(), 
            'school_id' => $this->idSekolah
        ]);

        // Step 1: Parse headers from first row
        $this->parseHeaders($rows->first());
        
        // Step 2: Validate and transform rows
        $validatedData = $this->validateRows($rows);

        if ($validatedData->isEmpty()) {
            Log::channel('guru_bulk_import')->warning('No valid rows after validation');
            return;
        }

        Log::channel('guru_bulk_import')->info('Rows validated', [
            'valid_count' => $validatedData->count()
        ]);

        // Step 3: Get existing guru untuk comparison
        $existingGurus = $this->getExistingGurus($validatedData);

        // Step 4: Process dalam transaction
        DB::transaction(function () use ($validatedData, $existingGurus) {
            // 4.1: Process Guru (insert new, get all guru)
            $allGurus = $this->guruProcessor->process($validatedData);
            
            // 4.2: Process Jabatan Guru (always insert new)
            $jabatanCount = $this->jabatanProcessor->process(
                $validatedData, 
                $allGurus, 
                $this->idSekolah
            );
            
            // 4.3: Process Alamat (insert new, update existing)
            $alamatStats = $this->alamatProcessor->process(
                $validatedData,
                $allGurus,
                $existingGurus
            );
            
            // Add success rows
            $this->successRows = $validatedData;
            
            Log::channel('guru_bulk_import')->info('=== CHUNK COMPLETED ===', [
                'guru_processed' => $allGurus->count(),
                'jabatan_inserted' => $jabatanCount,
                'alamat_inserted' => $alamatStats['inserted'],
                'alamat_updated' => $alamatStats['updated']
            ]);
        });
    }

    /**
     * Get existing guru from database
     */
    private function getExistingGurus(Collection $validatedData): Collection
    {
        $nikList = $validatedData->pluck('nik')->filter()->unique()->toArray();
        $npkList = $validatedData->pluck('npk')->filter()->unique()->toArray();
        $nuptkList = $validatedData->pluck('nuptk')->filter()->unique()->toArray();

        return Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
            ->where(function($query) use ($nikList, $npkList, $nuptkList) {
                $query->whereIn('nik', $nikList);
                
                if (!empty($npkList)) {
                    $query->orWhereIn('npk', $npkList);
                }
                
                if (!empty($nuptkList)) {
                    $query->orWhereIn('nuptk', $nuptkList);
                }
            })
            ->get();
    }

    /**
     * Validate and transform rows
     */
    private function validateRows(Collection $rows): Collection
    {
        return $rows->skip(1) // Skip header row
            ->map(function ($row, $index) {
                $data = $row->toArray();
                $rowNumber = $index + 2; // +2 because we skip header and start from row 2

                // Get values using column mapping
                $nama = $this->getValueByKey($data, 'nama');
                $nik = $this->getValueByKey($data, 'nik');
                $jenisKelamin = $this->getValueByKey($data, 'jenis_kelamin');
                $status = $this->getValueByKey($data, 'status');

                // Validate mandatory fields
                if (empty($nik) || empty($nama) || empty($jenisKelamin) || empty($status)) {
                    $errorFields = [];
                    if (empty($nik)) $errorFields[] = 'NIK';
                    if (empty($nama)) $errorFields[] = 'Nama';
                    if (empty($jenisKelamin)) $errorFields[] = 'Jenis Kelamin';
                    if (empty($status)) $errorFields[] = 'Status';
                    
                    $this->errors->push([
                        'row' => $rowNumber,
                        'nik' => $nik,
                        'nama' => $nama,
                        'error' => 'Kolom wajib tidak lengkap: ' . implode(', ', $errorFields)
                    ]);
                    
                    Log::channel('guru_bulk_import')->warning('Row validation failed - missing required fields', [
                        'row' => $rowNumber,
                        'nik' => $nik,
                        'nama' => $nama,
                        'jenis_kelamin' => $jenisKelamin,
                        'status' => $status,
                    ]);
                    return null;
                }

                // Validate NIK format (should be numeric and 16 digits)
                if (!is_numeric($nik) || strlen($nik) !== 16) {
                    $this->errors->push([
                        'row' => $rowNumber,
                        'nik' => $nik,
                        'nama' => $nama,
                        'error' => 'NIK harus berupa angka dan 16 digit'
                    ]);
                    return null;
                }

                // Validate jenis kelamin
                if (strtoupper($jenisKelamin) !== 'L' && strtoupper($jenisKelamin) !== 'P') {
                    $this->errors->push([
                        'row' => $rowNumber,
                        'nik' => $nik,
                        'nama' => $nama,
                        'error' => 'Jenis kelamin harus "L" atau "P"'
                    ]);
                    return null;
                }

                // Validate status
                if (!in_array($status, ['aktif', 'tidak'])) {
                    $this->errors->push([
                        'row' => $rowNumber,
                        'nik' => $nik,
                        'nama' => $nama,
                        'error' => 'Status harus "aktif" atau "tidak"'
                    ]);
                    return null;
                }

                // Normalize identifiers
                $nik = $this->normalizeNumericString($nik);
                $npk = $this->normalizeNumericString($this->getValueByKey($data, 'npk'));
                $nuptk = $this->normalizeNumericString($this->getValueByKey($data, 'nuptk'));

                return [
                    'nama' => trim($nama),
                    'nik' => trim($nik),
                    'jenis_kelamin' => strtoupper(trim($jenisKelamin)) === 'P' ? 'P' : 'L',
                    'status' => $status,
                    'status_kepegawaian' => $this->getValueByKey($data, 'status_kepegawaian'),
                    'status_perkawinan' => $this->getValueByKey($data, 'status_perkawinan'),
                    'gelar_depan' => $this->getValueByKey($data, 'gelar_depan'),
                    'gelar_belakang' => $this->getValueByKey($data, 'gelar_belakang'),
                    'tempat_lahir' => $this->getValueByKey($data, 'tempat_lahir'),
                    'tanggal_lahir' => $this->transformDate($this->getValueByKey($data, 'tanggal_lahir')),
                    'npk' => $npk,
                    'nuptk' => $nuptk,
                    'kontak_wa_hp' => $this->getValueByKey($data, 'kontak_wa_hp'),
                    'kontak_email' => $this->getValueByKey($data, 'kontak_email'),
                    'jenis_jabatan' => $this->getValueByKey($data, 'jenis_jabatan') ?? JabatanGuru::JENIS_JABATAN_GURU,
                    'keterangan_jabatan' => $this->getValueByKey($data, 'keterangan_jabatan'),
                    'nomor_rekening' => $this->getValueByKey($data, 'nomor_rekening'),
                    'rekening_atas_nama' => $this->getValueByKey($data, 'rekening_atas_nama'),
                    'bank_rekening' => $this->getValueByKey($data, 'bank_rekening'),
                    'alamat_asli' => $this->extractAlamatBySection($data, 'asli'),
                    'alamat_domisili' => $this->extractAlamatBySection($data, 'domisili'),
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

        // Normalize headers
        $this->headers = array_map(function($h) {
            $normalized = strtolower(trim((string)$h));
            $normalized = str_replace(['/', ' '], '_', $normalized);
            return $normalized;
        }, $headerRow);
        
        $this->columnMap = [];

        // Map columns
        $this->mapColumns([
            'nama', 'nik', 'jenis_kelamin', 'status', 'status_kepegawaian',
            'status_perkawinan', 'gelar_depan', 'gelar_belakang', 
            'tempat_lahir', 'tanggal_lahir', 'npk', 'nuptk',
            'kontak_wa_hp', 'kontak_wa/hp', 'email',
            'jenis_jabatan', 'keterangan_jabatan',
            'nomor_rekening', 'rekening_atas_nama', 'bank_rekening'
        ]);
    }

    private function mapColumns(array $columnNames): void
    {
        foreach ($columnNames as $colName) {
            $normalized = strtolower($colName);
            $key = array_search($normalized, $this->headers);
            if ($key !== false) {
                $cleanKey = str_replace(['/', ' '], '_', $colName);
                $this->columnMap[$cleanKey] = $key;
            }
        }
    }

    private function getValueByKey(array $row, string $key): mixed
    {
        $normalizedKey = str_replace(['/', ' '], '_', strtolower($key));
        
        if (!$this->columnMap || !isset($this->columnMap[$normalizedKey])) {
            return $row[$key] ?? $row[$normalizedKey] ?? null;
        }

        $columnIndex = $this->columnMap[$normalizedKey];
        return $row[$columnIndex] ?? null;
    }

    /**
     * Normalize numeric string (handle scientific notation)
     */
    private function normalizeNumericString($value): ?string
    {
        if (empty($value)) return null;
        
        $value = (string)$value;
        
        if (is_numeric($value) && (strpos($value, 'E') !== false || strpos($value, 'e') !== false)) {
            return number_format((float)$value, 0, '', '');
        }
        
        return $value;
    }

    /**
     * Extract alamat by section
     */
    private function extractAlamatBySection(array $row, string $section): array
    {
        $startCol = match($section) {
            'asli' => 22,      // Column W
            'domisili' => 31,  // Column AF
            default => 0,
        };

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
        } catch (Exception $e) {
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}