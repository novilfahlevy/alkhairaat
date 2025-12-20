<?php

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
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nama');
            $table->string('nik', 16)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('kelas')->nullable();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');
            $table->year('tahun_masuk');
            $table->year('tahun_lulus')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('sekolah_id')->constrained('sekolah')->onDelete('cascade');
            $table->timestamps();

            // Indexes for common queries
            $table->index('status');
            $table->index('tahun_masuk');
            $table->index('kelas');
            $table->index(['sekolah_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
