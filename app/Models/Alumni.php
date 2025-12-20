<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alumni';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'santri_id',
        'tahun_lulus',
        'angkatan',
        'kontak',
        'email',
        'alamat_sekarang',
        'lanjutan_studi',
        'nama_institusi',
        'jurusan',
        'pekerjaan',
        'nama_perusahaan',
        'jabatan',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun_lulus' => 'integer',
        ];
    }

    /**
     * Get the santri record for this alumni.
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Get the sekolah through santri relationship.
     */
    public function sekolah()
    {
        return $this->hasOneThrough(
            Sekolah::class,
            Santri::class,
            'id',           // Foreign key on santri table
            'id',           // Foreign key on sekolah table
            'santri_id',    // Local key on alumni table
            'sekolah_id'    // Local key on santri table
        );
    }

    /**
     * Scope to filter by tahun lulus
     */
    public function scopeByTahunLulus($query, int $tahun)
    {
        return $query->where('tahun_lulus', $tahun);
    }

    /**
     * Scope to filter by angkatan
     */
    public function scopeByAngkatan($query, string $angkatan)
    {
        return $query->where('angkatan', $angkatan);
    }

    /**
     * Scope to filter alumni who continued studying
     */
    public function scopeMelanjutkanStudi($query)
    {
        return $query->whereNotNull('lanjutan_studi')->where('lanjutan_studi', '!=', '');
    }

    /**
     * Scope to filter alumni who are working
     */
    public function scopeBekerja($query)
    {
        return $query->whereNotNull('pekerjaan')->where('pekerjaan', '!=', '');
    }

    /**
     * Scope to filter by sekolah (through santri)
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->whereHas('santri', function ($q) use ($sekolahId) {
            $q->where('sekolah_id', $sekolahId);
        });
    }

    /**
     * Check if alumni continued studying
     */
    public function isMelanjutkanStudi(): bool
    {
        return !empty($this->lanjutan_studi);
    }

    /**
     * Check if alumni is working
     */
    public function isBekerja(): bool
    {
        return !empty($this->pekerjaan);
    }

    /**
     * Get nama from santri relationship
     */
    public function getNamaAttribute(): ?string
    {
        return $this->santri?->nama;
    }

    /**
     * Get NIS from santri relationship
     */
    public function getNisAttribute(): ?string
    {
        return $this->santri?->nis;
    }
}
