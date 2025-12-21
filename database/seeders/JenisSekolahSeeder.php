<?php

namespace Database\Seeders;

use App\Models\JenisSekolah;
use Illuminate\Database\Seeder;

class JenisSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSekolahData = [
            [
                'kode_jenis' => 'RA-TK',
                'nama_jenis' => 'RA / TK',
                'deskripsi' => 'Raudhatul Athfal / Taman Kanak-Kanak',
            ],
            [
                'kode_jenis' => 'MI-SD',
                'nama_jenis' => 'MI / SD',
                'deskripsi' => 'Madrasah Ibtidaiyah / Sekolah Dasar',
            ],
            [
                'kode_jenis' => 'MTS-SMP',
                'nama_jenis' => 'MTS / SMP',
                'deskripsi' => 'Madrasah Tsanawiyah / Sekolah Menengah Pertama',
            ],
            [
                'kode_jenis' => 'MA-SMA',
                'nama_jenis' => 'MA / SMA',
                'deskripsi' => 'Madrasah Aliyah / Sekolah Menengah Atas',
            ],
            [
                'kode_jenis' => 'PT',
                'nama_jenis' => 'Perguruan Tinggi PT',
                'deskripsi' => 'Perguruan Tinggi',
            ],
        ];

        foreach ($jenisSekolahData as $data) {
            JenisSekolah::firstOrCreate(
                ['kode_jenis' => $data['kode_jenis']],
                $data
            );
        }

        $this->command->info('Jenis Sekolah data seeded successfully.');
    }
}
