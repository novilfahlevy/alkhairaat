<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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
        return $query->whereHas('kabupaten', function ($query) {
            $query->whereHas('sekolah', function ($query) {
                $query->whereHas('editorLists', function ($q) {
                    $q->where('id_user', Auth::id());
                });
            });
        });
    }
}
