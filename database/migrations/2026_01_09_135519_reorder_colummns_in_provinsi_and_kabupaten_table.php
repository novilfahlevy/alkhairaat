<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop timestamps dari tabel provinsi
        Schema::table('provinsi', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        // Drop timestamps dari tabel kabupaten
        Schema::table('kabupaten', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        // Reorder columns in kabupaten table: id, id_provinsi, nama_kabupaten
        // Menggunakan raw SQL karena Laravel tidak support column reordering
        
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            // MySQL: gunakan MODIFY COLUMN dengan AFTER
            DB::statement('ALTER TABLE kabupaten MODIFY COLUMN nama_kabupaten VARCHAR(255) AFTER id_provinsi');
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: tidak support reordering, tapi kita bisa recreate dengan urutan yang benar
            // Karena ini hanya reordering dan tidak mengubah data, kita skip untuk PostgreSQL
            // atau bisa menggunakan pendekatan create temp table, copy data, drop, rename
            
            // Untuk saat ini, kita abaikan karena PostgreSQL tidak support column reordering
            // dan ini hanya masalah estetika, tidak mempengaruhi functionality
        }
        
        // Note: Provinsi table sudah memiliki urutan yang benar (id, nama_provinsi)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan urutan ke semula: id, nama_kabupaten, id_provinsi
        
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE kabupaten MODIFY COLUMN id_provinsi BIGINT UNSIGNED NOT NULL AFTER nama_kabupaten');
        }
        
        // PostgreSQL: skip karena tidak support column reordering
    }
};
