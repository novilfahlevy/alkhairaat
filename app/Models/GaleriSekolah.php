<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $id_sekolah
 * @property string $image_path
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sekolah $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GaleriSekolah whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GaleriSekolah extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galeri_sekolah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_sekolah',
        'image_path',
        'deskripsi',
    ];

    /**
     * Get the sekolah that owns the galeri.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
