<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiKabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding provinsi data...');
        
        // Execute provinsi.sql first
        $provinsiSqlPath = database_path('seeders/provinsi.sql');
        if (file_exists($provinsiSqlPath)) {
            $provinsiSql = file_get_contents($provinsiSqlPath);
            DB::unprepared($provinsiSql);
            $this->command->info('✓ Provinsi data seeded successfully');
        } else {
            $this->command->error('✗ provinsi.sql file not found');
            return;
        }

        $this->command->info('Seeding kabupaten data...');
        
        // Execute kabupaten.sql after provinsi
        $kabupatenSqlPath = database_path('seeders/kabupaten.sql');
        if (file_exists($kabupatenSqlPath)) {
            $kabupatenSql = file_get_contents($kabupatenSqlPath);
            DB::unprepared($kabupatenSql);
            $this->command->info('✓ Kabupaten data seeded successfully');
        } else {
            $this->command->error('✗ kabupaten.sql file not found');
            return;
        }

        $this->command->newLine();
        $this->command->info('Provinsi and Kabupaten seeder completed successfully!');
    }
}
