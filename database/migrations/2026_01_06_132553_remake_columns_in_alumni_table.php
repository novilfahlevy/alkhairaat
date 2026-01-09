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
        Schema::table('alumni', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'tahun_lulus',
                'angkatan',
                'kontak',
                'email',
                'alamat_sekarang',
                'lanjutan_studi',
                'nama_institusi',
                'jurusan',
                'pekerjaan',
                'nama_perusahaan',
                'jabatan',
                'keterangan'
            ]);

            // Add new columns
            $table->string('profesi_sekarang')->nullable();
            $table->string('nama_tempat_kerja')->nullable();
            $table->string('kota_tempat_kerja')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'profesi_sekarang',
                'nama_tempat_kerja',
                'kota_tempat_kerja'
            ]);

            // Re-add old columns
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
        });
    }
};
