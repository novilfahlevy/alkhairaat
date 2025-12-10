<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lembaga extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lembaga';

    /**
     * Jenjang constants
     */
    public const JENJANG_TK = 'TK';
    public const JENJANG_SD = 'SD';
    public const JENJANG_SMP = 'SMP';
    public const JENJANG_SMA = 'SMA';
    public const JENJANG_SMK = 'SMK';
    public const JENJANG_MA = 'MA';
    public const JENJANG_PESANTREN = 'Pesantren';
    public const JENJANG_LAINNYA = 'Lainnya';

    /**
     * Status constants
     */
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_TIDAK_AKTIF = 'tidak_aktif';

    /**
     * Available jenjang options
     *
     * @var array<string>
     */
    public const JENJANG_OPTIONS = [
        self::JENJANG_TK,
        self::JENJANG_SD,
        self::JENJANG_SMP,
        self::JENJANG_SMA,
        self::JENJANG_SMK,
        self::JENJANG_MA,
        self::JENJANG_PESANTREN,
        self::JENJANG_LAINNYA,
    ];

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
        'kode_lembaga',
        'nama',
        'jenjang',
        'status',
        'kabupaten_id',
        'kecamatan',
        'alamat',
        'telepon',
        'email',
        'keterangan',
    ];

    /**
     * Get the users that belong to this lembaga.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the santri that belong to this lembaga.
     */
    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    /**
     * Get the kabupaten that owns this lembaga.
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class);
    }

    /**
     * Get the provinsi name through kabupaten relationship.
     */
    public function getProvinsiAttribute()
    {
        return $this->kabupaten?->provinsi?->nama_provinsi;
    }

    /**
     * Scope to filter active lembaga
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Scope to filter by jenjang
     */
    public function scopeByJenjang($query, string $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    /**
     * Scope to filter by provinsi
     */
    public function scopeByProvinsi($query, string $provinsi)
    {
        return $query->where('provinsi', $provinsi);
    }

    /**
     * Scope to filter by kabupaten
     */
    public function scopeByKabupaten($query, string $kabupaten)
    {
        return $query->where('kabupaten', $kabupaten);
    }

    /**
     * Check if lembaga is active
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }
}
