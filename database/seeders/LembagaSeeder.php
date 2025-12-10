<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use App\Models\Kabupaten;
use Illuminate\Database\Seeder;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Kota Palu (from Sulawesi Tengah)
        $kotaPalu = Kabupaten::where('nama_kabupaten', 'Kota Palu')->first();
        
        if (!$kotaPalu) {
            $this->command->error('Kota Palu not found! Please run ProvinsiKabupatenSeeder first.');
            return;
        }

        $lembagaData = [
            [
                'kode_lembaga' => 'ALK-001',
                'nama' => 'Pesantren Alkhairaat Pusat Palu',
                'jenjang' => Lembaga::JENJANG_PESANTREN,
                'status' => Lembaga::STATUS_AKTIF,
                'kabupaten_id' => $kotaPalu->id,
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 1, Palu',
                'telepon' => '0451-123456',
                'email' => 'pesantren.pusat@alkhairaat.or.id',
                'keterangan' => 'Pesantren pusat Alkhairaat dengan santri dari berbagai daerah',
            ],
            [
                'kode_lembaga' => 'ALK-002',
                'nama' => 'SMA Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_SMA,
                'status' => Lembaga::STATUS_AKTIF,
                'kabupaten_id' => $kotaPalu->id,
                'kecamatan' => 'Palu Selatan',
                'alamat' => 'Jl. Alkhairaat No. 2, Palu',
                'telepon' => '0451-234567',
                'email' => 'sma.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Atas dengan kurikulum nasional dan keislaman',
            ],
            [
                'kode_lembaga' => 'ALK-003',
                'nama' => 'SMP Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_SMP,
                'status' => Lembaga::STATUS_AKTIF,
                'kabupaten_id' => $kotaPalu->id,
                'kecamatan' => 'Palu Timur',
                'alamat' => 'Jl. Alkhairaat No. 3, Palu',
                'telepon' => '0451-345678',
                'email' => 'smp.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Pertama dengan pendidikan karakter islami',
            ],
            [
                'kode_lembaga' => 'ALK-004',
                'nama' => 'Madrasah Aliyah Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_MA,
                'status' => Lembaga::STATUS_AKTIF,
                'kabupaten_id' => $kotaPalu->id,
                'kecamatan' => 'Palu Utara',
                'alamat' => 'Jl. Alkhairaat No. 4, Palu',
                'telepon' => '0451-456789',
                'email' => 'ma.palu@alkhairaat.or.id',
                'keterangan' => 'Madrasah Aliyah dengan fokus pendidikan agama Islam',
            ],
            [
                'kode_lembaga' => 'ALK-005',
                'nama' => 'SD Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_SD,
                'status' => Lembaga::STATUS_AKTIF,
                'kabupaten_id' => $kotaPalu->id,
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 5, Palu',
                'telepon' => '0451-567890',
                'email' => 'sd.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Dasar dengan pendidikan dasar Islam',
            ],
        ];

        foreach ($lembagaData as $data) {
            // Check if lembaga already exists
            if (!Lembaga::where('kode_lembaga', $data['kode_lembaga'])->exists()) {
                Lembaga::create($data);
                $this->command->info("Created lembaga: {$data['nama']}");
            } else {
                $this->command->info("Lembaga {$data['kode_lembaga']} already exists, skipping...");
            }
        }
        
        $this->command->info('Lembaga seeder completed successfully.');
    }
}
