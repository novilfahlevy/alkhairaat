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
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('kota_perusahaan')->nullable()->after('nama_perusahaan');
            $table->text('riwayat_pekerjaan')->nullable()->after('kota_perusahaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn('kota_perusahaan');
            $table->dropColumn('riwayat_pekerjaan');
        });
    }
};
