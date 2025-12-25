<?php

namespace App\Models;

use App\Models\Scopes\NauanganSekolahScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $kode_sekolah
 * @property string|null $jenis_sekolah
 * @property string|null $bentuk_pendidikan
 * @property string|null $no_npsn
 * @property string $nama
 * @property string $status
 * @property int|null $id_kabupaten
 * @property string|null $kecamatan
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $website
 * @property string|null $nomor_rekening
 * @property string|null $rekening_atas_nama
 * @property string|null $bank_rekening
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alamat> $alamatList
 * @property-read int|null $alamat_list_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EditorList> $editorLists
 * @property-read int|null $editor_lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GaleriSekolah> $galeri
 * @property-read int|null $galeri_count
 * @property-read string $bentuk_pendidikan_label
 * @property-read string $jenis_sekolah_label
 * @property-read mixed $provinsi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JabatanGuru> $jabatanGuru
 * @property-read int|null $jabatan_guru_count
 * @property-read \App\Models\Kabupaten|null $kabupaten
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Murid> $murid
 * @property-read int|null $murid_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SekolahMurid> $sekolahMurid
 * @property-read int|null $sekolah_murid_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah naungan()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereBankRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereBentukPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereIdKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereJenisSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereKodeSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereNoNpsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereRekeningAtasNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sekolah whereWebsite($value)
 * @mixin \Eloquent
 */
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
        static::addGlobalScope(new NauanganSekolahScope());

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
     * Get the guru that belong to this sekolah (many-to-many through jabatan_guru).
     */
    public function guru(): BelongsToMany
    {
        return $this->belongsToMany(
            Guru::class,
            'jabatan_guru',
            'id_sekolah',
            'id_guru'
        )->withPivot([
            'jenis_jabatan',
            'keterangan_jabatan',
            'created_at',
            'updated_at',
        ]);
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
     * 
     * @deprecated Use global scope NauanganSekolahScope instead
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
