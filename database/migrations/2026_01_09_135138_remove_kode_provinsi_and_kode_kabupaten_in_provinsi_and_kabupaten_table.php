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
        Schema::table('provinsi', function (Blueprint $table) {
            $table->dropColumn('kode_provinsi');
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->dropColumn('kode_kabupaten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            $table->string('kode_provinsi', 10)->after('nama_provinsi')->nullable();
        });

        Schema::table('kabupaten', function (Blueprint $table) {
            $table->string('kode_kabupaten', 10)->after('nama_kabupaten')->nullable();
        });
    }
};
