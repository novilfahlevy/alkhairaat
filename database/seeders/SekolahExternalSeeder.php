<?php

namespace Database\Seeders;

use App\Models\SekolahExternal;
use App\Models\JenisSekolah;
use App\Models\BentukPendidikan;
use Illuminate\Database\Seeder;

class SekolahExternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data jenis sekolah dan bentuk pendidikan yang tersedia
        $jenisSekolah = JenisSekolah::pluck('id')->toArray();
        $bentukPendidikan = BentukPendidikan::pluck('id')->toArray();

        // Data sekolah external sample
        $sekolahExternalData = [
            [
                'id_jenis_sekolah' => $jenisSekolah[0] ?? 1,
                'id_bentuk_pendidikan' => $bentukPendidikan[0] ?? 1,
                'nama_sekolah' => 'SD Negeri 01 Pusat Kota',
                'kota_sekolah' => 'Jakarta',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[1] ?? 2,
                'id_bentuk_pendidikan' => $bentukPendidikan[0] ?? 1,
                'nama_sekolah' => 'SMP Swasta Maju Jaya',
                'kota_sekolah' => 'Surabaya',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[0] ?? 1,
                'id_bentuk_pendidikan' => $bentukPendidikan[1] ?? 2,
                'nama_sekolah' => 'SMA Negeri 5',
                'kota_sekolah' => 'Bandung',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[2] ?? 3,
                'id_bentuk_pendidikan' => $bentukPendidikan[0] ?? 1,
                'nama_sekolah' => 'SMK Teknik Industri',
                'kota_sekolah' => 'Medan',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[1] ?? 2,
                'id_bentuk_pendidikan' => $bentukPendidikan[1] ?? 2,
                'nama_sekolah' => 'Pesantren Modern Al-Azhar',
                'kota_sekolah' => 'Bogor',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[0] ?? 1,
                'id_bentuk_pendidikan' => $bentukPendidikan[0] ?? 1,
                'nama_sekolah' => 'SD Islam Terpadu Salsabila',
                'kota_sekolah' => 'Yogyakarta',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[2] ?? 3,
                'id_bentuk_pendidikan' => $bentukPendidikan[1] ?? 2,
                'nama_sekolah' => 'SMA Bhakti Nusa',
                'kota_sekolah' => 'Makassar',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[1] ?? 2,
                'id_bentuk_pendidikan' => $bentukPendidikan[0] ?? 1,
                'nama_sekolah' => 'SMP Plus Assalaam',
                'kota_sekolah' => 'Cilegon',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[0] ?? 1,
                'id_bentuk_pendidikan' => $bentukPendidikan[1] ?? 2,
                'nama_sekolah' => 'SD Muhammadiyah 01',
                'kota_sekolah' => 'Semarang',
            ],
            [
                'id_jenis_sekolah' => $jenisSekolah[2] ?? 3,
                'id_bentuk_pendidikan' => $bentukPendidikan[0] ?? 1,
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
                    'id_jenis_sekolah' => $data['id_jenis_sekolah'],
                    'id_bentuk_pendidikan' => $data['id_bentuk_pendidikan'],
                ]
            );
        }
    }
}
