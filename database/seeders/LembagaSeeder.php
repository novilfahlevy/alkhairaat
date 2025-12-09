<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use Illuminate\Database\Seeder;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lembagaData = [
            [
                'kode_lembaga' => 'ALK-001',
                'nama' => 'Pesantren Alkhairaat Pusat Palu',
                'jenjang' => Lembaga::JENJANG_PESANTREN,
                'status' => Lembaga::STATUS_AKTIF,
                'provinsi' => 'Sulawesi Tengah',
                'kabupaten' => 'Kota Palu',
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 1, Palu',
                'telepon' => '0451-123456',
                'email' => 'pesantren.pusat@alkhairaat.or.id',
            ],
            [
                'kode_lembaga' => 'ALK-002',
                'nama' => 'SMA Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_SMA,
                'status' => Lembaga::STATUS_AKTIF,
                'provinsi' => 'Sulawesi Tengah',
                'kabupaten' => 'Kota Palu',
                'kecamatan' => 'Palu Selatan',
                'alamat' => 'Jl. Alkhairaat No. 2, Palu',
                'telepon' => '0451-234567',
                'email' => 'sma.palu@alkhairaat.or.id',
            ],
            [
                'kode_lembaga' => 'ALK-003',
                'nama' => 'SMP Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_SMP,
                'status' => Lembaga::STATUS_AKTIF,
                'provinsi' => 'Sulawesi Tengah',
                'kabupaten' => 'Kota Palu',
                'kecamatan' => 'Palu Timur',
                'alamat' => 'Jl. Alkhairaat No. 3, Palu',
                'telepon' => '0451-345678',
                'email' => 'smp.palu@alkhairaat.or.id',
            ],
            [
                'kode_lembaga' => 'ALK-004',
                'nama' => 'Madrasah Aliyah Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_MA,
                'status' => Lembaga::STATUS_AKTIF,
                'provinsi' => 'Sulawesi Tengah',
                'kabupaten' => 'Kota Palu',
                'kecamatan' => 'Palu Utara',
                'alamat' => 'Jl. Alkhairaat No. 4, Palu',
                'telepon' => '0451-456789',
                'email' => 'ma.palu@alkhairaat.or.id',
            ],
            [
                'kode_lembaga' => 'ALK-005',
                'nama' => 'SD Alkhairaat Palu',
                'jenjang' => Lembaga::JENJANG_SD,
                'status' => Lembaga::STATUS_AKTIF,
                'provinsi' => 'Sulawesi Tengah',
                'kabupaten' => 'Kota Palu',
                'kecamatan' => 'Palu Barat',
                'alamat' => 'Jl. Alkhairaat No. 5, Palu',
                'telepon' => '0451-567890',
                'email' => 'sd.palu@alkhairaat.or.id',
            ],
        ];

        foreach ($lembagaData as $data) {
            Lembaga::create($data);
        }
    }
}
