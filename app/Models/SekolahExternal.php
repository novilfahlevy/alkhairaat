<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SekolahExternal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sekolah_external';



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_jenis_sekolah',
        'id_bentuk_pendidikan',
        'nama_sekolah',
        'kota_sekolah',
    ];

    /**
     * Get the jenis_sekolah that owns the sekolah_external.
     */
    public function jenisSekolah(): BelongsTo
    {
        return $this->belongsTo(JenisSekolah::class, 'id_jenis_sekolah', 'id');
    }

    /**
     * Get the bentuk_pendidikan that owns the sekolah_external.
     */
    public function bentukPendidikan(): BelongsTo
    {
        return $this->belongsTo(BentukPendidikan::class, 'id_bentuk_pendidikan', 'id');
    }
}
