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
        // Create Super Admin (PB Alkhairaat)
        User::create([
            'name' => 'Super Admin PB Alkhairaat',
            'email' => 'admin@alkhairaat.or.id',
            'password' => 'password', // Will be hashed automatically
            'role' => User::ROLE_SUPER_ADMIN,
            'lembaga_id' => null,
        ]);

        // Create Wilayah Admin
        User::create([
            'name' => 'Admin Wilayah Sulawesi Tengah',
            'email' => 'wilayah.sulteng@alkhairaat.or.id',
            'password' => 'password',
            'role' => User::ROLE_WILAYAH,
            'lembaga_id' => null,
        ]);

        // Create sample Sekolah users for each lembaga
        $lembagaList = Lembaga::all();

        foreach ($lembagaList as $lembaga) {
            User::create([
                'name' => 'Operator ' . $lembaga->nama,
                'email' => 'operator.' . strtolower(str_replace([' ', '-'], '', $lembaga->kode_lembaga)) . '@alkhairaat.or.id',
                'password' => 'password',
                'role' => User::ROLE_SEKOLAH,
                'lembaga_id' => $lembaga->id,
            ]);
        }
    }
}
