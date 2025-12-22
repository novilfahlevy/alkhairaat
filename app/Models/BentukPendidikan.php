<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
