<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BentukPendidikan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BentukPendidikan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bentuk_pendidikan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    /**
     * Get the sekolah that have this bentuk_pendidikan.
     */
    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'id_bentuk_pendidikan');
    }
}
