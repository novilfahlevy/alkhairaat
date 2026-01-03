<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Alamat;
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

    public function __construct(private int $idSekolah) {}

    /**
     * PENTING: Mulai membaca dari baris ke-2 (baris sub-header/nama kolom di Excel)
     * Struktur Excel:
     * Baris 1: Header kategori (Data Pribadi, Data Jabatan, dll) - akan di-skip
     * Baris 2: Sub-header/nama kolom (Nama, NIK, dll) - dibaca sebagai header
     * Baris 3+: Data guru - dibaca sebagai data
     */
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::channel('guru_bulk_import')->warning('Collection is empty');
            return;
        }

        Log::channel('guru_bulk_import')->info('Chunk received', [
            'total_rows' => $rows->count(), 
            'school_id' => $this->idSekolah
        ]);

        // Parse headers dari baris pertama collection (baris ke-2 di Excel = sub-header)
        $this->parseHeaders($rows->first());
        
        Log::channel('guru_bulk_import')->info('Headers parsed', [
            'headers_count' => count($this->headers)
        ]);

        // Validasi dan siapkan data
        $validRows = $this->validateRows($rows);

        if ($validRows->isEmpty()) {
            Log::channel('guru_bulk_import')->warning('No valid rows after validation');
            return;
        }

        Log::channel('guru_bulk_import')->info('Rows validated', [
            'valid_count' => $validRows->count()
        ]);

        // Ambil semua NIK, NPK, dan NUPTK dari chunk ini
        $nikList = $validRows->pluck('nik')->filter()->unique()->toArray();
        $npkList = $validRows->pluck('npk')->filter()->unique()->toArray();
        $nuptkList = $validRows->pluck('nuptk')->filter()->unique()->toArray();

        Log::channel('guru_bulk_import')->info('Processing identifiers', [
            'nik_count' => count($nikList),
            'npk_count' => count($npkList),
            'nuptk_count' => count($nuptkList)
        ]);

        // Bulk query - cari guru yang sudah ada berdasarkan NIK, NPK, atau NUPTK
        $existingGurus = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
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

        Log::channel('guru_bulk_import')->info('Existing gurus found', [
            'existing_count' => $existingGurus->count()
        ]);

        // Klasifikasi guru baru vs existing
        // Guru dianggap existing jika NIK, NPK, atau NUPTK cocok dengan salah satu guru yang ada
        $newGuruRows = $validRows->reject(function ($row) use ($existingGurus) {
            return $this->isGuruExists($row, $existingGurus);
        });

        $existingGuruRows = $validRows->filter(function ($row) use ($existingGurus) {
            return $this->isGuruExists($row, $existingGurus);
        });

        Log::channel('guru_bulk_import')->info('Guru classification', [
            'new_count' => $newGuruRows->count(),
            'existing_count' => $existingGuruRows->count(),
        ]);

        // Proses dalam transaction
        DB::transaction(function () use ($newGuruRows, $existingGuruRows, $existingGurus) {
            $jabatanGuruData = [];
            $alamatData = [];
            $newGuruData = [];
            $newJabatanCount = 0;
            $updatedAlamatCount = 0;

            // Proses guru baru (INSERT)
            if (!$newGuruRows->isEmpty()) {
                Log::channel('guru_bulk_import')->info('Processing new gurus', [
                    'count' => $newGuruRows->count()
                ]);
                
                foreach ($newGuruRows as $row) {
                    $newGuruData[] = [
                        'status' => $row['status'] ?? Guru::STATUS_AKTIF,
                        'nama_gelar_depan' => $row['gelar_depan'],
                        'nama' => $row['nama'],
                        'nama_gelar_belakang' => $row['gelar_belakang'],
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => $row['tanggal_lahir'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'status_perkawinan' => $row['status_perkawinan'],
                        'nik' => $row['nik'],
                        'status_kepegawaian' => $row['status_kepegawaian'],
                        'npk' => $row['npk'],
                        'nuptk' => $row['nuptk'],
                        'kontak_wa_hp' => $row['kontak_wa_hp'],
                        'kontak_email' => $row['kontak_email'],
                        'nomor_rekening' => $row['nomor_rekening'],
                        'rekening_atas_nama' => $row['rekening_atas_nama'],
                        'bank_rekening' => $row['bank_rekening'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert guru baru
                try {
                    DB::table('guru')->insert($newGuruData);
                    Log::channel('guru_bulk_import')->info('Bulk insert successful', [
                        'count' => count($newGuruData)
                    ]);
                } catch (\Exception $e) {
                    Log::channel('guru_bulk_import')->warning('Bulk insert failed, using individual inserts', [
                        'error' => $e->getMessage()
                    ]);

                    foreach ($newGuruData as $data) {
                        try {
                            DB::table('guru')->insert($data);
                        } catch (Exception $e2) {
                            if ($e2 instanceof \Illuminate\Database\QueryException && 
                                isset($e2->errorInfo[1]) && $e2->errorInfo[1] == 1062) {
                                Log::channel('guru_bulk_import')->warning('Duplicate entry ignored', [
                                    'nik' => $data['nik']
                                ]);
                            } else {
                                throw $e2;
                            }
                        }
                    }
                }

                // Refresh untuk dapat ID guru yang baru diinsert
                $nikList = collect($newGuruData)->pluck('nik')->toArray();
                $insertedGurus = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
                    ->whereIn('nik', $nikList)
                    ->get();
                    
                $allGurus = $existingGurus->merge($insertedGurus);
            } else {
                $allGurus = $existingGurus;
            }

            // Proses semua rows (baru dan existing)
            $allGuruRows = $newGuruRows->merge($existingGuruRows);
            
            Log::channel('guru_bulk_import')->info('Processing jabatan and alamat for all rows', [
                'total_rows' => $allGuruRows->count()
            ]);
            
            foreach ($allGuruRows as $row) {
                // Cari guru berdasarkan NIK, NPK, atau NUPTK
                $guru = $this->findGuruByIdentifiers($row, $allGurus);
                
                if (!$guru) {
                    Log::channel('guru_bulk_import')->error('Guru not found', [
                        'nik' => $row['nik'],
                        'npk' => $row['npk'],
                        'nuptk' => $row['nuptk']
                    ]);
                    continue;
                }

                $isExistingGuru = $this->isGuruExists($row, $existingGurus);

                Log::channel('guru_bulk_import')->debug('Processing guru', [
                    'nik' => $row['nik'],
                    'npk' => $row['npk'],
                    'nuptk' => $row['nuptk'],
                    'nama' => $guru->nama,
                    'is_existing' => $isExistingGuru,
                    'action' => $isExistingGuru ? 'Update alamat + add jabatan' : 'Create all'
                ]);

                // SELALU buat record JabatanGuru baru untuk setiap baris
                // Ini memungkinkan guru memiliki multiple jabatan di sekolah yang sama
                $jabatanGuruData[] = [
                    'id_guru' => $guru->id,
                    'id_sekolah' => $this->idSekolah,
                    'jenis_jabatan' => $row['jenis_jabatan'] ?? JabatanGuru::JENIS_JABATAN_GURU,
                    'keterangan_jabatan' => $row['keterangan_jabatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $newJabatanCount++;
                
                // Proses alamat untuk 2 jenis: asli, domisili
                // PENTING: Untuk guru existing, hanya UPDATE alamat (tidak insert baru)
                // Untuk guru baru, INSERT alamat baru
                $alamatSections = [
                    'asli' => $row['alamat_asli'],
                    'domisili' => $row['alamat_domisili'],
                ];

                foreach ($alamatSections as $jenisAlamat => $alamatRowData) {
                    // Skip jika tidak ada data alamat
                    if (!array_filter($alamatRowData, fn($v) => $v !== null && $v !== '')) {
                        Log::channel('guru_bulk_import')->debug('Skipping empty address', [
                            'nik' => $row['nik'],
                            'jenis' => $jenisAlamat
                        ]);
                        continue;
                    }

                    Log::channel('guru_bulk_import')->debug('Processing address', [
                        'nik' => $row['nik'],
                        'jenis' => $jenisAlamat,
                        'is_existing_guru' => $isExistingGuru
                    ]);

                    // Cek apakah alamat sudah ada
                    $existingAlamat = Alamat::where('id_guru', $guru->id)
                        ->where('jenis', $jenisAlamat)
                        ->first();

                    if ($existingAlamat) {
                        // UPDATE alamat yang sudah ada
                        Log::channel('guru_bulk_import')->debug('Updating existing address', [
                            'nik' => $row['nik'],
                            'jenis' => $jenisAlamat,
                            'alamat_id' => $existingAlamat->id
                        ]);
                        
                        $existingAlamat->update([
                            'provinsi' => $alamatRowData['provinsi'],
                            'kabupaten' => $alamatRowData['kabupaten'],
                            'kecamatan' => $alamatRowData['kecamatan'],
                            'kelurahan' => $alamatRowData['kelurahan'],
                            'rt' => $alamatRowData['rt'],
                            'rw' => $alamatRowData['rw'],
                            'kode_pos' => $alamatRowData['kode_pos'],
                            'alamat_lengkap' => $alamatRowData['alamat_lengkap'],
                            'koordinat_x' => $alamatRowData['koordinat_x'],
                            'koordinat_y' => $alamatRowData['koordinat_y'],
                        ]);
                        
                        $updatedAlamatCount++;
                        
                        Log::channel('guru_bulk_import')->debug('Address updated', [
                            'nik' => $row['nik'],
                            'jenis' => $jenisAlamat
                        ]);
                    } else {
                        // INSERT alamat baru
                        Log::channel('guru_bulk_import')->debug('Adding new address', [
                            'nik' => $row['nik'],
                            'jenis' => $jenisAlamat
                        ]);
                        
                        $alamatData[] = [
                            'id_guru' => $guru->id,
                            'jenis' => $jenisAlamat,
                            'provinsi' => $alamatRowData['provinsi'],
                            'kabupaten' => $alamatRowData['kabupaten'],
                            'kecamatan' => $alamatRowData['kecamatan'],
                            'kelurahan' => $alamatRowData['kelurahan'],
                            'rt' => $alamatRowData['rt'],
                            'rw' => $alamatRowData['rw'],
                            'kode_pos' => $alamatRowData['kode_pos'],
                            'alamat_lengkap' => $alamatRowData['alamat_lengkap'],
                            'koordinat_x' => $alamatRowData['koordinat_x'],
                            'koordinat_y' => $alamatRowData['koordinat_y'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Bulk insert jabatan_guru baru
            if (!empty($jabatanGuruData)) {
                Log::channel('guru_bulk_import')->info('Bulk inserting jabatan_guru', [
                    'count' => count($jabatanGuruData)
                ]);
                try {
                    DB::table('jabatan_guru')->insert($jabatanGuruData);
                    Log::channel('guru_bulk_import')->info('Jabatan_guru inserted', [
                        'count' => count($jabatanGuruData)
                    ]);
                } catch (\Exception $e) {
                    Log::channel('guru_bulk_import')->error('Jabatan_guru bulk insert failed', [
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }

            // Bulk insert alamat baru
            if (!empty($alamatData)) {
                Log::channel('guru_bulk_import')->info('Bulk inserting alamat', [
                    'count' => count($alamatData)
                ]);
                try {
                    DB::table('alamat')->insert($alamatData);
                    Log::channel('guru_bulk_import')->info('Alamat inserted', [
                        'count' => count($alamatData)
                    ]);
                } catch (\Exception $e) {
                    Log::channel('guru_bulk_import')->error('Alamat bulk insert failed', [
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }

            Log::channel('guru_bulk_import')->info('Import completed', [
                'new_guru_count' => count($newGuruData),
                'existing_guru_count' => $existingGuruRows->count(),
                'new_jabatan_count' => $newJabatanCount,
                'updated_alamat_count' => $updatedAlamatCount,
                'new_alamat_count' => count($alamatData),
                'total_processed' => $allGuruRows->count()
            ]);
        });
    }

    /**
     * Cek apakah guru sudah ada berdasarkan NIK, NPK, atau NUPTK
     */
    private function isGuruExists(array $row, Collection $existingGurus): bool
    {
        return $existingGurus->contains(function ($guru) use ($row) {
            // Cek NIK (wajib ada)
            if ($guru->nik === $row['nik']) {
                return true;
            }
            
            // Cek NPK (jika ada di row dan guru)
            if (!empty($row['npk']) && !empty($guru->npk) && $guru->npk === $row['npk']) {
                return true;
            }
            
            // Cek NUPTK (jika ada di row dan guru)
            if (!empty($row['nuptk']) && !empty($guru->nuptk) && $guru->nuptk === $row['nuptk']) {
                return true;
            }
            
            return false;
        });
    }

    /**
     * Cari guru berdasarkan NIK, NPK, atau NUPTK (prioritas: NIK > NPK > NUPTK)
     */
    private function findGuruByIdentifiers(array $row, Collection $allGurus): ?Guru
    {
        // Prioritas 1: Cari berdasarkan NIK
        $guru = $allGurus->firstWhere('nik', $row['nik']);
        if ($guru) {
            return $guru;
        }
        
        // Prioritas 2: Cari berdasarkan NPK (jika ada)
        if (!empty($row['npk'])) {
            $guru = $allGurus->firstWhere('npk', $row['npk']);
            if ($guru) {
                return $guru;
            }
        }
        
        // Prioritas 3: Cari berdasarkan NUPTK (jika ada)
        if (!empty($row['nuptk'])) {
            $guru = $allGurus->firstWhere('nuptk', $row['nuptk']);
            if ($guru) {
                return $guru;
            }
        }
        
        return null;
    }

    private function validateRows(Collection $rows): Collection
    {
        return $rows->skip(1) // Skip header row (baris pertama chunk = header)
            ->map(function ($row) {
                $data = $row->toArray();
                
                Log::channel('guru_bulk_import')->debug('Processing row', [
                    'data_count' => count($data)
                ]);

                // Ambil nilai menggunakan column mapping
                $nama = $this->getValueByKey($data, 'nama');
                $nik = $this->getValueByKey($data, 'nik');
                $jenisKelamin = $this->getValueByKey($data, 'jenis_kelamin');
                $status = $this->getValueByKey($data, 'status');

                // Validasi field wajib
                if (empty($nik) || empty($nama) || empty($jenisKelamin) || empty($status)) {
                    Log::channel('guru_bulk_import')->warning('Validation failed', [
                        'nik' => $nik,
                        'nama' => $nama,
                        'jenis_kelamin' => $jenisKelamin,
                        'status' => $status
                    ]);
                    return null;
                }

                // Konversi NIK/NPK/NUPTK ke string (handle scientific notation)
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
     * Parse headers untuk dynamic column mapping
     */
    private function parseHeaders($headerRow): void
    {
        if ($headerRow instanceof Collection) {
            $headerRow = $headerRow->toArray();
        }

        // Normalize headers: lowercase, trim, ganti spasi/slash dengan underscore
        $this->headers = array_map(function($h) {
            $normalized = strtolower(trim((string)$h));
            $normalized = str_replace(['/', ' '], '_', $normalized);
            return $normalized;
        }, $headerRow);
        
        $this->columnMap = [];

        // Map kolom data pribadi
        $this->mapColumns([
            'nama', 'nik', 'jenis_kelamin', 'status', 'status_kepegawaian',
            'status_perkawinan', 'gelar_depan', 'gelar_belakang', 
            'tempat_lahir', 'tanggal_lahir', 'npk', 'nuptk',
            'kontak_wa_hp', 'kontak_wa/hp', 'email'
        ]);

        // Map kolom jabatan
        $this->mapColumns(['jenis_jabatan', 'keterangan_jabatan']);

        // Map kolom rekening
        $this->mapColumns(['nomor_rekening', 'rekening_atas_nama', 'bank_rekening']);
    }

    private function mapColumns(array $columnNames): void
    {
        foreach ($columnNames as $colName) {
            $normalized = strtolower($colName);
            $key = array_search($normalized, $this->headers);
            if ($key !== false) {
                // Simpan dengan key tanpa slash untuk konsistensi
                $cleanKey = str_replace(['/', ' '], '_', $colName);
                $this->columnMap[$cleanKey] = $key;
            }
        }
    }

    private function getValueByKey(array $row, string $key): mixed
    {
        // Normalize key untuk matching
        $normalizedKey = str_replace(['/', ' '], '_', strtolower($key));
        
        if (!$this->columnMap || !isset($this->columnMap[$normalizedKey])) {
            // Fallback: coba direct access
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
        
        // Handle scientific notation
        if (is_numeric($value) && (strpos($value, 'E') !== false || strpos($value, 'e') !== false)) {
            return number_format((float)$value, 0, '', '');
        }
        
        return $value;
    }

    /**
     * Extract alamat berdasarkan section
     * 
     * Mapping Excel:
     * - Alamat Asli: W-AD (index 22-29)
     * - Alamat Domisili: AF-AM (index 31-38)
     */
    private function extractAlamatBySection(array $row, string $section): array
    {
        $startCol = match($section) {
            'asli' => 22,      // W
            'domisili' => 31,  // AF
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