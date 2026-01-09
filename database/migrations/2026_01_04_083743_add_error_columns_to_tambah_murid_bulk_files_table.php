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
            $table->integer('processed_rows')->nullable()->after('is_finished');
            $table->integer('error_rows')->nullable()->after('processed_rows');
            $table->json('error_details')->nullable()->after('error_rows');
            $table->text('error_message')->nullable()->after('error_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tambah_murid_bulk_files', function (Blueprint $table) {
            $table->dropColumn([
                'processed_rows',
                'error_rows',
                'error_details',
                'error_message'
            ]);
        });
    }
};