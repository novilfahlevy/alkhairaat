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
        Schema::create('jenis_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis', 20)->unique();
            $table->string('nama_jenis', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Add foreign key to sekolah table
        Schema::table('sekolah', function (Blueprint $table) {
            $table->foreignId('id_jenis_sekolah')
                ->nullable()
                ->after('jenjang')
                ->constrained('jenis_sekolah')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_jenis_sekolah');
        });

        Schema::dropIfExists('jenis_sekolah');
    }
};
