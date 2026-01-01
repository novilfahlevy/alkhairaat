<?php

namespace App\Livewire;

use App\Models\Murid;
use App\Models\Provinsi;
use Livewire\Component;
use Illuminate\Support\Collection;

class MuridCountsWidget extends Component
{
    public $collapsed = [];

    /**
     * Get murid counts grouped by provinsi and kabupaten
     */
    private function getMuridCounts(): Collection
    {
        return Provinsi::query()
            ->with(['kabupaten.sekolah.sekolahMurid.murid'])
            ->get()
            ->map(function (Provinsi $provinsi) {
                $kabupatens = $provinsi->kabupaten->map(function ($kabupaten) {
                    $sekolahMurids = $kabupaten->sekolah
                        ->flatMap(fn($sekolah) => $sekolah->sekolahMurid);

                    $totalMurid = $sekolahMurids->count();
                    $alumniCount = $sekolahMurids
                        ->flatMap(fn($sm) => $sm->murid)
                        ->filter(fn($murid) => $murid->isAlumni())
                        ->unique('id')
                        ->count();

                    return [
                        'id' => $kabupaten->id,
                        'nama' => $kabupaten->nama_kabupaten ?? 'Unknown',
                        'murid_count' => $totalMurid,
                        'alumni_count' => $alumniCount,
                    ];
                });

                $totalMurid = $kabupatens->sum('murid_count');
                $totalAlumni = $kabupatens->sum('alumni_count');

                return [
                    'id' => $provinsi->id,
                    'nama' => $provinsi->nama_provinsi ?? 'Unknown',
                    'total_murid' => $totalMurid,
                    'total_alumni' => $totalAlumni,
                    'kabupatens' => $kabupatens,
                ];
            })
            ->filter(fn($p) => $p['total_murid'] > 0);
    }

    public function render()
    {
        return view('livewire.murid-counts-widget', [
            'provinsis' => $this->getMuridCounts(),
        ]);
    }
}
