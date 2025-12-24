<?php

namespace Database\Seeders;

use App\Models\SekolahExternal;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahExternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define enum values for jenis_sekolah and bentuk_pendidikan
        $jenisSekolahValues = array_keys(Sekolah::JENIS_SEKOLAH_OPTIONS);
        $bentukPendidikanValues = array_keys(Sekolah::BENTUK_PENDIDIKAN_OPTIONS);

        // Data sekolah external sample
        $sekolahExternalData = [
            [
                'jenis_sekolah' => $jenisSekolahValues[0] ?? Sekolah::JENIS_SEKOLAH_MI_SD,
                'bentuk_pendidikan' => $bentukPendidikanValues[0] ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                'nama_sekolah' => 'SD Negeri 01 Pusat Kota',
                'kota_sekolah' => 'Jakarta',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[1] ?? Sekolah::JENIS_SEKOLAH_MTS_SMP,
                'bentuk_pendidikan' => $bentukPendidikanValues[0] ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                'nama_sekolah' => 'SMP Swasta Maju Jaya',
                'kota_sekolah' => 'Surabaya',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[0] ?? Sekolah::JENIS_SEKOLAH_MI_SD,
                'bentuk_pendidikan' => $bentukPendidikanValues[1] ?? Sekolah::BENTUK_PENDIDIKAN_PONPES,
                'nama_sekolah' => 'SMA Negeri 5',
                'kota_sekolah' => 'Bandung',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[2] ?? Sekolah::JENIS_SEKOLAH_MTS_SMP,
                'bentuk_pendidikan' => $bentukPendidikanValues[0] ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                'nama_sekolah' => 'SMK Teknik Industri',
                'kota_sekolah' => 'Medan',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[1] ?? Sekolah::JENIS_SEKOLAH_MTS_SMP,
                'bentuk_pendidikan' => $bentukPendidikanValues[1] ?? Sekolah::BENTUK_PENDIDIKAN_PONPES,
                'nama_sekolah' => 'Pesantren Modern Al-Azhar',
                'kota_sekolah' => 'Bogor',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[0] ?? Sekolah::JENIS_SEKOLAH_MI_SD,
                'bentuk_pendidikan' => $bentukPendidikanValues[0] ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                'nama_sekolah' => 'SD Islam Terpadu Salsabila',
                'kota_sekolah' => 'Yogyakarta',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[2] ?? Sekolah::JENIS_SEKOLAH_MTS_SMP,
                'bentuk_pendidikan' => $bentukPendidikanValues[1] ?? Sekolah::BENTUK_PENDIDIKAN_PONPES,
                'nama_sekolah' => 'SMA Bhakti Nusa',
                'kota_sekolah' => 'Makassar',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[1] ?? Sekolah::JENIS_SEKOLAH_MTS_SMP,
                'bentuk_pendidikan' => $bentukPendidikanValues[0] ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                'nama_sekolah' => 'SMP Plus Assalaam',
                'kota_sekolah' => 'Cilegon',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[0] ?? Sekolah::JENIS_SEKOLAH_MI_SD,
                'bentuk_pendidikan' => $bentukPendidikanValues[1] ?? Sekolah::BENTUK_PENDIDIKAN_PONPES,
                'nama_sekolah' => 'SD Muhammadiyah 01',
                'kota_sekolah' => 'Semarang',
            ],
            [
                'jenis_sekolah' => $jenisSekolahValues[2] ?? Sekolah::JENIS_SEKOLAH_MTS_SMP,
                'bentuk_pendidikan' => $bentukPendidikanValues[0] ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                'nama_sekolah' => 'SMK Negeri 2 Pertanian',
                'kota_sekolah' => 'Banjarmasin',
            ],
        ];

        // Insert data ke database menggunakan firstOrCreate untuk menghindari duplikasi
        foreach ($sekolahExternalData as $data) {
            SekolahExternal::firstOrCreate(
                [
                    'nama_sekolah' => $data['nama_sekolah'],
                    'kota_sekolah' => $data['kota_sekolah'],
                ],
                [
                    'jenis_sekolah' => $data['jenis_sekolah'],
                    'bentuk_pendidikan' => $data['bentuk_pendidikan'],
                ]
            );
        }
    }
}
