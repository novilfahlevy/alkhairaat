<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $id_murid
 * @property int $tahun_lulus
 * @property string|null $angkatan
 * @property string|null $kontak
 * @property string|null $email
 * @property string|null $alamat_sekarang
 * @property string|null $lanjutan_studi Jenjang pendidikan lanjutan: S1, S2, S3, D3, dll
 * @property string|null $nama_institusi Nama universitas/institusi
 * @property string|null $jurusan
 * @property string|null $pekerjaan
 * @property string|null $nama_perusahaan
 * @property string|null $kota_perusahaan
 * @property string|null $riwayat_pekerjaan
 * @property string|null $jabatan
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $nama
 * @property-read string|null $nis
 * @property-read \App\Models\Murid|null $murid
 * @property-read \App\Models\Sekolah|null $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni bekerja()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni byAngkatan(string $angkatan)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni bySekolah(int $sekolahId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni byTahunLulus(int $tahun)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni melanjutkanStudi()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereAlamatSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereAngkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereIdMurid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereKotaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereLanjutanStudi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNamaInstitusi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereRiwayatPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alumni whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        'profesi_sekarang',
        'nama_tempat_kerja',
        'kota_tempat_kerja',
        'riwayat_pekerjaan',
        'id_provinsi',
        'id_kabupaten',
    ];

    /**
     * Get the murid record for this alumni.
     */
    public function murid(): BelongsTo
    {
        return $this->belongsTo(Murid::class, 'id_murid', 'id');
    }

    /**
     * Get the provinsi that this alumni belongs to.
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi');
    }

    /**
     * Get the kabupaten that this alumni belongs to.
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten');
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
            'id_sekolah'    // Local key on murid table
        );
    }

    /**
     * Scope to filter by sekolah (through murid)
     */
    public function scopeBySekolah($query, int $sekolahId)
    {
        return $query->whereHas('murid', function ($q) use ($sekolahId) {
            $q->where('id_sekolah', $sekolahId);
        });
    }

    /**
     * Get nama from murid relationship
     */
    public function getNamaAttribute(): ?string
    {
        return $this->murid?->nama;
    }

    /**
     * Get NISN from murid relationship
     */
    public function getNisnAttribute(): ?string
    {
        return $this->murid?->nisn;
    }
}
