<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include the new role
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'superuser',
                'pengurus_besar',
                'komisariat_daerah',
                'komisariat_wilayah',
                'guru'
            ])->default('guru')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'superuser',
                'pengurus_besar',
                'komisariat_wilayah',
                'guru'
            ])->default('guru')->change();
        });
    }
};
