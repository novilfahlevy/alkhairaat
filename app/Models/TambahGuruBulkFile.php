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
class TambahGuruBulkFile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tambah_guru_bulk_files';

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
    ];

    protected static function booted(): void
    {
        static::deleting(function (self $bulkFile) {
            if ($bulkFile->file_path && Storage::disk('local')->exists($bulkFile->file_path)) {
                Storage::disk('local')->delete($bulkFile->file_path);
            }
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_finished' => 'boolean',
        ];
    }

    /**
     * Get the sekolah that this bulk file belongs to.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
