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
        Schema::table('murid', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['sekolah_id']);
            // Drop old columns that won't be used
            $table->dropColumn(['alamat', 'kelas', 'status', 'tahun_masuk', 'tahun_lulus', 'foto', 'sekolah_id', 'telepon', 'email']);
        });

        Schema::table('murid', function (Blueprint $table) {
            // Rename nis to nisn if not already done
            if (Schema::hasColumn('murid', 'nis') && !Schema::hasColumn('murid', 'nisn')) {
                $table->renameColumn('nis', 'nisn');
            }
        });

        Schema::table('murid', function (Blueprint $table) {
            // Add new columns based on DBML
            $table->string('kontak_wa_hp')->nullable()->after('nisn');
            $table->string('kontak_email')->nullable()->after('kontak_wa_hp');
            $table->string('nomor_hp_ayah')->nullable()->after('nama_ayah');
            $table->string('nomor_hp_ibu')->nullable()->after('nama_ibu');
            $table->boolean('status_alumni')->default(false)->after('nomor_hp_ibu');
            $table->timestamp('tanggal_update_data')->useCurrent()->after('status_alumni');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('murid', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn(['kontak_wa_hp', 'kontak_email', 'nomor_hp_ayah', 'nomor_hp_ibu', 'status_alumni', 'tanggal_update_data']);
        });

        Schema::table('murid', function (Blueprint $table) {
            // Rename nisn back to nis
            if (Schema::hasColumn('murid', 'nisn') && !Schema::hasColumn('murid', 'nis')) {
                $table->renameColumn('nisn', 'nis');
            }
        });

        Schema::table('murid', function (Blueprint $table) {
            // Add back old columns
            $table->text('alamat')->nullable();
            $table->string('kelas')->nullable();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');
            $table->year('tahun_masuk');
            $table->year('tahun_lulus')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('sekolah_id')->constrained('sekolah')->onDelete('cascade');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
        });
    }
};
