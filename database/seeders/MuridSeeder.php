<?php

namespace Database\Seeders;

use App\Models\Murid;
use App\Models\Scopes\NauanganSekolahScope;
use App\Models\Sekolah;
use App\Models\SekolahMurid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MuridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $sekolahList = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)->get();
        $now = now();
        $muridBatchSize = 500;
        $sekolahMuridBatchSize = 500;

        // Set unik global
        $nisnSet = [];
        $nikSet = [];
        $emailSet = [];
        $waSet = [];

        foreach ($sekolahList as $sekolah) {
            $muridCount = rand(1000, 2000);
            $muridData = [];
            $nisnToIndex = [];
            $sekolahMuridData = [];

            for ($i = 0; $i < $muridCount; $i++) {
                // Generate unique NISN, NIK, email, kontak_wa_hp secara global
                do {
                    $nisn = $faker->numerify('########');
                } while (isset($nisnSet[$nisn]));
                $nisnSet[$nisn] = true;
                do {
                    $nik = $faker->numerify('###############');
                } while (isset($nikSet[$nik]));
                $nikSet[$nik] = true;
                do {
                    $email = $faker->unique()->safeEmail;
                } while (isset($emailSet[$email]));
                $emailSet[$email] = true;
                do {
                    $wa = $faker->unique()->phoneNumber;
                } while (isset($waSet[$wa]));
                $waSet[$wa] = true;

                $jenisKelamin = $faker->randomElement([Murid::JENIS_KELAMIN_LAKI, Murid::JENIS_KELAMIN_PEREMPUAN]);
                $muridData[] = [
                    'nama' => $faker->name($jenisKelamin === Murid::JENIS_KELAMIN_LAKI ? 'male' : 'female'),
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->date('Y-m-d', '-6 years'),
                    'jenis_kelamin' => $jenisKelamin,
                    'nik' => $nik,
                    'nisn' => $nisn,
                    'kontak_wa_hp' => $wa,
                    'kontak_email' => $email,
                    'nama_ayah' => $faker->name('male'),
                    'nomor_hp_ayah' => $faker->phoneNumber,
                    'nama_ibu' => $faker->name('female'),
                    'nomor_hp_ibu' => $faker->phoneNumber,
                    'status_alumni' => false,
                    'tanggal_update_data' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $nisnToIndex[$nisn] = $i;
            }

            // Batch insert murid
            foreach (array_chunk($muridData, $muridBatchSize) as $batch) {
                DB::table('murid')->insert($batch);
            }

            // Ambil id murid yang baru diinsert berdasarkan NISN
            $nisnList = array_column($muridData, 'nisn');
            $muridBaru = DB::table('murid')->whereIn('nisn', $nisnList)->get(['id', 'nisn']);
            $nisnToId = [];
            foreach ($muridBaru as $row) {
                $nisnToId[$row->nisn] = $row->id;
            }

            // Buat data sekolah_murid
            foreach ($nisnList as $nisn) {
                $muridId = $nisnToId[$nisn] ?? null;
                if ($muridId) {
                    $sekolahMuridData[] = [
                        'id_murid' => $muridId,
                        'id_sekolah' => $sekolah->id,
                        'tahun_masuk' => $faker->numberBetween(date('Y') - 6, date('Y')),
                        'kelas' => $faker->randomElement(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']),
                        'status_kelulusan' => SekolahMurid::STATUS_LULUS_TIDAK,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Batch insert sekolah_murid
            foreach (array_chunk($sekolahMuridData, $sekolahMuridBatchSize) as $batch) {
                DB::table('sekolah_murid')->insert($batch);
            }

            $this->command->info("Murid untuk sekolah {$sekolah->nama} berhasil dibuat: {$muridCount}");
        }
    }
}
