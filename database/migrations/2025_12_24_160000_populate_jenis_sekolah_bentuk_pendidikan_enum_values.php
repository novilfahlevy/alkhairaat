<?php

use App\Models\Sekolah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Map old jenis_sekolah IDs to enum values
        $jenisSekolahMapping = [
            1 => Sekolah::JENIS_SEKOLAH_RA_TK,           // RA / TK
            2 => Sekolah::JENIS_SEKOLAH_MI_SD,           // MI / SD
            3 => Sekolah::JENIS_SEKOLAH_MTS_SMP,         // MTS / SMP
            4 => Sekolah::JENIS_SEKOLAH_MA_SMA,          // MA / SMA
            5 => Sekolah::JENIS_SEKOLAH_PT,              // Perguruan Tinggi PT
        ];

        // Map old bentuk_pendidikan IDs to enum values
        $bentukPendidikanMapping = [
            1 => Sekolah::BENTUK_PENDIDIKAN_UMUM,        // UMUM
            2 => Sekolah::BENTUK_PENDIDIKAN_PONPES,      // PONPES
        ];

        // Get all sekolah records that have NULL enum values
        $sekolahList = DB::table('sekolah')
            ->whereNull('jenis_sekolah')
            ->orWhereNull('bentuk_pendidikan')
            ->get();

        // Since we can't get the old FK values (they were dropped), 
        // we'll set sensible defaults based on the school name or set all to a default
        // For now, we'll set them all to the first option as a safe default
        foreach ($sekolahList as $sekolah) {
            DB::table('sekolah')
                ->where('id', $sekolah->id)
                ->update([
                    'jenis_sekolah' => $sekolah->jenis_sekolah ?? Sekolah::JENIS_SEKOLAH_MI_SD,
                    'bentuk_pendidikan' => $sekolah->bentuk_pendidikan ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                ]);
        }

        // Do the same for sekolah_external table
        $externalList = DB::table('sekolah_external')
            ->whereNull('jenis_sekolah')
            ->orWhereNull('bentuk_pendidikan')
            ->get();

        foreach ($externalList as $external) {
            DB::table('sekolah_external')
                ->where('id', $external->id)
                ->update([
                    'jenis_sekolah' => $external->jenis_sekolah ?? Sekolah::JENIS_SEKOLAH_MI_SD,
                    'bentuk_pendidikan' => $external->bentuk_pendidikan ?? Sekolah::BENTUK_PENDIDIKAN_UMUM,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all enum values back to NULL
        DB::table('sekolah')->update([
            'jenis_sekolah' => null,
            'bentuk_pendidikan' => null,
        ]);

        DB::table('sekolah_external')->update([
            'jenis_sekolah' => null,
            'bentuk_pendidikan' => null,
        ]);
    }
};
