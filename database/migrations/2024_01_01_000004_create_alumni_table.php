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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('murid_id')->unique()->constrained('murid')->onDelete('cascade');
            $table->year('tahun_lulus');
            $table->string('angkatan')->nullable();
            $table->string('kontak')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat_sekarang')->nullable();
            $table->string('lanjutan_studi')->nullable()->comment('Jenjang pendidikan lanjutan: S1, S2, S3, D3, dll');
            $table->string('nama_institusi')->nullable()->comment('Nama universitas/institusi');
            $table->string('jurusan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('tahun_lulus');
            $table->index('angkatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
