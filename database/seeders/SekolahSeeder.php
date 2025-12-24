<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\Kabupaten;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map jenis_sekolah enum values
        $jenisSekolah = [
            'RA-TK' => Sekolah::JENIS_SEKOLAH_RA_TK,
            'MI-SD' => Sekolah::JENIS_SEKOLAH_MI_SD,
            'MTS-SMP' => Sekolah::JENIS_SEKOLAH_MTS_SMP,
            'MA-SMA' => Sekolah::JENIS_SEKOLAH_MA_SMA,
            'PT' => Sekolah::JENIS_SEKOLAH_PT,
        ];

        // Map bentuk_pendidikan enum values
        $bentukUmum = Sekolah::BENTUK_PENDIDIKAN_UMUM;
        $bentukPonpes = Sekolah::BENTUK_PENDIDIKAN_PONPES;

        $sekolahData = [
            // Sulawesi Tengah - Kota Palu
            [
                'kode_sekolah' => 'ALK-001',
                'nama' => 'Pesantren Alkhairaat Pusat Palu',
                'jenis_sekolah' => $jenisSekolah['PT'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Kota Palu',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 1, Palu',
                'telepon' => '0451-123456',
                'email' => 'pesantren.pusat@alkhairaat.or.id',
                'website' => 'www.alkhairaat.ac.id',
                'keterangan' => 'Pesantren pusat Alkhairaat dengan murid dari berbagai daerah',
            ],
            [
                'kode_sekolah' => 'ALK-002',
                'nama' => 'SMA Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Kota Palu',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Palu Selatan',
                'alamat' => 'Jl. Alkhairaat No. 2, Palu',
                'telepon' => '0451-234567',
                'email' => 'sma.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Atas dengan kurikulum nasional dan keislaman',
            ],
            [
                'kode_sekolah' => 'ALK-003',
                'nama' => 'SMP Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MTS-SMP'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Kota Palu',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Palu Timur',
                'alamat' => 'Jl. Alkhairaat No. 3, Palu',
                'telepon' => '0451-345678',
                'email' => 'smp.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Pertama dengan pendidikan karakter islami',
            ],
            [
                'kode_sekolah' => 'ALK-004',
                'nama' => 'Madrasah Aliyah Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Kota Palu',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Palu Utara',
                'alamat' => 'Jl. Alkhairaat No. 4, Palu',
                'telepon' => '0451-456789',
                'email' => 'ma.palu@alkhairaat.or.id',
                'keterangan' => 'Madrasah Aliyah dengan fokus pendidikan agama Islam',
            ],
            [
                'kode_sekolah' => 'ALK-005',
                'nama' => 'SD Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MI-SD'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Kota Palu',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 5, Palu',
                'telepon' => '0451-567890',
                'email' => 'sd.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Dasar dengan pendidikan dasar Islam',
            ],
            [
                'kode_sekolah' => 'ALK-006',
                'nama' => 'TK Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['RA-TK'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Kota Palu',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Palu Selatan',
                'alamat' => 'Jl. Alkhairaat No. 6, Palu',
                'telepon' => '0451-678901',
                'email' => 'tk.palu@alkhairaat.or.id',
                'keterangan' => 'Taman Kanak-kanak dengan program pendidikan usia dini',
            ],

            // Sulawesi Tengah - Donggala
            [
                'kode_sekolah' => 'ALK-101',
                'nama' => 'SMA Alkhairaat Donggala',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Donggala',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Donggala',
                'alamat' => 'Jl. Pendidikan No. 10, Donggala',
                'telepon' => '0452-321123',
                'email' => 'sma.donggala@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Atas di Donggala',
            ],
            [
                'kode_sekolah' => 'ALK-102',
                'nama' => 'SMP Alkhairaat Donggala',
                'jenis_sekolah' => $jenisSekolah['MTS-SMP'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Donggala',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Donggala',
                'alamat' => 'Jl. Pendidikan No. 11, Donggala',
                'telepon' => '0452-321124',
                'email' => 'smp.donggala@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Pertama di Donggala',
            ],

            // Sulawesi Tengah - Banggai
            [
                'kode_sekolah' => 'ALK-201',
                'nama' => 'Pesantren Alkhairaat Banggai',
                'jenis_sekolah' => $jenisSekolah['MTS-SMP'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Banggai',
                'provinsi_name' => 'Sulawesi Tengah',
                'kecamatan' => 'Banggai Laut',
                'alamat' => 'Jl. Pesantren No. 1, Banggai',
                'telepon' => '0453-456789',
                'email' => 'pesantren.banggai@alkhairaat.or.id',
                'keterangan' => 'Pesantren Alkhairaat di Kabupaten Banggai',
            ],

            // DKI Jakarta
            [
                'kode_sekolah' => 'ALK-301',
                'nama' => 'Sekolah Islam Alkhairaat Jakarta',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Jakarta Selatan',
                'provinsi_name' => 'DKI Jakarta',
                'kecamatan' => 'Tebet',
                'alamat' => 'Jl. Salemba No. 100, Jakarta Selatan',
                'telepon' => '021-1234567',
                'email' => 'sekolah.jakarta@alkhairaat.or.id',
                'website' => 'jakarta.alkhairaat.or.id',
                'keterangan' => 'Sekolah Islam Alkhairaat di DKI Jakarta',
            ],

            // Jawa Barat - Bandung
            [
                'kode_sekolah' => 'ALK-401',
                'nama' => 'Pesantren Al-Ihsan Alkhairaat Bandung',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Bandung',
                'provinsi_name' => 'Jawa Barat',
                'kecamatan' => 'Cibiru',
                'alamat' => 'Jl. Sariwangi No. 50, Bandung',
                'telepon' => '022-2745678',
                'email' => 'pesantren.bandung@alkhairaat.or.id',
                'keterangan' => 'Pesantren Alkhairaat di Bandung',
            ],
            [
                'kode_sekolah' => 'ALK-402',
                'nama' => 'SD Alkhairaat Bandung',
                'jenis_sekolah' => $jenisSekolah['MI-SD'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Bandung',
                'provinsi_name' => 'Jawa Barat',
                'kecamatan' => 'Cibiru',
                'alamat' => 'Jl. Sariwangi No. 51, Bandung',
                'telepon' => '022-2745679',
                'email' => 'sd.bandung@alkhairaat.or.id',
                'keterangan' => 'Sekolah Dasar Alkhairaat di Bandung',
            ],

            // Jawa Barat - Garut
            [
                'kode_sekolah' => 'ALK-501',
                'nama' => 'Pesantren Alkhairaat Garut',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Garut',
                'provinsi_name' => 'Jawa Barat',
                'kecamatan' => 'Tarogong Kaler',
                'alamat' => 'Jl. Cimanuk No. 25, Garut',
                'telepon' => '0262-234567',
                'email' => 'pesantren.garut@alkhairaat.or.id',
                'keterangan' => 'Pesantren Alkhairaat dengan pendidikan terpadu',
            ],

            // Aceh
            [
                'kode_sekolah' => 'ALK-601',
                'nama' => 'Sekolah Islam Alkhairaat Aceh',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'kabupaten_name' => 'Banda Aceh',
                'provinsi_name' => 'Aceh',
                'kecamatan' => 'Meuraxa',
                'alamat' => 'Jl. Tgk. Imem Lueng Bata No. 100, Banda Aceh',
                'telepon' => '0651-123456',
                'email' => 'sekolah.aceh@alkhairaat.or.id',
                'keterangan' => 'Sekolah Islam Alkhairaat di Banda Aceh',
            ],
        ];

        $successCount = 0;
        $updateCount = 0;

        foreach ($sekolahData as $data) {
            // Extract kabupaten dan provinsi untuk mendapatkan ID kabupaten
            $kabupatenName = $data['kabupaten_name'];
            $provinsiName = $data['provinsi_name'];
            unset($data['kabupaten_name'], $data['provinsi_name']);

            // Find kabupaten by name and provinsi
            $kabupaten = Kabupaten::whereHas('provinsi', function ($query) use ($provinsiName) {
                $query->where('nama_provinsi', $provinsiName);
            })->where('nama_kabupaten', $kabupatenName)->first();

            if (!$kabupaten) {
                $this->command->warn("Kabupaten '{$kabupatenName}' in '{$provinsiName}' not found, skipping {$data['nama']}");
                continue;
            }

            $data['id_kabupaten'] = $kabupaten->id;

            // Use updateOrCreate to avoid duplicate data
            $sekolah = Sekolah::updateOrCreate(
                ['kode_sekolah' => $data['kode_sekolah']],
                $data
            );

            if ($sekolah->wasRecentlyCreated) {
                $this->command->info("✓ Created: {$data['nama']} ({$data['kode_sekolah']})");
                $successCount++;
            } else {
                $this->command->line("→ Updated: {$data['nama']} ({$data['kode_sekolah']})");
                $updateCount++;
            }
        }

        $this->command->newLine();
        $this->command->info("Sekolah seeder completed successfully!");
        $this->command->info("Created: {$successCount}, Updated: {$updateCount}, Total: " . ($successCount + $updateCount));
    }
}
