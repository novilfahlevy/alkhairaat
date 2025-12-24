<?php

use App\Models\JabatanGuru;
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
        Schema::create('jabatan_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->constrained('guru', 'id')->onDelete('cascade');
            $table->foreignId('id_sekolah')->constrained('sekolah', 'id')->onDelete('cascade');
            $table->enum('jenis_jabatan', array_keys(JabatanGuru::JENIS_JABATAN_OPTIONS));
            $table->text('keterangan_jabatan')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('id_guru');
            $table->index('id_sekolah');
            $table->index('jenis_jabatan');
            $table->index(['id_sekolah', 'jenis_jabatan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_guru');
    }
};
