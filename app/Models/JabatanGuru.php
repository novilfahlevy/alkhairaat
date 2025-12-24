<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JabatanGuru extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jabatan_guru';

    /**
     * Jenis jabatan constants
     */
    public const JENIS_JABATAN_KEPALA_SEKOLAH = 'Kepala Sekolah';
    public const JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH = 'Wakil Kepala Sekolah';
    public const JENIS_JABATAN_GURU = 'Guru';
    public const JENIS_JABATAN_STAFF_TU = 'Staff / TU';
    public const JENIS_JABATAN_PENGASUH_ASRAMA = 'Pengasuh Asrama';

    /**
     * Available jenis jabatan options
     *
     * @var array<string, string>
     */
    public const JENIS_JABATAN_OPTIONS = [
        self::JENIS_JABATAN_KEPALA_SEKOLAH => 'Kepala Sekolah',
        self::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH => 'Wakil Kepala Sekolah',
        self::JENIS_JABATAN_GURU => 'Guru',
        self::JENIS_JABATAN_STAFF_TU => 'Staff / TU',
        self::JENIS_JABATAN_PENGASUH_ASRAMA => 'Pengasuh Asrama',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_guru',
        'id_sekolah',
        'jenis_jabatan',
        'keterangan_jabatan',
    ];

    /**
     * Get the guru that this jabatan_guru record belongs to.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    /**
     * Get the sekolah that this jabatan_guru record belongs to.
     */
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    /**
     * Scope to filter by guru
     */
    public function scopeByGuru($query, int $guruId)
    {
        return $query->where('id_guru', $guruId);
    }

    /**
     * Scope to filter by sekolah
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->where('id_sekolah', $sekolahId);
    }

    /**
     * Scope to filter by jenis jabatan
     */
    public function scopeByJenisJabatan($query, string $jenisJabatan)
    {
        return $query->where('jenis_jabatan', $jenisJabatan);
    }

    /**
     * Scope to filter kepala sekolah
     */
    public function scopeKepalaSekolah($query)
    {
        return $query->where('jenis_jabatan', self::JENIS_JABATAN_KEPALA_SEKOLAH);
    }

    /**
     * Scope to filter guru (excluding staff/administrative roles)
     */
    public function scopeGuru($query)
    {
        return $query->where('jenis_jabatan', self::JENIS_JABATAN_GURU);
    }

    /**
     * Get formatted jenis jabatan
     */
    public function getJenisJabatanLabelAttribute(): string
    {
        return self::JENIS_JABATAN_OPTIONS[$this->jenis_jabatan] ?? $this->jenis_jabatan;
    }

    /**
     * Check if this is kepala sekolah role
     */
    public function isKepalaSekolah(): bool
    {
        return $this->jenis_jabatan === self::JENIS_JABATAN_KEPALA_SEKOLAH;
    }

    /**
     * Check if this is guru role
     */
    public function isGuru(): bool
    {
        return $this->jenis_jabatan === self::JENIS_JABATAN_GURU;
    }
}
