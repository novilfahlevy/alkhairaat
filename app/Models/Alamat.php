<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alamat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alamat';



    /**
     * Jenis alamat constants
     */
    public const JENIS_ASLI = 'asli';
    public const JENIS_DOMISILI = 'domisili';
    public const JENIS_AYAH = 'ayah';
    public const JENIS_IBU = 'ibu';

    /**
     * Available jenis options
     *
     * @var array<string>
     */
    public const JENIS_OPTIONS = [
        self::JENIS_ASLI,
        self::JENIS_DOMISILI,
        self::JENIS_AYAH,
        self::JENIS_IBU,
    ];

    /**
     * Jenis labels
     *
     * @var array<string, string>
     */
    public const JENIS_LABELS = [
        self::JENIS_ASLI => 'Alamat Asli',
        self::JENIS_DOMISILI => 'Alamat Domisili',
        self::JENIS_AYAH => 'Alamat Ayah',
        self::JENIS_IBU => 'Alamat Ibu',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_murid',
        'id_sekolah',
        'id_guru',
        'jenis',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'rt',
        'rw',
        'kode_pos',
        'alamat_lengkap',
        'koordinat_x',
        'koordinat_y',
    ];

    /**
     * Get the murid that owns this alamat.
     */
    public function murid(): BelongsTo
    {
        return $this->belongsTo(Murid::class, 'id_murid');
    }

    /**
     * Get the sekolah that owns this alamat.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    /**
     * Get the user (guru) that owns this alamat.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_guru');
    }

    /**
     * Get the jenis label for this alamat.
     */
    public function getJenisLabel(): string
    {
        return self::JENIS_LABELS[$this->jenis] ?? $this->jenis;
    }

    /**
     * Scope to filter alamat by jenis
     */
    public function scopeByJenis($query, string $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * Scope to filter alamat by sekolah
     */
    public function scopeForSekolah($query, int $sekolahId)
    {
        return $query->where('id_sekolah', $sekolahId);
    }

    /**
     * Scope to filter alamat by murid
     */
    public function scopeForMurid($query, int $muridId)
    {
        return $query->where('id_murid', $muridId);
    }
}
