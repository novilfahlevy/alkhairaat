<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinsiKabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data provinsi dan kabupaten Indonesia (contoh untuk beberapa provinsi utama)
        $data = [
            [
                'kode_provinsi' => '72',
                'nama_provinsi' => 'Sulawesi Tengah',
                'kabupaten' => [
                    ['kode_kabupaten' => '7201', 'nama_kabupaten' => 'Kabupaten Banggai Kepulauan'],
                    ['kode_kabupaten' => '7202', 'nama_kabupaten' => 'Kabupaten Banggai'],
                    ['kode_kabupaten' => '7203', 'nama_kabupaten' => 'Kabupaten Morowali'],
                    ['kode_kabupaten' => '7204', 'nama_kabupaten' => 'Kabupaten Poso'],
                    ['kode_kabupaten' => '7205', 'nama_kabupaten' => 'Kabupaten Donggala'],
                    ['kode_kabupaten' => '7206', 'nama_kabupaten' => 'Kabupaten Toli-Toli'],
                    ['kode_kabupaten' => '7207', 'nama_kabupaten' => 'Kabupaten Buol'],
                    ['kode_kabupaten' => '7208', 'nama_kabupaten' => 'Kabupaten Parigi Moutong'],
                    ['kode_kabupaten' => '7209', 'nama_kabupaten' => 'Kabupaten Tojo Una-Una'],
                    ['kode_kabupaten' => '7210', 'nama_kabupaten' => 'Kabupaten Sigi'],
                    ['kode_kabupaten' => '7211', 'nama_kabupaten' => 'Kabupaten Banggai Laut'],
                    ['kode_kabupaten' => '7212', 'nama_kabupaten' => 'Kabupaten Morowali Utara'],
                    ['kode_kabupaten' => '7271', 'nama_kabupaten' => 'Kota Palu'],
                ]
            ],
            [
                'kode_provinsi' => '11',
                'nama_provinsi' => 'Aceh',
                'kabupaten' => [
                    ['kode_kabupaten' => '1101', 'nama_kabupaten' => 'Kabupaten Simeulue'],
                    ['kode_kabupaten' => '1102', 'nama_kabupaten' => 'Kabupaten Aceh Singkil'],
                    ['kode_kabupaten' => '1103', 'nama_kabupaten' => 'Kabupaten Aceh Selatan'],
                    ['kode_kabupaten' => '1104', 'nama_kabupaten' => 'Kabupaten Aceh Tenggara'],
                    ['kode_kabupaten' => '1105', 'nama_kabupaten' => 'Kabupaten Aceh Timur'],
                    ['kode_kabupaten' => '1106', 'nama_kabupaten' => 'Kabupaten Aceh Tengah'],
                    ['kode_kabupaten' => '1107', 'nama_kabupaten' => 'Kabupaten Aceh Barat'],
                    ['kode_kabupaten' => '1108', 'nama_kabupaten' => 'Kabupaten Aceh Besar'],
                    ['kode_kabupaten' => '1109', 'nama_kabupaten' => 'Kabupaten Pidie'],
                    ['kode_kabupaten' => '1110', 'nama_kabupaten' => 'Kabupaten Bireuen'],
                    ['kode_kabupaten' => '1111', 'nama_kabupaten' => 'Kabupaten Aceh Utara'],
                    ['kode_kabupaten' => '1112', 'nama_kabupaten' => 'Kabupaten Aceh Barat Daya'],
                    ['kode_kabupaten' => '1113', 'nama_kabupaten' => 'Kabupaten Gayo Lues'],
                    ['kode_kabupaten' => '1114', 'nama_kabupaten' => 'Kabupaten Aceh Tamiang'],
                    ['kode_kabupaten' => '1115', 'nama_kabupaten' => 'Kabupaten Nagan Raya'],
                    ['kode_kabupaten' => '1116', 'nama_kabupaten' => 'Kabupaten Aceh Jaya'],
                    ['kode_kabupaten' => '1117', 'nama_kabupaten' => 'Kabupaten Bener Meriah'],
                    ['kode_kabupaten' => '1118', 'nama_kabupaten' => 'Kabupaten Pidie Jaya'],
                    ['kode_kabupaten' => '1171', 'nama_kabupaten' => 'Kota Banda Aceh'],
                    ['kode_kabupaten' => '1172', 'nama_kabupaten' => 'Kota Sabang'],
                    ['kode_kabupaten' => '1173', 'nama_kabupaten' => 'Kota Langsa'],
                    ['kode_kabupaten' => '1174', 'nama_kabupaten' => 'Kota Lhokseumawe'],
                    ['kode_kabupaten' => '1175', 'nama_kabupaten' => 'Kota Subulussalam'],
                ]
            ],
            [
                'kode_provinsi' => '31',
                'nama_provinsi' => 'DKI Jakarta',
                'kabupaten' => [
                    ['kode_kabupaten' => '3101', 'nama_kabupaten' => 'Kabupaten Kepulauan Seribu'],
                    ['kode_kabupaten' => '3171', 'nama_kabupaten' => 'Kota Jakarta Selatan'],
                    ['kode_kabupaten' => '3172', 'nama_kabupaten' => 'Kota Jakarta Timur'],
                    ['kode_kabupaten' => '3173', 'nama_kabupaten' => 'Kota Jakarta Pusat'],
                    ['kode_kabupaten' => '3174', 'nama_kabupaten' => 'Kota Jakarta Barat'],
                    ['kode_kabupaten' => '3175', 'nama_kabupaten' => 'Kota Jakarta Utara'],
                ]
            ],
            [
                'kode_provinsi' => '32',
                'nama_provinsi' => 'Jawa Barat',
                'kabupaten' => [
                    ['kode_kabupaten' => '3201', 'nama_kabupaten' => 'Kabupaten Bogor'],
                    ['kode_kabupaten' => '3202', 'nama_kabupaten' => 'Kabupaten Sukabumi'],
                    ['kode_kabupaten' => '3203', 'nama_kabupaten' => 'Kabupaten Cianjur'],
                    ['kode_kabupaten' => '3204', 'nama_kabupaten' => 'Kabupaten Bandung'],
                    ['kode_kabupaten' => '3205', 'nama_kabupaten' => 'Kabupaten Garut'],
                    ['kode_kabupaten' => '3206', 'nama_kabupaten' => 'Kabupaten Tasikmalaya'],
                    ['kode_kabupaten' => '3207', 'nama_kabupaten' => 'Kabupaten Ciamis'],
                    ['kode_kabupaten' => '3208', 'nama_kabupaten' => 'Kabupaten Kuningan'],
                    ['kode_kabupaten' => '3209', 'nama_kabupaten' => 'Kabupaten Cirebon'],
                    ['kode_kabupaten' => '3210', 'nama_kabupaten' => 'Kabupaten Majalengka'],
                    ['kode_kabupaten' => '3211', 'nama_kabupaten' => 'Kabupaten Sumedang'],
                    ['kode_kabupaten' => '3212', 'nama_kabupaten' => 'Kabupaten Indramayu'],
                    ['kode_kabupaten' => '3213', 'nama_kabupaten' => 'Kabupaten Subang'],
                    ['kode_kabupaten' => '3214', 'nama_kabupaten' => 'Kabupaten Purwakarta'],
                    ['kode_kabupaten' => '3215', 'nama_kabupaten' => 'Kabupaten Karawang'],
                    ['kode_kabupaten' => '3216', 'nama_kabupaten' => 'Kabupaten Bekasi'],
                    ['kode_kabupaten' => '3217', 'nama_kabupaten' => 'Kabupaten Bandung Barat'],
                    ['kode_kabupaten' => '3218', 'nama_kabupaten' => 'Kabupaten Pangandaran'],
                    ['kode_kabupaten' => '3271', 'nama_kabupaten' => 'Kota Bogor'],
                    ['kode_kabupaten' => '3272', 'nama_kabupaten' => 'Kota Sukabumi'],
                    ['kode_kabupaten' => '3273', 'nama_kabupaten' => 'Kota Bandung'],
                    ['kode_kabupaten' => '3274', 'nama_kabupaten' => 'Kota Cirebon'],
                    ['kode_kabupaten' => '3275', 'nama_kabupaten' => 'Kota Bekasi'],
                    ['kode_kabupaten' => '3276', 'nama_kabupaten' => 'Kota Depok'],
                    ['kode_kabupaten' => '3277', 'nama_kabupaten' => 'Kota Cimahi'],
                    ['kode_kabupaten' => '3278', 'nama_kabupaten' => 'Kota Tasikmalaya'],
                    ['kode_kabupaten' => '3279', 'nama_kabupaten' => 'Kota Banjar'],
                ]
            ],
            [
                'kode_provinsi' => '33',
                'nama_provinsi' => 'Jawa Tengah',
                'kabupaten' => [
                    ['kode_kabupaten' => '3301', 'nama_kabupaten' => 'Kabupaten Cilacap'],
                    ['kode_kabupaten' => '3302', 'nama_kabupaten' => 'Kabupaten Banyumas'],
                    ['kode_kabupaten' => '3303', 'nama_kabupaten' => 'Kabupaten Purbalingga'],
                    ['kode_kabupaten' => '3304', 'nama_kabupaten' => 'Kabupaten Banjarnegara'],
                    ['kode_kabupaten' => '3305', 'nama_kabupaten' => 'Kabupaten Kebumen'],
                    ['kode_kabupaten' => '3306', 'nama_kabupaten' => 'Kabupaten Purworejo'],
                    ['kode_kabupaten' => '3307', 'nama_kabupaten' => 'Kabupaten Wonosobo'],
                    ['kode_kabupaten' => '3308', 'nama_kabupaten' => 'Kabupaten Magelang'],
                    ['kode_kabupaten' => '3309', 'nama_kabupaten' => 'Kabupaten Boyolali'],
                    ['kode_kabupaten' => '3310', 'nama_kabupaten' => 'Kabupaten Klaten'],
                    ['kode_kabupaten' => '3311', 'nama_kabupaten' => 'Kabupaten Sukoharjo'],
                    ['kode_kabupaten' => '3312', 'nama_kabupaten' => 'Kabupaten Wonogiri'],
                    ['kode_kabupaten' => '3313', 'nama_kabupaten' => 'Kabupaten Karanganyar'],
                    ['kode_kabupaten' => '3314', 'nama_kabupaten' => 'Kabupaten Sragen'],
                    ['kode_kabupaten' => '3315', 'nama_kabupaten' => 'Kabupaten Grobogan'],
                    ['kode_kabupaten' => '3316', 'nama_kabupaten' => 'Kabupaten Blora'],
                    ['kode_kabupaten' => '3317', 'nama_kabupaten' => 'Kabupaten Rembang'],
                    ['kode_kabupaten' => '3318', 'nama_kabupaten' => 'Kabupaten Pati'],
                    ['kode_kabupaten' => '3319', 'nama_kabupaten' => 'Kabupaten Kudus'],
                    ['kode_kabupaten' => '3320', 'nama_kabupaten' => 'Kabupaten Jepara'],
                    ['kode_kabupaten' => '3321', 'nama_kabupaten' => 'Kabupaten Demak'],
                    ['kode_kabupaten' => '3322', 'nama_kabupaten' => 'Kabupaten Semarang'],
                    ['kode_kabupaten' => '3323', 'nama_kabupaten' => 'Kabupaten Temanggung'],
                    ['kode_kabupaten' => '3324', 'nama_kabupaten' => 'Kabupaten Kendal'],
                    ['kode_kabupaten' => '3325', 'nama_kabupaten' => 'Kabupaten Batang'],
                    ['kode_kabupaten' => '3326', 'nama_kabupaten' => 'Kabupaten Pekalongan'],
                    ['kode_kabupaten' => '3327', 'nama_kabupaten' => 'Kabupaten Pemalang'],
                    ['kode_kabupaten' => '3328', 'nama_kabupaten' => 'Kabupaten Tegal'],
                    ['kode_kabupaten' => '3329', 'nama_kabupaten' => 'Kabupaten Brebes'],
                    ['kode_kabupaten' => '3371', 'nama_kabupaten' => 'Kota Magelang'],
                    ['kode_kabupaten' => '3372', 'nama_kabupaten' => 'Kota Surakarta'],
                    ['kode_kabupaten' => '3373', 'nama_kabupaten' => 'Kota Salatiga'],
                    ['kode_kabupaten' => '3374', 'nama_kabupaten' => 'Kota Semarang'],
                    ['kode_kabupaten' => '3375', 'nama_kabupaten' => 'Kota Pekalongan'],
                    ['kode_kabupaten' => '3376', 'nama_kabupaten' => 'Kota Tegal'],
                ]
            ],
        ];

        foreach ($data as $provinsiData) {
            $provinsi = Provinsi::create([
                'kode_provinsi' => $provinsiData['kode_provinsi'],
                'nama_provinsi' => $provinsiData['nama_provinsi'],
            ]);

            foreach ($provinsiData['kabupaten'] as $kabupatenData) {
                Kabupaten::create([
                    'kode_kabupaten' => $kabupatenData['kode_kabupaten'],
                    'nama_kabupaten' => $kabupatenData['nama_kabupaten'],
                    'provinsi_id' => $provinsi->id,
                ]);
            }
        }
    }
}
