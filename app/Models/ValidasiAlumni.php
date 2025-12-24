<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $id_murid
 * @property string|null $profesi_sekarang
 * @property string|null $nama_tempat_kerja
 * @property string|null $kota_tempat_kerja
 * @property string|null $riwayat_pekerjaan
 * @property string|null $kontak_wa
 * @property string|null $kontak_email
 * @property string $update_alamat_sekarang
 * @property \Illuminate\Support\Carbon $tanggal_update_data_alumni
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_info
 * @property-read \App\Models\Murid $murid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni byKota(string $kota)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni byMurid(int $muridId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni byProfesi(string $profesi)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni recentUpdates(int $days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereKontakEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereKontakWa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereKotaTempatKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereNamaTempatKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereProfesiSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereRiwayatPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereTanggalUpdateDataAlumni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereUpdateAlamatSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidasiAlumni whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ValidasiAlumni extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'validasi_alumni';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_murid',
        'profesi_sekarang',
        'nama_tempat_kerja',
        'kota_tempat_kerja',
        'riwayat_pekerjaan',
        'kontak_wa',
        'kontak_email',
        'update_alamat_sekarang',
        'tanggal_update_data_alumni',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_update_data_alumni' => 'datetime',
        ];
    }

    /**
     * Get the murid that this validasi alumni record belongs to.
     */
    public function murid(): BelongsTo
    {
        return $this->belongsTo(Murid::class, 'id_murid');
    }

    /**
     * Scope to filter by murid
     */
    public function scopeByMurid($query, int $muridId)
    {
        return $query->where('id_murid', $muridId);
    }

    /**
     * Scope to filter by profesi
     */
    public function scopeByProfesi($query, string $profesi)
    {
        return $query->where('profesi_sekarang', 'LIKE', "%{$profesi}%");
    }

    /**
     * Scope to filter by kota
     */
    public function scopeByKota($query, string $kota)
    {
        return $query->where('kota_tempat_kerja', 'LIKE', "%{$kota}%");
    }

    /**
     * Scope to filter by recent updates
     */
    public function scopeRecentUpdates($query, int $days = 30)
    {
        return $query->where('tanggal_update_data_alumni', '>=', now()->subDays($days));
    }

    /**
     * Check if alumni data is complete
     */
    public function isComplete(): bool
    {
        return !is_null($this->profesi_sekarang) &&
               !is_null($this->nama_tempat_kerja) &&
               !is_null($this->kontak_wa) &&
               !is_null($this->kontak_email);
    }

    /**
     * Get full alumni information
     */
    public function getFullInfoAttribute(): string
    {
        return "{$this->profesi_sekarang} di {$this->nama_tempat_kerja}, {$this->kota_tempat_kerja}";
    }
}
