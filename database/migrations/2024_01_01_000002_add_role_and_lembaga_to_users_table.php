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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'wilayah', 'sekolah'])->default('sekolah')->after('email');
            $table->foreignId('lembaga_id')->nullable()->after('role')->constrained('lembaga')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
            $table->dropColumn(['role', 'lembaga_id']);
        });
    }
};
