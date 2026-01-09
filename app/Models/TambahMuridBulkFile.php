<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $file_path
 * @property int $id_sekolah
 * @property bool|null $is_finished
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sekolah $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile whereIsFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TambahMuridBulkFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TambahMuridBulkFile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tambah_murid_bulk_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'file_path',
        'file_original_name',
        'id_sekolah',
        'is_finished', // null: belum diproses, true: berhasil, false: gagal

        'processed_rows',
        'error_rows',
        'error_details',
        'error_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_finished' => 'boolean',
        'error_details' => 'array',
    ];

    /**
     * Get the error details as array.
     * This accessor will handle both JSON string and array cases.
     */
    public function getErrorDetailsArrayAttribute(): array
    {
        if (is_array($this->error_details)) {
            return $this->error_details;
        }

        // If it's a JSON string, decode it
        if (is_string($this->error_details)) {
            $decoded = json_decode($this->error_details, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // If error_details is not empty but not an array, convert it to a single error entry
        if (!empty($this->error_details)) {
            return [
                [
                    'row' => 0,
                    'nisn' => '-',
                    'nama' => '-',
                    'error' => is_string($this->error_details) ? $this->error_details : 'Unknown error'
                ]
            ];
        }

        return [];
    }

    /**
     * Check if there are any errors.
     */
    public function getHasErrorsAttribute(): bool
    {
        return !empty($this->error_details);
    }

    /**
     * Get the error count.
     */
    public function getErrorCountAttribute(): int
    {
        return count($this->error_details_array);
    }

    protected static function booted(): void
    {
        static::deleting(function (self $bulkFile) {
            if ($bulkFile->file_path && Storage::disk('local')->exists($bulkFile->file_path)) {
                Storage::disk('local')->delete($bulkFile->file_path);
            }
        });
    }

    /**
     * Get the sekolah that this bulk file belongs to.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
