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
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->enum('status', array_keys(Guru::STATUS_OPTIONS))->default(Guru::STATUS_AKTIF);
            $table->string('nama_gelar_depan')->nullable();
            $table->string('nama');
            $table->string('nama_gelar_belakang')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', array_keys(Guru::JENIS_KELAMIN_OPTIONS));
            $table->enum('status_perkawinan', array_keys(Guru::STATUS_PERKAWINAN_OPTIONS))->nullable();
            $table->string('nik', 16)->nullable()->unique();
            $table->enum('status_kepegawaian', array_keys(Guru::STATUS_KEPEGAWAIAN_OPTIONS))->nullable();
            $table->string('npk')->nullable()->unique();
            $table->string('nuptk')->nullable()->unique();
            $table->string('kontak_wa_hp')->nullable();
            $table->string('kontak_email')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('rekening_atas_nama')->nullable();
            $table->string('bank_rekening')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('status');
            $table->index('jenis_kelamin');
            $table->index('status_kepegawaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
