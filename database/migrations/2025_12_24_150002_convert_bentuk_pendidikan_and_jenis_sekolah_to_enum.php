<?php

use App\Models\Sekolah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->convertTable('sekolah', 'kode_sekolah');
        $this->convertTable('sekolah_external', 'id');
    }

    private function convertTable(string $tableName, string $afterColumn): void
    {
        // 1. Get existing foreign keys to avoid "Constraint not found" errors
        $foreignKeys = collect(Schema::getForeignKeys($tableName))->pluck('name')->toArray();

        Schema::table($tableName, function (Blueprint $table) use ($tableName, $foreignKeys, $afterColumn) {

            // --- DROP SECTION ---
            if (in_array("{$tableName}_id_jenis_sekolah_foreign", $foreignKeys)) {
                $table->dropForeign(["id_jenis_sekolah"]);
            }
            if (in_array("{$tableName}_id_bentuk_pendidikan_foreign", $foreignKeys)) {
                $table->dropForeign(["id_bentuk_pendidikan"]);
            }

            if (Schema::hasColumn($tableName, 'id_jenis_sekolah')) {
                $table->dropColumn('id_jenis_sekolah');
            }
            if (Schema::hasColumn($tableName, 'id_bentuk_pendidikan')) {
                $table->dropColumn('id_bentuk_pendidikan');
            }

            // --- ADD SECTION (The fix for your current error) ---
            if (!Schema::hasColumn($tableName, 'jenis_sekolah')) {
                $table->enum('jenis_sekolah', array_keys(Sekolah::JENIS_SEKOLAH_OPTIONS))
                    ->nullable()
                    ->after($afterColumn);
            }

            if (!Schema::hasColumn($tableName, 'bentuk_pendidikan')) {
                $table->enum('bentuk_pendidikan', array_keys(Sekolah::BENTUK_PENDIDIKAN_OPTIONS))
                    ->nullable()
                    ->after('jenis_sekolah');
            }
        });
    }

    public function down(): void
    {
        $this->revertTable('sekolah', 'jenjang');
        $this->revertTable('sekolah_external', 'id');
    }

    private function revertTable(string $tableName, string $afterColumn): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($tableName, $afterColumn) {
            // 1. Clean up the enum columns first
            if (Schema::hasColumn($tableName, 'jenis_sekolah')) {
                $table->dropColumn('jenis_sekolah');
            }
            if (Schema::hasColumn($tableName, 'bentuk_pendidikan')) {
                $table->dropColumn('bentuk_pendidikan');
            }

            // 2. Safely recreate id_jenis_sekolah
            if (!Schema::hasColumn($tableName, 'id_jenis_sekolah')) {
                $column = $table->foreignId('id_jenis_sekolah')->nullable();

                // Only use ->after() if the reference column actually exists
                if (Schema::hasColumn($tableName, $afterColumn)) {
                    $column->after($afterColumn);
                }

                $column->constrained('jenis_sekolah')->onDelete('set null');
            }

            // 3. Safely recreate id_bentuk_pendidikan
            if (!Schema::hasColumn($tableName, 'id_bentuk_pendidikan')) {
                $column = $table->foreignId('id_bentuk_pendidikan')->nullable();

                if (Schema::hasColumn($tableName, 'id_jenis_sekolah')) {
                    $column->after('id_jenis_sekolah');
                }

                $column->constrained('bentuk_pendidikan')->onDelete('set null');
            }
        });
    }
};
