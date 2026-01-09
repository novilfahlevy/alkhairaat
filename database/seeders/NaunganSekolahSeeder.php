<?php

namespace Database\Seeders;

use App\Models\EditorList;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\Scopes\NauanganSekolahScope;
use App\Models\User;
use Illuminate\Database\Seeder;

class NaunganSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding EditorList for user naungan...');

        // 1. Komisariat Wilayah - menaungi semua sekolah di provinsi mereka
        $this->seedKomisariatWilayah();

        // 2. Komisariat Daerah - menaungi semua sekolah di kabupaten mereka
        $this->seedKomisariatDaerah();

        // 3. Akun Sekolah - menaungi satu sekolah saja
        $this->seedAkunSekolah();

        $this->command->info('EditorList seeding completed successfully!');
    }

    /**
     * Seed EditorList untuk Komisariat Wilayah
     */
    private function seedKomisariatWilayah(): void
    {
        $this->command->info('Seeding Komisariat Wilayah naungan...');

        // Mapping user Komwil dengan provinsi mereka
        $komwilMapping = [
            'wilayah.sulteng@alkhairaat.or.id' => 'Sulawesi Tengah',
            'wilayah.aceh@alkhairaat.or.id' => 'Aceh',
            'wilayah.jakarta@alkhairaat.or.id' => 'DKI Jakarta',
            'wilayah.jabar@alkhairaat.or.id' => 'Jawa Barat',
        ];

        foreach ($komwilMapping as $email => $provinsiName) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->command->warn("User {$email} not found, skipping...");
                continue;
            }

            // Ambil provinsi
            $provinsi = Provinsi::where('nama_provinsi', $provinsiName)->first();

            if (!$provinsi) {
                $this->command->warn("Provinsi {$provinsiName} not found, skipping...");
                continue;
            }

            // Ambil semua kabupaten di provinsi tersebut
            $kabupatenIds = Kabupaten::where('id_provinsi', $provinsi->id)->pluck('id');

            // Ambil semua sekolah di kabupaten-kabupaten tersebut
            $sekolahList = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)
                ->whereIn('id_kabupaten', $kabupatenIds)
                ->get();

            $count = 0;
            foreach ($sekolahList as $sekolah) {
                EditorList::firstOrCreate([
                    'id_user' => $user->id,
                    'id_sekolah' => $sekolah->id,
                ]);
                $count++;
            }

            $this->command->info("  → {$user->name}: {$count} sekolah di {$provinsiName}");
        }
    }

    /**
     * Seed EditorList untuk Komisariat Daerah
     */
    private function seedKomisariatDaerah(): void
    {
        $this->command->info('Seeding Komisariat Daerah naungan...');

        // Ambil semua user dengan role Komisariat Daerah
        $komDaerahUsers = User::role(User::ROLE_KOMISARIAT_DAERAH)->get();

        foreach ($komDaerahUsers as $user) {
            // Ekstrak nama kabupaten dari email
            // Format: daerah.{kabupaten}@alkhairaat.or.id
            if (!preg_match('/daerah\.([^@]+)@/', $user->email, $matches)) {
                $this->command->warn("Cannot extract kabupaten from email: {$user->email}");
                continue;
            }

            $kabupatenSlug = $matches[1];
            
            // Coba cari kabupaten berdasarkan nama yang telah dinormalisasi
            // Hapus spasi dan tanda hubung untuk pencocokan
            $kabupaten = Kabupaten::whereRaw(
                "LOWER(REPLACE(REPLACE(nama_kabupaten, ' ', ''), '-', '')) = ?",
                [strtolower($kabupatenSlug)]
            )->first();

            if (!$kabupaten) {
                $this->command->warn("Kabupaten for user {$user->email} not found, skipping...");
                continue;
            }

            // Ambil semua sekolah di kabupaten tersebut
            $sekolahList = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)
                ->where('id_kabupaten', $kabupaten->id)
                ->get();

            $count = 0;
            foreach ($sekolahList as $sekolah) {
                EditorList::firstOrCreate([
                    'id_user' => $user->id,
                    'id_sekolah' => $sekolah->id,
                ]);
                $count++;
            }

            $this->command->info("  → {$user->name}: {$count} sekolah di {$kabupaten->nama_kabupaten}");
        }
    }

    /**
     * Seed EditorList untuk Akun Sekolah
     */
    private function seedAkunSekolah(): void
    {
        $this->command->info('Seeding Akun Sekolah naungan...');

        // Ambil semua user dengan role Sekolah
        $sekolahUsers = User::role(User::ROLE_SEKOLAH)->get();

        foreach ($sekolahUsers as $user) {
            // Ekstrak kode sekolah dari email
            // Format: operator.{kode_sekolah}@alkhairaat.or.id
            if (!preg_match('/operator\.([^@]+)@/', $user->email, $matches)) {
                $this->command->warn("Cannot extract kode_sekolah from email: {$user->email}");
                continue;
            }

            $kodeSekolahSlug = $matches[1];
            
            // Cari sekolah berdasarkan kode sekolah yang telah dinormalisasi
            $sekolah = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)
                ->whereRaw(
                    "LOWER(REPLACE(REPLACE(kode_sekolah, ' ', ''), '-', '')) = ?",
                    [strtolower($kodeSekolahSlug)]
                )
                ->first();

            if (!$sekolah) {
                $this->command->warn("Sekolah for user {$user->email} not found, skipping...");
                continue;
            }

            // Buat EditorList untuk user sekolah ini
            EditorList::firstOrCreate([
                'id_user' => $user->id,
                'id_sekolah' => $sekolah->id,
            ]);

            $this->command->info("  → {$user->name}: 1 sekolah ({$sekolah->nama})");
        }
    }
}
