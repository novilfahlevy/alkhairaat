<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\Kabupaten;
use App\Models\JenisSekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
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

        // Get all jenis_sekolah for reference
        $raKurnia = JenisSekolah::where('kode_jenis', 'RA-TK')->first()?->id;
        $miSd = JenisSekolah::where('kode_jenis', 'MI-SD')->first()?->id;
        $mtsSmp = JenisSekolah::where('kode_jenis', 'MTS-SMP')->first()?->id;
        $maSma = JenisSekolah::where('kode_jenis', 'MA-SMA')->first()?->id;
        $pt = JenisSekolah::where('kode_jenis', 'PT')->first()?->id;

        $sekolahData = [
            [
                'kode_sekolah' => 'ALK-001',
                'nama' => 'Pesantren Alkhairaat Pusat Palu',
                'id_jenis_sekolah' => $pt,
                'status' => Sekolah::STATUS_AKTIF,
                'id_kabupaten' => $kotaPalu->id,
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 1, Palu',
                'telepon' => '0451-123456',
                'email' => 'pesantren.pusat@alkhairaat.or.id',
                'keterangan' => 'Pesantren pusat Alkhairaat dengan santri dari berbagai daerah',
            ],
            [
                'kode_sekolah' => 'ALK-002',
                'nama' => 'SMA Alkhairaat Palu',
                'id_jenis_sekolah' => $maSma,
                'status' => Sekolah::STATUS_AKTIF,
                'id_kabupaten' => $kotaPalu->id,
                'kecamatan' => 'Palu Selatan',
                'alamat' => 'Jl. Alkhairaat No. 2, Palu',
                'telepon' => '0451-234567',
                'email' => 'sma.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Atas dengan kurikulum nasional dan keislaman',
            ],
            [
                'kode_sekolah' => 'ALK-003',
                'nama' => 'SMP Alkhairaat Palu',
                'id_jenis_sekolah' => $mtsSmp,
                'status' => Sekolah::STATUS_AKTIF,
                'id_kabupaten' => $kotaPalu->id,
                'kecamatan' => 'Palu Timur',
                'alamat' => 'Jl. Alkhairaat No. 3, Palu',
                'telepon' => '0451-345678',
                'email' => 'smp.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Pertama dengan pendidikan karakter islami',
            ],
            [
                'kode_sekolah' => 'ALK-004',
                'nama' => 'Madrasah Aliyah Alkhairaat Palu',
                'id_jenis_sekolah' => $maSma,
                'status' => Sekolah::STATUS_AKTIF,
                'id_kabupaten' => $kotaPalu->id,
                'kecamatan' => 'Palu Utara',
                'alamat' => 'Jl. Alkhairaat No. 4, Palu',
                'telepon' => '0451-456789',
                'email' => 'ma.palu@alkhairaat.or.id',
                'keterangan' => 'Madrasah Aliyah dengan fokus pendidikan agama Islam',
            ],
            [
                'kode_sekolah' => 'ALK-005',
                'nama' => 'SD Alkhairaat Palu',
                'id_jenis_sekolah' => $miSd,
                'status' => Sekolah::STATUS_AKTIF,
                'id_kabupaten' => $kotaPalu->id,
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 5, Palu',
                'telepon' => '0451-567890',
                'email' => 'sd.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Dasar dengan pendidikan dasar Islam',
            ],
        ];

        foreach ($sekolahData as $data) {
            // Check if sekolah already exists
            if (!Sekolah::where('kode_sekolah', $data['kode_sekolah'])->exists()) {
                Sekolah::create($data);
                $this->command->info("Created sekolah: {$data['nama']}");
            } else {
                $this->command->info("Sekolah {$data['kode_sekolah']} already exists, skipping...");
            }
        }
        
        $this->command->info('Sekolah seeder completed successfully.');
    }
}
