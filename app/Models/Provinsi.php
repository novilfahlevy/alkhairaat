<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $kode_provinsi
 * @property string $nama_provinsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kabupaten> $kabupaten
 * @property-read int|null $kabupaten_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi naungan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereKodeProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereNamaProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Provinsi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provinsi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_provinsi',
        'nama_provinsi',
    ];

    /**
     * Get all kabupaten in this provinsi.
     */
    public function kabupaten(): HasMany
    {
        return $this->hasMany(Kabupaten::class, 'id_provinsi');
    }

    /**
     * Scope a query to only include provinsi that have kabupaten with sekolah edited by the current user.
     */
    public function scopeNaungan($query)
    {
        if (Auth::user()->isSuperuser() || Auth::user()->isPengurusBesar()) {
            return $query;
        }
        
        return $query->whereHas('kabupaten', function ($query) {
            $query->whereHas('sekolah', function ($query) {
                $query->whereHas('editorLists', function ($q) {
                    $q->where('id_user', Auth::id());
                });
            });
        });
    }
}
