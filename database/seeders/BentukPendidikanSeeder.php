<?php

namespace Database\Seeders;

use App\Models\BentukPendidikan;
use Illuminate\Database\Seeder;

class BentukPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bentukPendidikanData = [
            [
                'nama' => 'UMUM',
                'deskripsi' => 'Lembaga pendidikan umum/reguler',
            ],
            [
                'nama' => 'PONPES',
                'deskripsi' => 'Lembaga pendidikan pesantren dengan fokus pendidikan keagamaan',
            ],
        ];

        foreach ($bentukPendidikanData as $data) {
            BentukPendidikan::updateOrCreate(
                ['nama' => $data['nama']],
                ['deskripsi' => $data['deskripsi']]
            );
            $this->command->info("✓ Bentuk Pendidikan '{$data['nama']}' created/updated");
        }

        $this->command->info('Bentuk Pendidikan seeder completed successfully.');
    }
}
