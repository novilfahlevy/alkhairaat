<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
     * Jenis sekolah constants
     */
    public const JENIS_SEKOLAH_RA_TK = 'RA / TK';
    public const JENIS_SEKOLAH_MI_SD = 'MI / SD';
    public const JENIS_SEKOLAH_MTS_SMP = 'MTS / SMP';
    public const JENIS_SEKOLAH_MA_SMA = 'MA / SMA';
    public const JENIS_SEKOLAH_PT = 'Perguruan Tinggi PT';

    /**
     * Available jenis sekolah options
     *
     * @var array<string, string>
     */
    public const JENIS_SEKOLAH_OPTIONS = [
        self::JENIS_SEKOLAH_RA_TK => 'RA / TK',
        self::JENIS_SEKOLAH_MI_SD => 'MI / SD',
        self::JENIS_SEKOLAH_MTS_SMP => 'MTS / SMP',
        self::JENIS_SEKOLAH_MA_SMA => 'MA / SMA',
        self::JENIS_SEKOLAH_PT => 'Perguruan Tinggi PT',
    ];

    /**
     * Bentuk pendidikan constants
     */
    public const BENTUK_PENDIDIKAN_UMUM = 'UMUM';
    public const BENTUK_PENDIDIKAN_PONPES = 'PONPES';

    /**
     * Available bentuk pendidikan options
     *
     * @var array<string, string>
     */
    public const BENTUK_PENDIDIKAN_OPTIONS = [
        self::BENTUK_PENDIDIKAN_UMUM => 'UMUM',
        self::BENTUK_PENDIDIKAN_PONPES => 'PONPES',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_sekolah',
        'no_npsn',
        'nama',
        'jenis_sekolah',
        'bentuk_pendidikan',
        'status',
        'id_kabupaten',
        'kecamatan',
        'alamat',
        'telepon',
        'email',
        'website',
        'nomor_rekening',
        'rekening_atas_nama',
        'bank_rekening',
        'keterangan',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (self $sekolah) {
            // Hapus file gambar galeri terkait jika ada
            foreach ($sekolah->galeri as $galeri) {
                if ($galeri->image_path && Storage::disk('public')->exists($galeri->image_path)) {
                    Storage::disk('public')->delete($galeri->image_path);
                }
            }
        });
    }

    /**
     * Get the alamat associated with this sekolah.
     */
    public function alamatList(): HasMany
    {
        return $this->hasMany(Alamat::class, 'id_sekolah');
    }

    /**
     * Get the murid that belong to this sekolah (many-to-many through sekolah_murid).
     */
    public function murid(): BelongsToMany
    {
        return $this->belongsToMany(
            Murid::class,
            'sekolah_murid',
            'id_sekolah',
            'id_murid'
        )->withPivot([
            'tahun_masuk',
            'tahun_keluar',
            'kelas',
            'status_kelulusan',
            'tahun_mutasi_masuk',
            'alasan_mutasi_masuk',
            'tahun_mutasi_keluar',
            'alasan_mutasi_keluar',
        ])->withTimestamps();
    }

    /**
     * Get the sekolah_murid records for this sekolah.
     */
    public function sekolahMurid(): HasMany
    {
        return $this->hasMany(SekolahMurid::class, 'id_sekolah');
    }

    /**
     * Get the jabatan_guru records for this sekolah.
     */
    public function jabatanGuru(): HasMany
    {
        return $this->hasMany(JabatanGuru::class, 'id_sekolah');
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
     * Get formatted jenis sekolah
     */
    public function getJenisSekolahLabelAttribute(): string
    {
        return self::JENIS_SEKOLAH_OPTIONS[$this->jenis_sekolah] ?? $this->jenis_sekolah;
    }

    /**
     * Get formatted bentuk pendidikan
     */
    public function getBentukPendidikanLabelAttribute(): string
    {
        return self::BENTUK_PENDIDIKAN_OPTIONS[$this->bentuk_pendidikan] ?? $this->bentuk_pendidikan;
    }

    /**
     * Scope to filter by jenis sekolah
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

    /**
     * Scope to filter sekolah under the current user's naungan.
     */
    public function scopeNaungan($query)
    {
        return $query->whereHas('editorLists', function ($q) {
            $q->where('id_user', Auth::id());
        });
    }

    /**
     * Get the galeri associated with this sekolah.
     */
    public function galeri(): HasMany
    {
        return $this->hasMany(GaleriSekolah::class, 'id_sekolah');
    }
}
