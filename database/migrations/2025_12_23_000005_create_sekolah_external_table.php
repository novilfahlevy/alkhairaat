<?php

use App\Models\SekolahExternal;
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
            $table->id();
            $table->enum('jenis_sekolah', array_keys(SekolahExternal::JENIS_SEKOLAH_OPTIONS))->nullable();
            $table->enum('bentuk_pendidikan', array_keys(SekolahExternal::BENTUK_PENDIDIKAN_OPTIONS))->nullable();
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
