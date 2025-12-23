<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_sekolah',
        'no_npsn',
        'nama',
        'id_jenis_sekolah',
        'id_bentuk_pendidikan',
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
     * Get the jenis_sekolah that this sekolah belongs to.
     */
    public function jenisSekolah(): BelongsTo
    {
        return $this->belongsTo(JenisSekolah::class, 'id_jenis_sekolah');
    }

    /**
     * Get the bentuk_pendidikan that this sekolah belongs to.
     */
    public function bentukPendidikan(): BelongsTo
    {
        return $this->belongsTo(BentukPendidikan::class, 'id_bentuk_pendidikan');
    }

    /**
     * Get the alamat associated with this sekolah.
     */
    public function alamatList(): HasMany
    {
        return $this->hasMany(Alamat::class, 'id_sekolah');
    }

    /**
     * Get the murid that belong to this sekolah.
     */
    public function murid(): HasMany
    {
        return $this->hasMany(Murid::class);
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
