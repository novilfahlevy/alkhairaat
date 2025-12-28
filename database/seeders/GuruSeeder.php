<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\JabatanGuru;
use App\Models\Scopes\NauanganSekolahScope;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $sekolahList = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)->get();
        $now = now();
        $guruBatchSize = 200;
        $jabatanBatchSize = 200;

        // Set unik global
        $nikSet = [];
        $npkSet = [];
        $nuptkSet = [];
        $emailSet = [];
        $waSet = [];

        foreach ($sekolahList as $sekolah) {
            $guruCount = rand(8, 20);
            $guruData = [];
            $nikToIndex = [];
            $jabatanData = [];

            for ($i = 0; $i < $guruCount; $i++) {
                // Unique NIK, NPK, NUPTK, email, wa secara global
                do {
                    $nik = $faker->numerify('###############');
                } while (isset($nikSet[$nik]));
                $nikSet[$nik] = true;
                do {
                    $npk = $faker->numerify('########');
                } while (isset($npkSet[$npk]));
                $npkSet[$npk] = true;
                do {
                    $nuptk = $faker->numerify('########');
                } while (isset($nuptkSet[$nuptk]));
                $nuptkSet[$nuptk] = true;
                do {
                    $email = $faker->unique()->safeEmail;
                } while (isset($emailSet[$email]));
                $emailSet[$email] = true;
                do {
                    $wa = $faker->unique()->phoneNumber;
                } while (isset($waSet[$wa]));
                $waSet[$wa] = true;

                $jenisKelamin = $faker->randomElement([Guru::JENIS_KELAMIN_LAKI, Guru::JENIS_KELAMIN_PEREMPUAN]);
                $guruData[] = [
                    'nama' => $faker->name($jenisKelamin === Guru::JENIS_KELAMIN_LAKI ? 'male' : 'female'),
                    'status' => Guru::STATUS_AKTIF,
                    'nama_gelar_depan' => null,
                    'nama_gelar_belakang' => null,
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->date('Y-m-d', '-25 years'),
                    'jenis_kelamin' => $jenisKelamin,
                    'status_perkawinan' => $faker->randomElement([Guru::STATUS_PERKAWINAN_LAJANG, Guru::STATUS_PERKAWINAN_MENIKAH]),
                    'nik' => $nik,
                    'status_kepegawaian' => $faker->randomElement([Guru::STATUS_KEPEGAWAIAN_PNS, Guru::STATUS_KEPEGAWAIAN_NON_PNS, Guru::STATUS_KEPEGAWAIAN_PPPK]),
                    'npk' => $npk,
                    'nuptk' => $nuptk,
                    'kontak_wa_hp' => $wa,
                    'kontak_email' => $email,
                    'nomor_rekening' => null,
                    'rekening_atas_nama' => null,
                    'bank_rekening' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $nikToIndex[$nik] = $i;
            }

            // Batch insert guru
            foreach (array_chunk($guruData, $guruBatchSize) as $batch) {
                \DB::table('guru')->insert($batch);
            }

            // Ambil id guru yang baru diinsert berdasarkan NIK
            $nikList = array_column($guruData, 'nik');
            $guruBaru = \DB::table('guru')->whereIn('nik', $nikList)->get(['id', 'nik']);
            $nikToId = [];
            foreach ($guruBaru as $row) {
                $nikToId[$row->nik] = $row->id;
            }

            // Buat data jabatan_guru
            foreach ($nikList as $nik) {
                $guruId = $nikToId[$nik] ?? null;
                if ($guruId) {
                    $jenisJabatan = $faker->randomElement([
                        JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH,
                        JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH,
                        JabatanGuru::JENIS_JABATAN_GURU,
                        JabatanGuru::JENIS_JABATAN_STAFF_TU,
                        JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA,
                    ]);
                    $jabatanData[] = [
                        'id_guru' => $guruId,
                        'id_sekolah' => $sekolah->id,
                        'jenis_jabatan' => $jenisJabatan,
                        'keterangan_jabatan' => $jenisJabatan === JabatanGuru::JENIS_JABATAN_GURU ? $faker->randomElement(['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'Bahasa Inggris', 'Penjaskes', 'Seni Budaya']) : $jenisJabatan,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Batch insert jabatan_guru
            foreach (array_chunk($jabatanData, $jabatanBatchSize) as $batch) {
                \DB::table('jabatan_guru')->insert($batch);
            }

            $this->command->info("Guru untuk sekolah {$sekolah->nama} berhasil dibuat: {$guruCount}");
        }
    }
}
