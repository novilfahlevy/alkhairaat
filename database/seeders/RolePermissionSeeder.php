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
        // Create roles
        Role::create(['name' => User::ROLE_SUPERUSER]);
        Role::create(['name' => User::ROLE_PENGURUS_BESAR]);
        Role::create(['name' => User::ROLE_KOMISARIAT_DAERAH]);
        Role::create(['name' => User::ROLE_KOMISARIAT_WILAYAH]);
        Role::create(['name' => User::ROLE_GURU]);
    }
}