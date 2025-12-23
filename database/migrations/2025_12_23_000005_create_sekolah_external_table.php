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
        Schema::create('sekolah_external', function (Blueprint $table) {
            $table->id('id_sekolah_external');
            $table->foreignId('id_jenis_sekolah')->nullable()->constrained('jenis_sekolah', 'id')->nullOnDelete();
            $table->foreignId('id_bentuk_pendidikan')->nullable()->constrained('bentuk_pendidikan', 'id')->nullOnDelete();
            $table->string('nama_sekolah');
            $table->string('kota_sekolah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_external');
    }
};
