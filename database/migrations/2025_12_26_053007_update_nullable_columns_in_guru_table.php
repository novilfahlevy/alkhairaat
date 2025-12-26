<?php

use App\Models\Guru;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change(); // Kolom yang wajib (not nullable)
            $table->string('nik', 16)->nullable(false)->change(); // Kolom yang wajib (not nullable)
            $table->enum('status_kepegawaian', array_keys(Guru::STATUS_KEPEGAWAIAN_OPTIONS))->nullable(false)->change();

            // Kolom yang dibuat nullable (opsional), kecuali nama dan nik
            $table->string('nama_gelar_depan')->nullable()->change();
            $table->string('nama_gelar_belakang')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->enum('status_perkawinan', ['lajang', 'menikah'])->nullable()->change();
            $table->enum('status_kepegawaian', ['PNS', 'Non PNS', 'PPPK'])->nullable()->change();
            $table->string('npk')->nullable()->change();
            $table->string('nuptk')->nullable()->change();
            $table->string('kontak_wa_hp')->nullable()->change();
            $table->string('kontak_email')->nullable()->change();
            $table->string('nomor_rekening')->nullable()->change();
            $table->string('rekening_atas_nama')->nullable()->change();
            $table->string('bank_rekening')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change();
            $table->string('nik', 16)->nullable()->change();
            $table->enum('status_kepegawaian', array_keys(Guru::STATUS_KEPEGAWAIAN_OPTIONS))->nullable()->change();
            $table->string('nama_gelar_depan')->nullable()->change();
            $table->string('nama_gelar_belakang')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->enum('status_perkawinan', array_keys(\App\Models\Guru::STATUS_PERKAWINAN_OPTIONS))->nullable()->change();
            $table->enum('status_kepegawaian', array_keys(\App\Models\Guru::STATUS_KEPEGAWAIAN_OPTIONS))->nullable()->change();
            $table->string('npk')->nullable()->change();
            $table->string('nuptk')->nullable()->change();
            $table->string('kontak_wa_hp')->nullable()->change();
            $table->string('kontak_email')->nullable()->change();
            $table->string('nomor_rekening')->nullable()->change();
            $table->string('rekening_atas_nama')->nullable()->change();
            $table->string('bank_rekening')->nullable()->change();
        });
    }
};
