<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SekolahExternal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sekolah_external';

    /**
     * Jenis sekolah constants
     */
    public const JENIS_SEKOLAH_RA_TK = 'RA / TK';
    public const JENIS_SEKOLAH_MI_SD = 'MI / SD';
    public const JENIS_SEKOLAH_MTS_SMP = 'MTS / SMP';
    public const JENIS_SEKOLAH_MA_SMA = 'MA / SMA';
    public const JENIS_SEKOLAH_PT = 'Perguruan Tinggi PT';

    /**
     * Available jenis sekolah options
     *
     * @var array<string, string>
     */
    public const JENIS_SEKOLAH_OPTIONS = [
        self::JENIS_SEKOLAH_RA_TK => 'RA / TK',
        self::JENIS_SEKOLAH_MI_SD => 'MI / SD',
        self::JENIS_SEKOLAH_MTS_SMP => 'MTS / SMP',
        self::JENIS_SEKOLAH_MA_SMA => 'MA / SMA',
        self::JENIS_SEKOLAH_PT => 'Perguruan Tinggi PT',
    ];

    /**
     * Bentuk pendidikan constants
     */
    public const BENTUK_PENDIDIKAN_UMUM = 'UMUM';
    public const BENTUK_PENDIDIKAN_PONPES = 'PONPES';

    /**
     * Available bentuk pendidikan options
     *
     * @var array<string, string>
     */
    public const BENTUK_PENDIDIKAN_OPTIONS = [
        self::BENTUK_PENDIDIKAN_UMUM => 'UMUM',
        self::BENTUK_PENDIDIKAN_PONPES => 'PONPES',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jenis_sekolah',
        'bentuk_pendidikan',
        'nama_sekolah',
        'kota_sekolah',
    ];

    /**
     * Get formatted jenis sekolah
     */
    public function getJenisSekolahLabelAttribute(): string
    {
        return self::JENIS_SEKOLAH_OPTIONS[$this->jenis_sekolah] ?? $this->jenis_sekolah;
    }

    /**
     * Get formatted bentuk pendidikan
     */
    public function getBentukPendidikanLabelAttribute(): string
    {
        return self::BENTUK_PENDIDIKAN_OPTIONS[$this->bentuk_pendidikan] ?? $this->bentuk_pendidikan;
    }
}
