<?php

namespace App\Livewire;

use App\Models\Murid;
use App\Models\Guru;
use App\Models\SekolahMurid;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MuridGuruCountsWidget extends Component
{
    /**
     * Placeholder untuk lazy loading
     */
    public function placeholder()
    {
        return view('livewire.placeholders.murid-guru-counts-skeleton');
    }

    /**
     * Get total counts for murid and guru
     */
    private function getCounts(): array
    {
        $totalMurid = Murid::count();
        // $muridAktif = Murid::nonAlumni()->count();
        // $muridAlumni = Murid::alumni()->count();

        $muridLulus = SekolahMurid::whereHas('sekolah.editorLists', function ($query) {
            if (Auth::user()->isPengurusBesar() || Auth::user()->isSuperuser()) {
                return;
            }
            $query->where('id_user', Auth::id());
        })
            ->where('status_kelulusan', SekolahMurid::STATUS_LULUS_YA)
            ->count();

        $muridTidakLulus = SekolahMurid::whereHas('sekolah.editorLists', function ($query) {
            if (Auth::user()->isPengurusBesar() || Auth::user()->isSuperuser()) {
                return;
            }
            $query->where('id_user', Auth::id());
        })
            ->where('status_kelulusan', SekolahMurid::STATUS_LULUS_TIDAK)
            ->count();

        $muridBelumLulus = SekolahMurid::whereHas('sekolah.editorLists', function ($query) {
            if (Auth::user()->isPengurusBesar() || Auth::user()->isSuperuser()) {
                return;
            }
            $query->where('id_user', Auth::id());
        })
            ->whereNull('status_kelulusan')
            ->count();

        $totalGuru = Guru::count();
        $guruAktif = Guru::aktif()->count();
        $guruNonAktif = Guru::tidakAktif()->count();

        $muridLakiLaki = Murid::byJenisKelamin(Murid::JENIS_KELAMIN_LAKI)->count();
        $muridPerempuan = Murid::byJenisKelamin(Murid::JENIS_KELAMIN_PEREMPUAN)->count();

        $guruLakiLaki = Guru::byJenisKelamin(Guru::JENIS_KELAMIN_LAKI)->count();
        $guruPerempuan = Guru::byJenisKelamin(Guru::JENIS_KELAMIN_PEREMPUAN)->count();

        $guruPNS = Guru::byStatusKepegawaian(Guru::STATUS_KEPEGAWAIAN_PNS)->count();
        $guruNonPNS = Guru::byStatusKepegawaian(Guru::STATUS_KEPEGAWAIAN_NON_PNS)->count();
        $guruPPPK = Guru::byStatusKepegawaian(Guru::STATUS_KEPEGAWAIAN_PPPK)->count();

        return [
            'murid' => [
                'total' => $totalMurid,
                // 'aktif' => $muridAktif,
                // 'alumni' => $muridAlumni,
                'laki_laki' => $muridLakiLaki,
                'perempuan' => $muridPerempuan,
                'lulus' => $muridLulus,
                'tidak_lulus' => $muridTidakLulus,
                'belum_lulus' => $muridBelumLulus,
            ],
            'guru' => [
                'total' => $totalGuru,
                'aktif' => $guruAktif,
                'non_aktif' => $guruNonAktif,
                'laki_laki' => $guruLakiLaki,
                'perempuan' => $guruPerempuan,
                'pns' => $guruPNS,
                'non_pns' => $guruNonPNS,
                'pppk' => $guruPPPK,
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
