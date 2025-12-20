<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->hasMany(Kabupaten::class);
    }

    /**
     * Get all sekolah in this provinsi.
     */
    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class);
    }
}
