<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ProvinsiKabupatenSeeder::class,
            SekolahSeeder::class,
            SekolahExternalSeeder::class,
            NaunganSekolahSeeder::class,
            MuridSeeder::class,
            GuruSeeder::class,
        ]);
    }
}
