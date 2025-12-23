<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
