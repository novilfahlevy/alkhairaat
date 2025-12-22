<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create roles using firstOrCreate to prevent duplicates
        Role::firstOrCreate(['name' => User::ROLE_SUPERUSER]);
        Role::firstOrCreate(['name' => User::ROLE_PENGURUS_BESAR]);
        Role::firstOrCreate(['name' => User::ROLE_KOMISARIAT_DAERAH]);
        Role::firstOrCreate(['name' => User::ROLE_KOMISARIAT_WILAYAH]);
        Role::firstOrCreate(['name' => User::ROLE_SEKOLAH]);
    }
}