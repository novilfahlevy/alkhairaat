<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration safely renames the 'guru' role to 'sekolah' without losing any data.
     */
    public function up(): void
    {
        // Update the roles table - rename 'guru' to 'sekolah'
        DB::table('roles')
            ->where('name', 'guru')
            ->update(['name' => 'sekolah']);

        // Update the model_has_roles table (if there are any assignments)
        // Note: This is handled automatically through the roles table update

        // Update the enum in users table if it still exists
        // (Check if role column exists first)
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                // Update all existing 'guru' values to 'sekolah'
                DB::table('users')
                    ->where('role', 'guru')
                    ->update(['role' => 'sekolah']);

                // Update the enum definition to include 'sekolah' instead of 'guru'
                $table->enum('role', [
                    'superuser',
                    'pengurus_besar',
                    'komisariat_daerah',
                    'komisariat_wilayah',
                    'sekolah'
                ])->default('sekolah')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert roles table - rename 'sekolah' back to 'guru'
        DB::table('roles')
            ->where('name', 'sekolah')
            ->update(['name' => 'guru']);

        // Revert the enum in users table if it exists
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                // Revert all 'sekolah' values back to 'guru'
                DB::table('users')
                    ->where('role', 'sekolah')
                    ->update(['role' => 'guru']);

                // Revert the enum definition back to 'guru'
                $table->enum('role', [
                    'superuser',
                    'pengurus_besar',
                    'komisariat_daerah',
                    'komisariat_wilayah',
                    'guru'
                ])->default('guru')->change();
            });
        }
    }
};
