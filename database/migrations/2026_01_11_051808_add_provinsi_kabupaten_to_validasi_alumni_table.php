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
        Schema::table('validasi_alumni', function (Blueprint $table) {
            $table->unsignedBigInteger('id_provinsi')->nullable()->after('update_alamat_sekarang');
            $table->unsignedBigInteger('id_kabupaten')->nullable()->after('id_provinsi');

            $table->foreign('id_provinsi')
                ->references('id')
                ->on('provinsi')
                ->onDelete('set null');

            $table->foreign('id_kabupaten')
                ->references('id')
                ->on('kabupaten')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('validasi_alumni', function (Blueprint $table) {
            $table->dropForeign(['id_provinsi']);
            $table->dropForeign(['id_kabupaten']);
            $table->dropColumn(['id_provinsi', 'id_kabupaten']);
        });
    }
};
