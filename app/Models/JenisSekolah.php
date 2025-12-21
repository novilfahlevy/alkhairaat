<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSekolah extends Model
{
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
        return $this->hasMany(Sekolah::class);
    }
}
