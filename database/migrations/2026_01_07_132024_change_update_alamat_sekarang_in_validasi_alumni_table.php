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
            $table->text('update_alamat_sekarang')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('validasi_alumni', function (Blueprint $table) {
            $table->datetime('update_alamat_sekarang')->nullable()->change();
        });
    }
};
