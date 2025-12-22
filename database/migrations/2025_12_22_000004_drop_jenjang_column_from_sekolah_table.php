<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Removes the 'jenjang' column from sekolah table as it's replaced by
     * the relationship with jenis_sekolah table via id_jenis_sekolah foreign key.
     */
    public function up(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn('jenjang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->enum('jenjang', ['TK', 'SD', 'SMP', 'SMA', 'SMK', 'MA', 'Pesantren', 'Lainnya'])
                ->default('Lainnya')
                ->after('nama');
        });
    }
};
