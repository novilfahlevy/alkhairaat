<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\Alamat;
use App\Models\Murid;
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

                // Update murid status_alumni
                $murid->status_alumni = true;
                $murid->save();

                // Create or update Alumni record (following approve() method logic)
                $alumni = Alumni::firstOrCreate(
                    ['id_murid' => $murid->id],
                    [
                        'profesi_sekarang' => trim($row['profesi_sekarang'] ?? ''),
                        'nama_tempat_kerja' => trim($row['nama_tempat_kerja'] ?? ''),
                        'kota_tempat_kerja' => trim($row['kota_tempat_kerja'] ?? ''),
                        'riwayat_pekerjaan' => trim($row['riwayat_pekerjaan'] ?? ''),
                    ]
                );

                // Update Alumni if already exists
                if ($alumni->exists && $alumni->wasRecentlyCreated === false) {
                    $alumni->update([
                        'profesi_sekarang' => trim($row['profesi_sekarang'] ?? '') ?: $alumni->profesi_sekarang,
                        'nama_tempat_kerja' => trim($row['nama_tempat_kerja'] ?? '') ?: $alumni->nama_tempat_kerja,
                        'kota_tempat_kerja' => trim($row['kota_tempat_kerja'] ?? '') ?: $alumni->kota_tempat_kerja,
                        'riwayat_pekerjaan' => trim($row['riwayat_pekerjaan'] ?? '') ?: $alumni->riwayat_pekerjaan,
                    ]);
                }

                // Create or update Alamat (domisili) if alamat_sekarang provided
                $alamatSekarang = trim($row['alamat_sekarang'] ?? '');
                if ($alamatSekarang) {
                    Alamat::updateOrCreate(
                        [
                            'id_murid' => $murid->id,
                            'jenis' => Alamat::JENIS_DOMISILI,
                        ],
                        [
                            'alamat_lengkap' => $alamatSekarang,
                        ]
                    );
                }

                // Update contact info on murid if provided
                $kontakWa = trim($row['kontak_whatsapp'] ?? '');
                $kontakEmail = trim($row['kontak_email'] ?? '');

                if ($kontakWa || $kontakEmail) {
                    $murid->update([
                        'kontak_wa_hp' => $kontakWa ?: $murid->kontak_wa_hp,
                        'kontak_email' => $kontakEmail ?: $murid->kontak_email,
                    ]);
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
