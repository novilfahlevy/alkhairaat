<?php

namespace App\Livewire;

use App\Models\EditorList;
use App\Models\Provinsi;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Collection;

class KomwilPerProvinsiCountsWidget extends Component
{
    /**
     * Get komwil counts per provinsi
     */
    private function getKomwilCounts(): Collection
    {
        return Provinsi::query()
            ->with(['kabupaten'])
            ->get()
            ->map(function (Provinsi $provinsi) {
                // Get distinct komwil users who manage sekolah in this provinsi
                $komwilCount = User::query()
                    ->role(User::ROLE_KOMISARIAT_WILAYAH)
                    ->whereHas('editorLists', function ($query) use ($provinsi) {
                        $query->whereHas('sekolah', function ($subQuery) use ($provinsi) {
                            $subQuery->whereHas('kabupaten', function ($kabuQuery) use ($provinsi) {
                                $kabuQuery->where('id_provinsi', $provinsi->id);
                            });
                        });
                    })
                    ->distinct()
                    ->count();

                return [
                    'id' => $provinsi->id,
                    'nama' => $provinsi->nama_provinsi ?? 'Unknown',
                    'komwil_count' => $komwilCount,
                    'kabupaten_count' => $provinsi->kabupaten?->count() ?? 0,
                ];
            })
            ->sortByDesc('komwil_count')
            ->values();
    }

    public function render()
    {
        $komwilData = $this->getKomwilCounts();

        return view('livewire.komwil-per-provinsi-counts-widget', [
            'komwilData' => $komwilData,
            'totalKomwil' => $komwilData->sum('komwil_count'),
            'totalProvinsi' => $komwilData->count(),
        ]);
    }
}
