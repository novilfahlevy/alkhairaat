<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Murid extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'murid';

    /**
     * Jenis kelamin constants
     */
    public const JENIS_KELAMIN_LAKI = 'L';
    public const JENIS_KELAMIN_PEREMPUAN = 'P';

    /**
     * Status constants
     */
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_LULUS = 'lulus';
    public const STATUS_PINDAH = 'pindah';
    public const STATUS_KELUAR = 'keluar';

    /**
     * Available jenis kelamin options
     *
     * @var array<string, string>
     */
    public const JENIS_KELAMIN_OPTIONS = [
        self::JENIS_KELAMIN_LAKI => 'Laki-laki',
        self::JENIS_KELAMIN_PEREMPUAN => 'Perempuan',
    ];

    /**
     * Available status options
     *
     * @var array<string, string>
     */
    public const STATUS_OPTIONS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_LULUS => 'Lulus',
        self::STATUS_PINDAH => 'Pindah',
        self::STATUS_KELUAR => 'Keluar',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nis',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nama_ayah',
        'nama_ibu',
        'telepon',
        'email',
        'kelas',
        'status',
        'tahun_masuk',
        'tahun_lulus',
        'foto',
        'sekolah_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tahun_masuk' => 'integer',
            'tahun_lulus' => 'integer',
        ];
    }

    /**
     * Get the sekolah that the murid belongs to.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    /**
     * Get the alumni record for this murid.
     */
    public function alumni(): HasOne
    {
        return $this->hasOne(Alumni::class);
    }

    /**
     * Scope to filter active murid
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by sekolah (backward compatibility)
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->where('sekolah_id', $sekolahId);
    }

    /**
     * Scope to filter by tahun masuk
     */
    public function scopeByTahunMasuk($query, int $tahun)
    {
        return $query->where('tahun_masuk', $tahun);
    }

    /**
     * Scope to filter by kelas
     */
    public function scopeByKelas($query, string $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Scope to filter by jenis kelamin
     */
    public function scopeByJenisKelamin($query, string $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    /**
     * Check if murid is active
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    /**
     * Check if murid has graduated
     */
    public function isLulus(): bool
    {
        return $this->status === self::STATUS_LULUS;
    }

    /**
     * Get formatted jenis kelamin
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return self::JENIS_KELAMIN_OPTIONS[$this->jenis_kelamin] ?? $this->jenis_kelamin;
    }

    /**
     * Get formatted status
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    /**
     * Get full name with NIS
     */
    public function getFullIdentityAttribute(): string
    {
        return "{$this->nis} - {$this->nama}";
    }
}
