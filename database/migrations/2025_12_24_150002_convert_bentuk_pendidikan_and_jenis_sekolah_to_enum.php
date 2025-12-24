<?php

use App\Models\Sekolah;
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
        // Convert sekolah table
        Schema::table('sekolah', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['id_jenis_sekolah']);
            $table->dropForeign(['id_bentuk_pendidikan']);
        });

        Schema::table('sekolah', function (Blueprint $table) {
            // Drop old foreign key columns
            $table->dropColumn(['id_jenis_sekolah', 'id_bentuk_pendidikan']);
            
            // Add enum columns
            $table->enum('jenis_sekolah', array_keys(Sekolah::JENIS_SEKOLAH_OPTIONS))
                ->nullable()
                ->after('kode_sekolah');
            
            $table->enum('bentuk_pendidikan', array_keys(Sekolah::BENTUK_PENDIDIKAN_OPTIONS))
                ->nullable()
                ->after('jenis_sekolah');
        });

        // Convert sekolah_external table
        Schema::table('sekolah_external', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['id_jenis_sekolah']);
            $table->dropForeign(['id_bentuk_pendidikan']);
        });

        Schema::table('sekolah_external', function (Blueprint $table) {
            // Drop old foreign key columns
            $table->dropColumn(['id_jenis_sekolah', 'id_bentuk_pendidikan']);
            
            // Add enum columns
            $table->enum('jenis_sekolah', array_keys(Sekolah::JENIS_SEKOLAH_OPTIONS))
                ->nullable()
                ->after('id');
            
            $table->enum('bentuk_pendidikan', array_keys(Sekolah::BENTUK_PENDIDIKAN_OPTIONS))
                ->nullable()
                ->after('jenis_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert sekolah table
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn(['jenis_sekolah', 'bentuk_pendidikan']);
            
            $table->foreignId('id_jenis_sekolah')
                ->nullable()
                ->after('jenjang')
                ->constrained('jenis_sekolah')
                ->onDelete('set null');
            
            $table->foreignId('id_bentuk_pendidikan')
                ->nullable()
                ->after('id_jenis_sekolah')
                ->constrained('bentuk_pendidikan')
                ->onDelete('set null');
        });

        // Revert sekolah_external table
        Schema::table('sekolah_external', function (Blueprint $table) {
            $table->dropColumn(['jenis_sekolah', 'bentuk_pendidikan']);
            
            $table->foreignId('id_jenis_sekolah')
                ->nullable()
                ->constrained('jenis_sekolah', 'id')
                ->nullOnDelete();
            
            $table->foreignId('id_bentuk_pendidikan')
                ->nullable()
                ->constrained('bentuk_pendidikan', 'id')
                ->nullOnDelete();
        });
    }
};
