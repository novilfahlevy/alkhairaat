<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\Kabupaten;
use App\Models\Scopes\GuruSekolahNauanganScope;
use App\Models\Scopes\NauanganSekolahScope;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map jenis_sekolah enum values
        $jenisSekolah = [
            'RA-TK' => Sekolah::JENIS_SEKOLAH_RA_TK,
            'MI-SD' => Sekolah::JENIS_SEKOLAH_MI_SD,
            'MTS-SMP' => Sekolah::JENIS_SEKOLAH_MTS_SMP,
            'MA-SMA' => Sekolah::JENIS_SEKOLAH_MA_SMA,
            'PT' => Sekolah::JENIS_SEKOLAH_PT,
        ];

        // Map bentuk_pendidikan enum values
        $bentukUmum = Sekolah::BENTUK_PENDIDIKAN_UMUM;
        $bentukPonpes = Sekolah::BENTUK_PENDIDIKAN_PONPES;

        $sekolahData = [
            // Sulawesi Tengah - Kota Palu
            [
                'id_kabupaten' => 7271,
                'kode_sekolah' => 'ALK-001',
                'no_npsn' => '72781001',
                'nama' => 'Pesantren Alkhairaat Pusat Palu',
                'jenis_sekolah' => $jenisSekolah['PT'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0451-123456',
                'email' => 'pesantren.pusat@alkhairaat.or.id',
                'website' => 'www.alkhairaat.ac.id',
                'keterangan' => 'Pesantren pusat Alkhairaat dengan murid dari berbagai daerah',
            ],
            [
                'id_kabupaten' => 7271,
                'kode_sekolah' => 'ALK-002',
                'no_npsn' => '72781002',
                'nama' => 'SMA Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0451-234567',
                'email' => 'sma.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Atas dengan kurikulum nasional dan keislaman',
            ],
            [
                'id_kabupaten' => 7271,
                'kode_sekolah' => 'ALK-003',
                'no_npsn' => '72781003',
                'nama' => 'SMP Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MTS-SMP'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0451-345678',
                'email' => 'smp.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Pertama dengan pendidikan karakter islami',
            ],
            [
                'id_kabupaten' => 7271,
                'kode_sekolah' => 'ALK-004',
                'no_npsn' => '72781004',
                'nama' => 'Madrasah Aliyah Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0451-456789',
                'email' => 'ma.palu@alkhairaat.or.id',
                'keterangan' => 'Madrasah Aliyah dengan fokus pendidikan agama Islam',
            ],
            [
                'id_kabupaten' => 7271,
                'kode_sekolah' => 'ALK-005',
                'no_npsn' => '72781005',
                'nama' => 'SD Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['MI-SD'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0451-567890',
                'email' => 'sd.palu@alkhairaat.or.id',
                'keterangan' => 'Sekolah Dasar dengan pendidikan dasar Islam',
            ],
            [
                'id_kabupaten' => 7271,
                'kode_sekolah' => 'ALK-006',
                'no_npsn' => '72781006',
                'nama' => 'TK Alkhairaat Palu',
                'jenis_sekolah' => $jenisSekolah['RA-TK'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0451-678901',
                'email' => 'tk.palu@alkhairaat.or.id',
                'keterangan' => 'Taman Kanak-kanak dengan program pendidikan usia dini',
            ],

            // Sulawesi Tengah - Donggala
            [
                'id_kabupaten' => 7203,
                'kode_sekolah' => 'ALK-101',
                'no_npsn' => '72781101',
                'nama' => 'SMA Alkhairaat Donggala',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0452-321123',
                'email' => 'sma.donggala@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Atas di Donggala',
            ],
            [
                'id_kabupaten' => 7203,
                'kode_sekolah' => 'ALK-102',
                'no_npsn' => '72781102',
                'nama' => 'SMP Alkhairaat Donggala',
                'jenis_sekolah' => $jenisSekolah['MTS-SMP'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0452-321124',
                'email' => 'smp.donggala@alkhairaat.or.id',
                'keterangan' => 'Sekolah Menengah Pertama di Donggala',
            ],

            // Sulawesi Tengah - Banggai
            [
                'id_kabupaten' => 7201,
                'kode_sekolah' => 'ALK-201',
                'no_npsn' => '72781201',
                'nama' => 'Pesantren Alkhairaat Banggai',
                'jenis_sekolah' => $jenisSekolah['MTS-SMP'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0453-456789',
                'email' => 'pesantren.banggai@alkhairaat.or.id',
                'keterangan' => 'Pesantren Alkhairaat di Kabupaten Banggai',
            ],

            // DKI Jakarta
            [
                'id_kabupaten' => 3174,
                'kode_sekolah' => 'ALK-301',
                'no_npsn' => '31781301',
                'nama' => 'Sekolah Islam Alkhairaat Jakarta',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '021-1234567',
                'email' => 'sekolah.jakarta@alkhairaat.or.id',
                'website' => 'jakarta.alkhairaat.or.id',
                'keterangan' => 'Sekolah Islam Alkhairaat di DKI Jakarta',
            ],

            // Jawa Barat - Bandung
            [
                'id_kabupaten' => 3204,
                'kode_sekolah' => 'ALK-401',
                'no_npsn' => '32781401',
                'nama' => 'Pesantren Al-Ihsan Alkhairaat Bandung',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '022-2745678',
                'email' => 'pesantren.bandung@alkhairaat.or.id',
                'keterangan' => 'Pesantren Alkhairaat di Bandung',
            ],
            [
                'id_kabupaten' => 3204,
                'kode_sekolah' => 'ALK-402',
                'no_npsn' => '32781402',
                'nama' => 'SD Alkhairaat Bandung',
                'jenis_sekolah' => $jenisSekolah['MI-SD'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '022-2745679',
                'email' => 'sd.bandung@alkhairaat.or.id',
                'keterangan' => 'Sekolah Dasar Alkhairaat di Bandung',
            ],

            // Jawa Barat - Garut
            [
                'id_kabupaten' => 3205,
                'kode_sekolah' => 'ALK-501',
                'no_npsn' => '32781501',
                'nama' => 'Pesantren Alkhairaat Garut',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukPonpes,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0262-234567',
                'email' => 'pesantren.garut@alkhairaat.or.id',
                'keterangan' => 'Pesantren Alkhairaat dengan pendidikan terpadu',
            ],

            // Aceh
            [
                'id_kabupaten' => 1101,
                'kode_sekolah' => 'ALK-601',
                'no_npsn' => '11781601',
                'nama' => 'Sekolah Islam Alkhairaat Aceh',
                'jenis_sekolah' => $jenisSekolah['MA-SMA'],
                'bentuk_pendidikan' => $bentukUmum,
                'status' => Sekolah::STATUS_AKTIF,
                'telepon' => '0651-123456',
                'email' => 'sekolah.aceh@alkhairaat.or.id',
                'keterangan' => 'Sekolah Islam Alkhairaat di Banda Aceh',
            ],
        ];

        $successCount = 0;
        $updateCount = 0;

        foreach ($sekolahData as $data) {
            // Use updateOrCreate to avoid duplicate data
            $sekolah = Sekolah::withoutGlobalScope(NauanganSekolahScope::class)->firstOrCreate(
                ['kode_sekolah' => $data['kode_sekolah']],
                $data
            );

            if ($sekolah->wasRecentlyCreated) {
                $this->command->info("✓ Created: {$data['nama']} ({$data['kode_sekolah']})");
                $successCount++;
            } else {
                $this->command->line("→ Updated: {$data['nama']} ({$data['kode_sekolah']})");
                $updateCount++;
            }
        }

        $this->command->newLine();
        $this->command->info("Sekolah seeder completed successfully!");
        $this->command->info("Created: {$successCount}, Updated: {$updateCount}, Total: " . ($successCount + $updateCount));
    }
}
