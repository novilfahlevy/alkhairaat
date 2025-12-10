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
        'provinsi_id',
    ];

    /**
     * Get the provinsi that owns this kabupaten.
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Get all users (wilayah) that manage this kabupaten.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_kabupaten');
    }

    /**
     * Get all lembaga in this kabupaten.
     */
    public function lembaga(): HasMany
    {
        return $this->hasMany(Lembaga::class);
    }

    /**
     * Get the nama attribute as alias for nama_kabupaten.
     */
    public function getNamaAttribute(): ?string
    {
        return $this->nama_kabupaten;
    }
}
