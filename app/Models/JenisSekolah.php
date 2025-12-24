<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $kode_jenis
 * @property string $nama_jenis
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereKodeJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereNamaJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisSekolah whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JenisSekolah extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_sekolah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_jenis',
        'nama_jenis',
        'deskripsi',
    ];

    /**
     * Get all sekolah of this jenis.
     */
    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'id_jenis_sekolah');
    }
}
