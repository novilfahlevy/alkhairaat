<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sekolah extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sekolah';

    /**
     * Status constants
     */
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_TIDAK_AKTIF = 'tidak_aktif';

    /**
     * Available status options
     *
     * @var array<string>
     */
    public const STATUS_OPTIONS = [
        self::STATUS_AKTIF,
        self::STATUS_TIDAK_AKTIF,
    ];

    /**
     * Status labels
     *
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_sekolah',
        'nama',
        'id_jenis_sekolah',
        'status',
        'id_kabupaten',
        'kecamatan',
        'alamat',
        'telepon',
        'email',
        'keterangan',
    ];

    /**
     * Get the jenis_sekolah that this sekolah belongs to.
     */
    public function jenisSekolah(): BelongsTo
    {
        return $this->belongsTo(JenisSekolah::class, 'id_jenis_sekolah');
    }

    /**
     * Get the users that belong to this sekolah.
     */

    /**
     * Get the santri that belong to this sekolah.
     */
    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    /**
     * Get the kabupaten that owns this sekolah.
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten');
    }

    /**
     * Get the provinsi name through kabupaten relationship.
     */
    public function getProvinsiAttribute()
    {
        return $this->kabupaten?->provinsi?->nama_provinsi;
    }

    /**
     * Scope to filter active sekolah
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Check if sekolah is active
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    /**
     * Get the editor lists associated with the sekolah.
     */
    public function editorLists()
    {
        return $this->hasMany(EditorList::class, 'id_sekolah');
    }
}
