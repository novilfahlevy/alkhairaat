<?php

namespace App\Livewire;

use App\Models\Sekolah;
use Livewire\Component;

class SekolahCountsWidget extends Component
{
    /**
     * Placeholder untuk lazy loading
     */
    public function placeholder()
    {
        return view('livewire.placeholders.sekolah-counts-skeleton');
    }

    /**
     * Get total counts for sekolah by status and jenis sekolah
     */
    private function getCounts(): array
    {
        $totalSekolah = Sekolah::count();
        $sekolahAktif = Sekolah::aktif()->count();
        $sekolahTidakAktif = Sekolah::where('status', Sekolah::STATUS_TIDAK_AKTIF)->count();

        $raTk = Sekolah::where('jenis_sekolah', Sekolah::JENIS_SEKOLAH_RA_TK)->count();
        $miSd = Sekolah::where('jenis_sekolah', Sekolah::JENIS_SEKOLAH_MI_SD)->count();
        $mtsSmp = Sekolah::where('jenis_sekolah', Sekolah::JENIS_SEKOLAH_MTS_SMP)->count();
        $maSma = Sekolah::where('jenis_sekolah', Sekolah::JENIS_SEKOLAH_MA_SMA)->count();
        $pt = Sekolah::where('jenis_sekolah', Sekolah::JENIS_SEKOLAH_PT)->count();

        $sekolahUmum = Sekolah::where('bentuk_pendidikan', Sekolah::BENTUK_PENDIDIKAN_UMUM)->count();
        $sekolahPonpes = Sekolah::where('bentuk_pendidikan', Sekolah::BENTUK_PENDIDIKAN_PONPES)->count();

        return [
            'total' => $totalSekolah,
            'aktif' => $sekolahAktif,
            'tidak_aktif' => $sekolahTidakAktif,
            'jenis' => [
                'ra_tk' => $raTk,
                'mi_sd' => $miSd,
                'mts_smp' => $mtsSmp,
                'ma_sma' => $maSma,
                'pt' => $pt,
            ],
            'bentuk' => [
                'umum' => $sekolahUmum,
                'ponpes' => $sekolahPonpes,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.sekolah-counts-widget', [
            'counts' => $this->getCounts(),
        ]);
    }
}
