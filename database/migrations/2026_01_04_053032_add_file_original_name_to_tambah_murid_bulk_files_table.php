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
        Schema::table('tambah_murid_bulk_files', function (Blueprint $table) {
            $table->string('file_original_name')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tambah_murid_bulk_files', function (Blueprint $table) {
            $table->dropColumn('file_original_name');
        });
    }
};
