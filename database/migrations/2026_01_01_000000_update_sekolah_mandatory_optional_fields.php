<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update sekolah table to enforce mandatory and optional field requirements:
     * 
     * MANDATORY (NOT NULL):
     * - kode_sekolah
     * - no_npsn
     * - nama
     * - status
     * - jenis_sekolah
     * - bentuk_pendidikan
     * - id_kabupaten
     * 
     * OPTIONAL (NULLABLE):
     * - telepon
     * - email
     * - website
     * - nomor_rekening
     * - rekening_atas_nama
     * - bank_rekening
     * - keterangan
     */
    public function up(): void
    {
        // First, handle NULL values in mandatory fields before applying NOT NULL constraints
        
        // For no_npsn: generate unique values using id to avoid duplicate key violation
        $nullNpsn = DB::table('sekolah')->whereNull('no_npsn')->get();
        foreach ($nullNpsn as $record) {
            DB::table('sekolah')->where('id', $record->id)->update(['no_npsn' => 'NPSN-' . $record->id]);
        }
        
        DB::table('sekolah')->whereNull('nama')->update(['nama' => 'Sekolah Tanpa Nama']);
        DB::table('sekolah')->whereNull('status')->update(['status' => 'aktif']);
        DB::table('sekolah')->whereNull('jenis_sekolah')->update(['jenis_sekolah' => 'RA / TK']);
        DB::table('sekolah')->whereNull('bentuk_pendidikan')->update(['bentuk_pendidikan' => 'UMUM']);
        DB::table('sekolah')->whereNull('id_kabupaten')->update(['id_kabupaten' => 1]); // Adjust as needed

        Schema::table('sekolah', function (Blueprint $table) {
            // First, drop the foreign key constraint that allows SET NULL
            $table->dropForeign(['id_kabupaten']);
            
            // Recreate foreign key with ON DELETE RESTRICT to enforce referential integrity
            $table->foreign('id_kabupaten')
                ->references('id')
                ->on('kabupaten')
                ->onDelete('restrict');
        });

        Schema::table('sekolah', function (Blueprint $table) {
            // Make mandatory fields NOT NULL
            $table->string('no_npsn', 20)->nullable(false)->change();
            $table->string('nama')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->enum('jenis_sekolah', ['RA / TK', 'MI / SD', 'MTS / SMP', 'MA / SMA', 'Perguruan Tinggi PT'])
                ->nullable(false)->change();
            $table->enum('bentuk_pendidikan', ['UMUM', 'PONPES'])
                ->nullable(false)->change();
            $table->unsignedBigInteger('id_kabupaten')->nullable(false)->change();

            // Ensure optional fields are nullable (they already are, but being explicit)
            $table->string('telepon')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('website')->nullable()->change();
            $table->string('nomor_rekening', 25)->nullable()->change();
            $table->string('rekening_atas_nama', 100)->nullable()->change();
            $table->string('bank_rekening', 50)->nullable()->change();
            $table->text('keterangan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            // Revert mandatory fields back to nullable
            $table->string('no_npsn', 20)->nullable()->change();
            $table->string('nama')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->enum('jenis_sekolah', ['RA / TK', 'MI / SD', 'MTS / SMP', 'MA / SMA', 'Perguruan Tinggi PT'])
                ->nullable()->change();
            $table->enum('bentuk_pendidikan', ['UMUM', 'PONPES'])
                ->nullable()->change();
            $table->unsignedBigInteger('id_kabupaten')->nullable()->change();
        });

        Schema::table('sekolah', function (Blueprint $table) {
            // Revert foreign key constraint back to SET NULL
            $table->dropForeign(['id_kabupaten']);
            
            $table->foreign('id_kabupaten')
                ->references('id')
                ->on('kabupaten')
                ->onDelete('set null');
        });

        // Optional fields remain nullable - handled by the first Schema::table call
        Schema::table('sekolah', function (Blueprint $table) {
            $table->string('telepon')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('website')->nullable()->change();
            $table->string('nomor_rekening', 25)->nullable()->change();
            $table->string('rekening_atas_nama', 100)->nullable()->change();
            $table->string('bank_rekening', 50)->nullable()->change();
            $table->text('keterangan')->nullable()->change();
        });
    }
};
