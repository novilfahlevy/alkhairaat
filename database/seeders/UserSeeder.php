<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\User;
use App\Models\Kabupaten;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superuser if not exists
        $superUser = User::firstOrCreate(
            ['email' => 'admin@alkhairaat.or.id'],
            [
                'name' => 'Superuser PB Alkhairaat',
                'password' => 'password', // Will be hashed automatically
            ]
        );
        
        // Assign role using Spatie (won't duplicate if already exists)
        if (!$superUser->hasRole(User::ROLE_SUPERUSER)) {
            $superUser->assignRole(User::ROLE_SUPERUSER);
            $this->command->info('Superuser role assigned to: ' . $superUser->email);
        }

        // Create Komisariat Wilayah Admin for Sulawesi Tengah
        $wilayahSulteng = User::firstOrCreate(
            ['email' => 'wilayah.sulteng@alkhairaat.or.id'],
            [
                'name' => 'Admin Wilayah Sulawesi Tengah',
                'password' => 'password'
            ]
        );
        
        if (!$wilayahSulteng->hasRole(User::ROLE_KOMISARIAT_WILAYAH)) {
            $wilayahSulteng->assignRole(User::ROLE_KOMISARIAT_WILAYAH);
            $this->command->info('Wilayah role assigned to: ' . $wilayahSulteng->email);
        }

        // Create additional wilayah users for other provinces
        $additionalWilayahUsers = [
            [
                'email' => 'wilayah.aceh@alkhairaat.or.id',
                'name' => 'Admin Wilayah Aceh',
                'provinsi_name' => 'Aceh'
            ],
            [
                'email' => 'wilayah.jakarta@alkhairaat.or.id',
                'name' => 'Admin Wilayah DKI Jakarta',
                'provinsi_name' => 'DKI Jakarta'
            ],
            [
                'email' => 'wilayah.jabar@alkhairaat.or.id',
                'name' => 'Admin Wilayah Jawa Barat',
                'provinsi_name' => 'Jawa Barat'
            ],
        ];

        foreach ($additionalWilayahUsers as $wilayahData) {
            $wilayah = User::firstOrCreate(
                ['email' => $wilayahData['email']],
                [
                    'name' => $wilayahData['name'],
                    'password' => 'password'
                ]
            );
            
            if (!$wilayah->hasRole(User::ROLE_KOMISARIAT_WILAYAH)) {
                $wilayah->assignRole(User::ROLE_KOMISARIAT_WILAYAH);
                $this->command->info('Wilayah role assigned to: ' . $wilayah->email);
            }
        }

        // Create sample Komisariat Daerah users for specific kabupaten
        $daerahKabupaten = Kabupaten::whereHas('provinsi', function ($query) {
            $query->where('nama_provinsi', 'Sulawesi Tengah');
        })->limit(2)->get();

        foreach ($daerahKabupaten as $kabupaten) {
            $email = 'daerah.' . strtolower(str_replace([' ', '-'], '', $kabupaten->nama_kabupaten)) . '@alkhairaat.or.id';
            
            $daerah = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin Daerah ' . $kabupaten->nama_kabupaten,
                    'password' => 'password'
                ]
            );
            
            if (!$daerah->hasRole(User::ROLE_KOMISARIAT_DAERAH)) {
                $daerah->assignRole(User::ROLE_KOMISARIAT_DAERAH);
                $this->command->info('Daerah role assigned to: ' . $daerah->email);
            }
        }

        // Create sample akun sekolah users for each sekolah
        $sekolahList = Sekolah::all();

        foreach ($sekolahList as $sekolah) {
            $email = 'operator.' . strtolower(str_replace([' ', '-'], '', $sekolah->kode_sekolah)) . '@alkhairaat.or.id';
            
            $akunSekolah = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Operator ' . $sekolah->nama,
                    'password' => 'password'
                ]
            );
            
            if (!$akunSekolah->hasRole(User::ROLE_SEKOLAH)) {
                $akunSekolah->assignRole(User::ROLE_SEKOLAH);
                $this->command->info('Sekolah role assigned to: ' . $akunSekolah->email);
            }
        }

        $this->command->info('Users created and roles assigned successfully.');
        $this->command->info('Default credentials: email / password');
    }
}
