<?php

namespace Database\Seeders;

use App\Models\Lembaga;
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
        // Create Super Admin (PB Alkhairaat) if not exists
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@alkhairaat.or.id'],
            [
                'name' => 'Super Admin PB Alkhairaat',
                'password' => 'password', // Will be hashed automatically
                'role' => User::ROLE_SUPER_ADMIN,
                'lembaga_id' => null,
            ]
        );
        
        // Assign role using Spatie (won't duplicate if already exists)
        if (!$superAdmin->hasRole(User::ROLE_SUPER_ADMIN)) {
            $superAdmin->assignRole(User::ROLE_SUPER_ADMIN);
            $this->command->info('Super Admin role assigned to: ' . $superAdmin->email);
        }

        // Create Wilayah Admin for Sulawesi Tengah
        $wilayahSulteng = User::firstOrCreate(
            ['email' => 'wilayah.sulteng@alkhairaat.or.id'],
            [
                'name' => 'Admin Wilayah Sulawesi Tengah',
                'password' => 'password',
                'role' => User::ROLE_WILAYAH,
                'lembaga_id' => null,
            ]
        );
        
        if (!$wilayahSulteng->hasRole(User::ROLE_WILAYAH)) {
            $wilayahSulteng->assignRole(User::ROLE_WILAYAH);
            $this->command->info('Wilayah role assigned to: ' . $wilayahSulteng->email);
        }

        // Assign kabupaten to wilayah user (all kabupaten in Sulawesi Tengah)
        $kabupatenSulteng = Kabupaten::whereHas('provinsi', function ($query) {
            $query->where('nama_provinsi', 'Sulawesi Tengah');
        })->get();

        if ($kabupatenSulteng->isNotEmpty()) {
            // Sync kabupaten (will not duplicate)
            $wilayahSulteng->kabupaten()->sync($kabupatenSulteng->pluck('id'));
            $this->command->info('Assigned ' . $kabupatenSulteng->count() . ' kabupaten to wilayah user');
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
                    'password' => 'password',
                    'role' => User::ROLE_WILAYAH,
                    'lembaga_id' => null,
                ]
            );
            
            if (!$wilayah->hasRole(User::ROLE_WILAYAH)) {
                $wilayah->assignRole(User::ROLE_WILAYAH);
                $this->command->info('Wilayah role assigned to: ' . $wilayah->email);
            }

            // Assign kabupaten for this provinsi
            $kabupatenProvinsi = Kabupaten::whereHas('provinsi', function ($query) use ($wilayahData) {
                $query->where('nama_provinsi', $wilayahData['provinsi_name']);
            })->get();

            if ($kabupatenProvinsi->isNotEmpty()) {
                $wilayah->kabupaten()->sync($kabupatenProvinsi->pluck('id'));
                $this->command->info("Assigned {$kabupatenProvinsi->count()} kabupaten to {$wilayah->name}");
            }
        }

        // Create sample Sekolah users for each lembaga
        $lembagaList = Lembaga::all();

        foreach ($lembagaList as $lembaga) {
            $email = 'operator.' . strtolower(str_replace([' ', '-'], '', $lembaga->kode_lembaga)) . '@alkhairaat.or.id';
            
            $sekolah = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Operator ' . $lembaga->nama,
                    'password' => 'password',
                    'role' => User::ROLE_SEKOLAH,
                    'lembaga_id' => $lembaga->id,
                ]
            );
            
            if (!$sekolah->hasRole(User::ROLE_SEKOLAH)) {
                $sekolah->assignRole(User::ROLE_SEKOLAH);
                $this->command->info('Sekolah role assigned to: ' . $sekolah->email);
            }
        }

        $this->command->info('Users created and roles assigned successfully.');
        $this->command->info('Default credentials: email / password');
    }
}
