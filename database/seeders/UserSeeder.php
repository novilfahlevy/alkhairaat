<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use App\Models\User;
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
        }

        // Create Wilayah Admin if not exists
        $wilayah = User::firstOrCreate(
            ['email' => 'wilayah.sulteng@alkhairaat.or.id'],
            [
                'name' => 'Admin Wilayah Sulawesi Tengah',
                'password' => 'password',
                'role' => User::ROLE_WILAYAH,
                'lembaga_id' => null,
            ]
        );
        
        if (!$wilayah->hasRole(User::ROLE_WILAYAH)) {
            $wilayah->assignRole(User::ROLE_WILAYAH);
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
            }
        }

        $this->command->info('Users created and roles assigned successfully.');
    }
}
