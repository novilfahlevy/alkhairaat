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
        Schema::create('sekolah_murid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_murid')->constrained('murid', 'id')->onDelete('cascade');
            $table->foreignId('id_sekolah')->nullable()->constrained('sekolah', 'id')->onDelete('cascade');
            $table->unsignedBigInteger('id_sekolah_external')->nullable();
            $table->foreign('id_sekolah_external')->references('id_sekolah_external')->on('sekolah_external')->onDelete('cascade');
            $table->integer('tahun_masuk');
            $table->integer('tahun_keluar')->nullable();
            $table->integer('tahun_mutasi_masuk')->nullable();
            $table->text('alasan_mutasi_masuk')->nullable();
            $table->integer('tahun_mutasi_keluar')->nullable();
            $table->text('alasan_mutasi_keluar')->nullable();
            $table->string('kelas')->nullable();
            $table->enum('status_kelulusan', ['ya', 'tidak'])->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('id_murid');
            $table->index('id_sekolah');
            $table->index('id_sekolah_external');
            $table->index('tahun_masuk');
            $table->index(['id_sekolah', 'tahun_masuk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_murid');
    }
};
