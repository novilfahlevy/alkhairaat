<?php

namespace App\Models;

use App\Models\Scopes\MuridNauanganScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $nisn
 * @property string|null $kontak_wa_hp
 * @property string|null $kontak_email
 * @property string $nama
 * @property string|null $nik
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string|null $nama_ayah
 * @property string|null $nomor_hp_ayah
 * @property string|null $nama_ibu
 * @property string|null $nomor_hp_ibu
 * @property bool $status_alumni
 * @property \Illuminate\Support\Carbon $tanggal_update_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_name
 * @property-read string $jenis_kelamin_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SekolahMurid> $sekolahMurid
 * @property-read int|null $sekolah_murid_count
 * @property-read \App\Models\ValidasiAlumni|null $validasiAlumni
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid alumni()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid byJenisKelamin(string $jenisKelamin)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid nonAlumni()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereKontakEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereKontakWaHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNomorHpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereNomorHpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereStatusAlumni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereTanggalUpdateData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Murid whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
     * Boot the model.
     */
    protected static function booted(): void
    {
        // static::addGlobalScope(new MuridNauanganScope());
    }

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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nik',
        'nisn',
        'kontak_wa_hp',
        'kontak_email',
        'nama_ayah',
        'nomor_hp_ayah',
        'nama_ibu',
        'nomor_hp_ibu',
        'status_alumni', // ya (1, lulus), tidak (0, belum/tidak lulus)
        'tanggal_update_data',
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
            'tanggal_update_data' => 'datetime',
            'status_alumni' => 'boolean',
        ];
    }

    /**
     * Get the sekolah murid records for this murid.
     */
    public function sekolahMurid(): HasMany
    {
        return $this->hasMany(SekolahMurid::class, 'id_murid');
    }

    /**
     * Get the validasi alumni record for this murid.
     */
    public function validasiAlumni(): HasOne
    {
        return $this->hasOne(ValidasiAlumni::class, 'id_murid');
    }

    /**
     * Get the sekolah records that this murid belongs to (many-to-many through sekolah_murid).
     */
    public function sekolah(): BelongsToMany
    {
        return $this->belongsToMany(
            Sekolah::class,
            'sekolah_murid',
            'id_murid',
            'id_sekolah'
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
     * Scope to filter by jenis kelamin
     */
    public function scopeByJenisKelamin($query, string $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    /**
     * Scope to filter alumni status
     */
    public function scopeAlumni($query)
    {
        return $query->where('status_alumni', true);
    }

    /**
     * Scope to filter non-alumni
     */
    public function scopeNonAlumni($query)
    {
        return $query->where('status_alumni', false);
    }

    /**
     * Check if murid is alumni
     */
    public function isAlumni(): bool
    {
        return $this->status_alumni === true;
    }

    /**
     * Get formatted jenis kelamin
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return self::JENIS_KELAMIN_OPTIONS[$this->jenis_kelamin] ?? $this->jenis_kelamin;
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->nama;
    }
}
