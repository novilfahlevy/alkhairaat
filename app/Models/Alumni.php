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
        'id_murid',
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
        'kota_perusahaan',
        'riwayat_pekerjaan',
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
     * Get the murid record for this alumni.
     */
    public function murid(): BelongsTo
    {
        return $this->belongsTo(Murid::class);
    }

    /**
     * Get the sekolah through murid relationship.
     */
    public function sekolah()
    {
        return $this->hasOneThrough(
            Sekolah::class,
            Murid::class,
            'id',           // Foreign key on murid table
            'id',           // Foreign key on sekolah table
            'id_murid',    // Local key on alumni table
            'sekolah_id'    // Local key on murid table
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
     * Scope to filter by sekolah (through murid)
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->whereHas('murid', function ($q) use ($sekolahId) {
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
     * Get nama from murid relationship
     */
    public function getNamaAttribute(): ?string
    {
        return $this->murid?->nama;
    }

    /**
     * Get NIS from murid relationship
     */
    public function getNisAttribute(): ?string
    {
        return $this->murid?->nis;
    }
}
