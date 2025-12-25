<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $id_murid
 * @property int|null $id_sekolah
 * @property int|null $id_sekolah_external
 * @property int $tahun_masuk
 * @property int|null $tahun_keluar
 * @property int|null $tahun_mutasi_masuk
 * @property string|null $alasan_mutasi_masuk
 * @property int|null $tahun_mutasi_keluar
 * @property string|null $alasan_mutasi_keluar
 * @property string|null $kelas
 * @property string|null $status_kelulusan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status_kelulusan_label
 * @property-read \App\Models\Murid $murid
 * @property-read \App\Models\Sekolah|null $sekolah
 * @property-read \App\Models\SekolahExternal|null $sekolahExternal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid byMurid(int $muridId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid bySekolah(int $sekolahId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid byStatusKelulusan(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid byTahunMasuk(int $tahun)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid lulus()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereAlasanMutasiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereAlasanMutasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereIdSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereIdSekolahExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereStatusKelulusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunMutasiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereTahunMutasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SekolahMurid whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SekolahMurid extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sekolah_murid';

    /**
     * Status kelulusan constants
     */
    public const STATUS_LULUS_YA = 'ya';
    public const STATUS_LULUS_TIDAK = 'tidak';

    /**
     * Available status kelulusan options
     *
     * @var array<string, string>
     */
    public const STATUS_KELULUSAN_OPTIONS = [
        self::STATUS_LULUS_YA => 'Sudah lulus',
        self::STATUS_LULUS_TIDAK => 'Tidak lulus',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_murid',
        'id_sekolah',
        'id_sekolah_external',
        'tahun_masuk',
        'tahun_keluar',
        'tahun_mutasi_masuk',
        'alasan_mutasi_masuk',
        'tahun_mutasi_keluar',
        'alasan_mutasi_keluar',
        'kelas',
        'status_kelulusan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun_masuk' => 'integer',
            'tahun_keluar' => 'integer',
            'tahun_mutasi_masuk' => 'integer',
            'tahun_mutasi_keluar' => 'integer',
        ];
    }

    /**
     * Get the murid that this sekolah_murid record belongs to.
     */
    public function murid(): BelongsTo
    {
        return $this->belongsTo(Murid::class, 'id_murid');
    }

    /**
     * Get the sekolah that this sekolah_murid record belongs to.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    /**
     * Get the sekolah external that this sekolah_murid record belongs to.
     */
    public function sekolahExternal(): BelongsTo
    {
        return $this->belongsTo(SekolahExternal::class, 'id_sekolah_external', 'id');
    }

    /**
     * Scope to filter by murid
     */
    public function scopeByMurid($query, int $muridId)
    {
        return $query->where('id_murid', $muridId);
    }

    /**
     * Scope to filter by sekolah
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->where('id_sekolah', $sekolahId);
    }

    /**
     * Scope to filter by tahun masuk
     */
    public function scopeByTahunMasuk($query, int $tahun)
    {
        return $query->where('tahun_masuk', $tahun);
    }

    /**
     * Scope to filter by status kelulusan
     */
    public function scopeByStatusKelulusan($query, string $status)
    {
        return $query->where('status_kelulusan', $status);
    }

    /**
     * Scope to filter graduates
     */
    public function scopeLulus($query)
    {
        return $query->where('status_kelulusan', self::STATUS_LULUS_YA);
    }

    /**
     * Check if murid graduated from this sekolah
     */
    public function isLulus(): bool
    {
        return $this->status_kelulusan === self::STATUS_LULUS_YA;
    }

    /**
     * Get formatted status kelulusan
     */
    public function getStatusKelulusanLabelAttribute(): string
    {
        return self::STATUS_KELULUSAN_OPTIONS[$this->status_kelulusan] ?? $this->status_kelulusan;
    }

    /**
     * Check if murid has a mutation record
     */
    public function hasMutasi(): bool
    {
        return $this->tahun_mutasi_masuk !== null || $this->tahun_mutasi_keluar !== null;
    }
}
