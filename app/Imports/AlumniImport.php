<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\Murid;
use App\Models\Sekolah;
use App\Models\SekolahMurid;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlumniImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $successCount = 0;
        $failureCount = 0;
        $errors = [];

        foreach ($collection as $rowIndex => $row) {
            try {
                // Skip empty rows
                if (empty(array_filter($row->toArray()))) {
                    continue;
                }

                $nik = trim($row['nik'] ?? '');

                // Validate required fields
                if (empty($nik)) {
                    $errors[] = [
                        'row' => $rowIndex + 2, // +2 karena row 1 header, array 0-indexed
                        'error' => 'NIK harus diisi'
                    ];
                    $failureCount++;
                    continue;
                }

                // Find murid by NIK
                $murid = Murid::where('nik', $nik)->first();

                if (!$murid) {
                    $errors[] = [
                        'row' => $rowIndex + 2,
                        'error' => "Murid dengan NIK '{$nik}' tidak ditemukan"
                    ];
                    $failureCount++;
                    continue;
                }

                $sekolahMurid = SekolahMurid::where('id_murid', $murid?->id)
                    ->whereHas('sekolah', function ($q) use ($row) {
                        $q->where('kode_sekolah', $row['kode_sekolah'] ?? '');
                    })
                    ->first();

                if (!$sekolahMurid) {
                    $errors[] = [
                        'row' => $rowIndex + 2,
                        'error' => "Murid dengan NIK '{$nik}' tidak terdaftar di sekolah dengan kode '{$row['kode_sekolah']}'"
                    ];
                    $failureCount++;
                    continue;
                }

                $alumniData = [
                    'id_murid' => $murid->id,
                    'profesi_sekarang' => trim($row['profesi_sekarang'] ?? ''),
                    'nama_tempat_kerja' => trim($row['nama_tempat_kerja'] ?? ''),
                    'kota_tempat_kerja' => trim($row['kota_tempat_kerja'] ?? ''),
                    'riwayat_pekerjaan' => trim($row['riwayat_pekerjaan'] ?? ''),
                ];

                // Remove empty values
                $alumniData = array_filter($alumniData, fn($value) => $value !== '');

                // Update murid status_alumni
                $murid->status_alumni = true;
                $murid->save();

                // Update sekolah_murid status_kelulusan
                $sekolahMurid->status_kelulusan = 'ya';
                $sekolahMurid->save();

                // Create alumni record
                if (!Alumni::where('id_murid', $murid->id)->exists()) {
                    Alumni::create($alumniData);
                }

                $successCount++;

            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $rowIndex + 2,
                    'error' => 'Error: ' . $e->getMessage()
                ];
                $failureCount++;
            }
        }

        // Store result in session
        session([
            'alumni_import_success' => $successCount,
            'alumni_import_failure' => $failureCount,
            'alumni_import_errors' => $errors,
        ]);
    }

    /**
     * Define validation rules for import
     */
    public function rules(): array
    {
        return [
            'nik' => 'required',
        ];
    }

    /**
     * Get custom attribute names for validation
     */
    public function customValidationAttributes()
    {
        return [
            'nik' => 'NIK',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nik.required' => 'Kolom NIK harus ada',
        ];
    }
}
