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
            // ->with([
            //     'kabupaten' => fn($q) => $q->whereHas('sekolah', fn($sq) => $sq->whereHas('sekolahMurid')),
            //     'kabupaten.sekolah' => fn($q) => $q->whereHas('sekolahMurid'),
            //     'kabupaten.sekolah.sekolahMurid',
            // ])
            // ->whereHas('kabupaten', fn($q) => $q->whereHas('sekolah', fn($sq) => $sq->whereHas('sekolahMurid')))
            ->get()
            ->map(function (Provinsi $provinsi) {
                $kabupatens = $provinsi->kabupaten->map(function ($kabupaten) {
                    $muridCount = $kabupaten->sekolah
                        ->flatMap(fn($sekolah) => $sekolah->sekolahMurid)
                        ->count();

                    return [
                        'id' => $kabupaten->id,
                        'nama' => $kabupaten->nama_kabupaten ?? 'Unknown',
                        'murid_count' => $muridCount,
                    ];
                });

                return [
                    'id' => $provinsi->id,
                    'nama' => $provinsi->nama_provinsi ?? 'Unknown',
                    'total_murid' => $kabupatens->sum('murid_count'),
                    'kabupatens' => $kabupatens,
                ];
            });
    }

    public function render()
    {
        return view('livewire.murid-counts-widget', [
            'provinsis' => $this->getMuridCounts(),
        ]);
    }
}
