<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add missing fields to sekolah table:
     * - no_npsn: Nomor NPSN (sekolah identifier)
     * - id_bentuk_pendidikan: Foreign key to bentuk_pendidikan
     * - website: Sekolah website
     * - nomor_rekening: Bank account number
     * - rekening_atas_nama: Account holder name
     * - bank_rekening: Bank name
     */
    public function up(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            // Add NPSN number
            $table->string('no_npsn', 20)->nullable()->unique()->after('kode_sekolah');
            
            // Add bentuk_pendidikan foreign key
            $table->foreignId('id_bentuk_pendidikan')
                ->nullable()
                ->after('id_jenis_sekolah')
                ->constrained('bentuk_pendidikan')
                ->onDelete('set null');
            
            // Add website
            $table->string('website')->nullable()->after('email');
            
            // Add banking information
            $table->string('nomor_rekening', 25)->nullable()->after('website');
            $table->string('rekening_atas_nama', 100)->nullable()->after('nomor_rekening');
            $table->string('bank_rekening', 50)->nullable()->after('rekening_atas_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropForeign(['id_bentuk_pendidikan']);
            $table->dropColumn([
                'no_npsn',
                'id_bentuk_pendidikan',
                'website',
                'nomor_rekening',
                'rekening_atas_nama',
                'bank_rekening',
            ]);
        });
    }
};
