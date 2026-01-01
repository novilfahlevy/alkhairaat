<?php

namespace App\Livewire;

use App\Models\Murid;
use App\Models\Guru;
use Livewire\Component;

class MuridGuruCountsWidget extends Component
{
    /**
     * Get total counts for murid and guru
     */
    private function getCounts(): array
    {
        $totalMurid = Murid::count();
        $muridAktif = Murid::nonAlumni()->count();
        $muridAlumni = Murid::alumni()->count();

        $totalGuru = Guru::count();
        $guruAktif = Guru::aktif()->count();
        $guruNonAktif = Guru::tidakAktif()->count();

        $muridLakiLaki = Murid::byJenisKelamin(Murid::JENIS_KELAMIN_LAKI)->count();
        $muridPerempuan = Murid::byJenisKelamin(Murid::JENIS_KELAMIN_PEREMPUAN)->count();

        $guruLakiLaki = Guru::byJenisKelamin(Guru::JENIS_KELAMIN_LAKI)->count();
        $guruPerempuan = Guru::byJenisKelamin(Guru::JENIS_KELAMIN_PEREMPUAN)->count();

        return [
            'murid' => [
                'total' => $totalMurid,
                'aktif' => $muridAktif,
                'alumni' => $muridAlumni,
                'laki_laki' => $muridLakiLaki,
                'perempuan' => $muridPerempuan,
            ],
            'guru' => [
                'total' => $totalGuru,
                'aktif' => $guruAktif,
                'non_aktif' => $guruNonAktif,
                'laki_laki' => $guruLakiLaki,
                'perempuan' => $guruPerempuan,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.murid-guru-counts-widget', [
            'counts' => $this->getCounts(),
        ]);
    }
}
