<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kabupaten';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_kabupaten',
        'nama_kabupaten',
        'id_provinsi',
    ];

    /**
     * Get the provinsi that owns this kabupaten.
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi');
    }

    /**
     * Get all sekolah in this kabupaten.
     */
    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'id_kabupaten')->orderBy('id', 'desc');
    }

    /**
     * Get the nama attribute as alias for nama_kabupaten.
     */
    public function getNamaAttribute(): ?string
    {
        return $this->nama_kabupaten;
    }
}
