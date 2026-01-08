<?php

namespace App\Models;

use App\Models\Scopes\GuruSekolahNauanganScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $status
 * @property string|null $nama_gelar_depan
 * @property string $nama
 * @property string|null $nama_gelar_belakang
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string|null $status_perkawinan
 * @property string $nik
 * @property string|null $status_kepegawaian
 * @property string|null $npk
 * @property string|null $nuptk
 * @property string|null $kontak_wa_hp
 * @property string|null $kontak_email
 * @property string|null $nomor_rekening
 * @property string|null $rekening_atas_nama
 * @property string|null $bank_rekening
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alamat> $alamatList
 * @property-read int|null $alamat_list_count
 * @property-read string $full_name
 * @property-read string $jenis_kelamin_label
 * @property-read string $nama_lengkap
 * @property-read string $status_kepegawaian_label
 * @property-read string $status_label
 * @property-read string $status_perkawinan_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JabatanGuru> $jabatanGurus
 * @property-read int|null $jabatan_gurus_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru byJenisKelamin(string $jenisKelamin)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru byStatusKepegawaian(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru tidakAktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereBankRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereKontakEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereKontakWaHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNamaGelarBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNamaGelarDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNpk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNuptk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereRekeningAtasNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatusKepegawaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatusPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        // Mandatory fields
        'nama',
        'jenis_kelamin',
        'nik',
        'status',
        
        // Optional fields
        'npk',
        'nuptk',
        'status_kepegawaian',
        'nama_gelar_depan',
        'nama_gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'status_perkawinan',
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
    public function jabatanGurus(): HasMany
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
        return self::STATUS_PERKAWINAN_OPTIONS[$this->status_perkawinan] ?? '-';
    }

    /**
     * Get formatted status kepegawaian
     */
    public function getStatusKepegawaianLabelAttribute(): string
    {
        return self::STATUS_KEPEGAWAIAN_OPTIONS[$this->status_kepegawaian] ?? '-';
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

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new GuruSekolahNauanganScope());
    }
}
