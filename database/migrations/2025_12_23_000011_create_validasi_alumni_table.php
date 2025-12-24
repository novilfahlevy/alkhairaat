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
        Schema::create('validasi_alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_murid')->constrained('murid', 'id')->onDelete('cascade');
            $table->string('profesi_sekarang')->nullable();
            $table->string('nama_tempat_kerja')->nullable();
            $table->string('kota_tempat_kerja')->nullable();
            $table->text('riwayat_pekerjaan')->nullable();
            $table->string('kontak_wa')->nullable();
            $table->string('kontak_email')->nullable();
            $table->text('update_alamat_sekarang')->nullable();
            $table->timestamp('tanggal_update_data_alumni')->useCurrent();
            $table->timestamps();

            // Indexes for common queries
            $table->index('id_murid');
            $table->index('tanggal_update_data_alumni');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi_alumni');
    }
};
