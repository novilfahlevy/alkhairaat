<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $kode_kabupaten
 * @property string $nama_kabupaten
 * @property int $id_provinsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $nama
 * @property-read \App\Models\Provinsi $provinsi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten naungan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereIdProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereKodeKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereNamaKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    /**
     * Scope a query to only include kabupaten that have sekolah edited by the current user.
     */
    public function scopeNaungan($query)
    {
        return $query->whereHas('sekolah', function ($query) {
            $query->whereHas('editorLists', function ($q) {
                $q->where('id_user', Auth::id());
            });
        });
    }
}
