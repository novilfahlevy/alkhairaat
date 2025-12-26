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
        Schema::create('tambah_guru_bulk_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->foreignId('id_sekolah')->constrained('sekolah')->onDelete('cascade');
            $table->boolean('is_finished')->nullable()->default(null)->comment('null: belum diproses, true: berhasil, false: gagal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambah_guru_bulk_files');
    }
};
