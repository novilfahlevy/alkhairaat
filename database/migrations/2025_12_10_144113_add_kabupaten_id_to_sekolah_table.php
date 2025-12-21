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
        Schema::table('sekolah', function (Blueprint $table) {
            // Add id_kabupaten foreign key
            $table->foreignId('id_kabupaten')->nullable()->after('status')->constrained('kabupaten')->onDelete('set null');
            
            // Drop old provinsi and kabupaten string columns
            $table->dropColumn(['provinsi', 'kabupaten']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            // Re-add old string columns
            $table->string('provinsi')->nullable()->after('status');
            $table->string('kabupaten')->nullable()->after('provinsi');
            
            // Drop foreign key and column
            $table->dropForeign(['id_kabupaten']);
            $table->dropColumn('id_kabupaten');
        });
    }
};
