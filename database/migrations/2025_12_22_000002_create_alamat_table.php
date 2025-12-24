<?php

use App\Models\Alamat;
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
        Schema::create('alamat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_murid')->nullable()->constrained('murid')->onDelete('cascade');
            $table->foreignId('id_sekolah')->nullable()->constrained('sekolah')->onDelete('cascade');
            $table->foreignId('id_guru')->nullable();
            
            // Jenis alamat: asli, domisili, ayah, ibu
            $table->enum('jenis', [Alamat::JENIS_ASLI, Alamat::JENIS_DOMISILI, Alamat::JENIS_AYAH, Alamat::JENIS_IBU])->default(Alamat::JENIS_ASLI);
            
            // Lokasi details
            $table->string('provinsi', 50)->nullable();
            $table->string('kabupaten', 50)->nullable();
            $table->string('kecamatan', 50)->nullable();
            $table->string('kelurahan', 50)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kode_pos', 10)->nullable();
            
            // Alamat lengkap
            $table->text('alamat_lengkap')->nullable();
            
            // Koordinat
            $table->string('koordinat_x', 20)->nullable();
            $table->string('koordinat_y', 20)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat');
    }
};
