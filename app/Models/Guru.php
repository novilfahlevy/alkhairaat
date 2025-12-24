<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guru';

    /**
     * Status constants
     */
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_TIDAK = 'tidak';

    /**
     * Available status options
     *
     * @var array<string, string>
     */
    public const STATUS_OPTIONS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_TIDAK => 'Tidak Aktif',
    ];

    /**
     * Jenis kelamin constants
     */
    public const JENIS_KELAMIN_LAKI = 'L';
    public const JENIS_KELAMIN_PEREMPUAN = 'P';

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
     * Status perkawinan constants
     */
    public const STATUS_PERKAWINAN_LAJANG = 'lajang';
    public const STATUS_PERKAWINAN_MENIKAH = 'menikah';

    /**
     * Available status perkawinan options
     *
     * @var array<string, string>
     */
    public const STATUS_PERKAWINAN_OPTIONS = [
        self::STATUS_PERKAWINAN_LAJANG => 'Lajang',
        self::STATUS_PERKAWINAN_MENIKAH => 'Menikah',
    ];

    /**
     * Status kepegawaian constants
     */
    public const STATUS_KEPEGAWAIAN_PNS = 'PNS';
    public const STATUS_KEPEGAWAIAN_NON_PNS = 'Non PNS';
    public const STATUS_KEPEGAWAIAN_PPPK = 'PPPK';

    /**
     * Available status kepegawaian options
     *
     * @var array<string, string>
     */
    public const STATUS_KEPEGAWAIAN_OPTIONS = [
        self::STATUS_KEPEGAWAIAN_PNS => 'PNS',
        self::STATUS_KEPEGAWAIAN_NON_PNS => 'Non PNS',
        self::STATUS_KEPEGAWAIAN_PPPK => 'PPPK',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'status',
        'nama_gelar_depan',
        'nama',
        'nama_gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'nik',
        'status_kepegawaian',
        'npk',
        'nuptk',
        'kontak_wa_hp',
        'kontak_email',
        'nomor_rekening',
        'rekening_atas_nama',
        'bank_rekening',
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
        ];
    }

    /**
     * Get the jabatan_guru records for this guru.
     */
    public function jabatanGuru(): HasMany
    {
        return $this->hasMany(JabatanGuru::class, 'id_guru');
    }

    /**
     * Get the alamat records for this guru.
     */
    public function alamatList(): HasMany
    {
        return $this->hasMany(Alamat::class, 'id_guru');
    }

    /**
     * Scope to filter by status
     */
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    /**
     * Scope to filter inactive
     */
    public function scopeTidakAktif($query)
    {
        return $query->where('status', self::STATUS_TIDAK);
    }

    /**
     * Scope to filter by jenis kelamin
     */
    public function scopeByJenisKelamin($query, string $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    /**
     * Scope to filter by status kepegawaian
     */
    public function scopeByStatusKepegawaian($query, string $status)
    {
        return $query->where('status_kepegawaian', $status);
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
     * Get formatted status perkawinan
     */
    public function getStatusPerkawinanLabelAttribute(): string
    {
        return self::STATUS_PERKAWINAN_OPTIONS[$this->status_perkawinan] ?? $this->status_perkawinan;
    }

    /**
     * Get formatted status kepegawaian
     */
    public function getStatusKepegawaianLabelAttribute(): string
    {
        return self::STATUS_KEPEGAWAIAN_OPTIONS[$this->status_kepegawaian] ?? $this->status_kepegawaian;
    }

    /**
     * Get full name with gelar
     */
    public function getFullNameAttribute(): string
    {
        $parts = [];
        
        if ($this->nama_gelar_depan) {
            $parts[] = $this->nama_gelar_depan;
        }
        
        $parts[] = $this->nama;
        
        if ($this->nama_gelar_belakang) {
            $parts[] = $this->nama_gelar_belakang;
        }
        
        return implode(' ', $parts);
    }

    /**
     * Get nama for display (without gelar)
     */
    public function getNamaLengkapAttribute(): string
    {
        return $this->nama;
    }

    /**
     * Check if guru is active
     */
    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    /**
     * Check if guru is PNS
     */
    public function isPNS(): bool
    {
        return $this->status_kepegawaian === self::STATUS_KEPEGAWAIAN_PNS;
    }
}
