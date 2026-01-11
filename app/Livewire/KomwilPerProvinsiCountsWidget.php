<?php

namespace App\Livewire;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Collection;

class KomwilPerProvinsiCountsWidget extends Component
{
    /**
     * Placeholder untuk lazy loading
     */
    public function placeholder()
    {
        return view('livewire.placeholders.komwil-per-provinsi-counts-skeleton');
    }

    /**
     * Get komwil counts per provinsi dan komda counts per kabupaten
     * Hanya menampilkan provinsi/kabupaten yang memiliki sekolah di bawah naungan user.
     */
    private function getKomwilKomdaCounts(): Collection
    {
        // 1. Ambil semua ID Sekolah yang bisa diakses oleh user saat ini.
        $accessibleSekolahIds = Sekolah::query()->pluck('id');

        if ($accessibleSekolahIds->isEmpty()) {
            return collect();
        }

        // 2. Ambil ID Kabupaten unik yang memiliki sekolah-sekolah tersebut
        $accessibleKabupatenIds = Sekolah::whereIn('id', $accessibleSekolahIds)
            ->distinct()
            ->pluck('id_kabupaten');

        // 3. Ambil ID Provinsi unik yang memiliki kabupaten-kabupaten tersebut
        $accessibleProvinsiIds = Kabupaten::whereIn('id', $accessibleKabupatenIds)
            ->distinct()
            ->pluck('id_provinsi');

        // 4. Query Provinsi dan eager load data dengan constraint ID
        return Provinsi::naungan()
            ->whereIn('id', $accessibleProvinsiIds)
            ->with([
                'kabupaten' => function ($query) use ($accessibleKabupatenIds) {
                    $query->whereIn('id', $accessibleKabupatenIds);
                },
            ])
            ->get()
            ->map(function (Provinsi $provinsi) use ($accessibleSekolahIds) {
                // Hitung jumlah Komwil yang menaungi sekolah di provinsi ini
                $komwilCount = User::query()
                    ->role(User::ROLE_KOMISARIAT_WILAYAH)
                    ->whereHas('editorLists', function ($query) use ($provinsi, $accessibleSekolahIds) {
                        $query->whereIn('id_sekolah', $accessibleSekolahIds)
                            ->whereHas('sekolah', function ($subQuery) use ($provinsi) {
                                $subQuery->whereHas('kabupaten', function ($kabuQuery) use ($provinsi) {
                                    $kabuQuery->where('id_provinsi', $provinsi->id);
                                });
                            });
                    })
                    ->distinct()
                    ->count();

                $kabupatens = $provinsi->kabupaten->map(function ($kabupaten) use ($accessibleSekolahIds) {
                    // Hitung jumlah Komda yang menaungi sekolah di kabupaten ini
                    $komdaCount = User::query()
                        ->role(User::ROLE_KOMISARIAT_DAERAH)
                        ->whereHas('editorLists', function ($query) use ($kabupaten, $accessibleSekolahIds) {
                            $query->whereIn('id_sekolah', $accessibleSekolahIds)
                                ->whereHas('sekolah', function ($subQuery) use ($kabupaten) {
                                    $subQuery->where('id_kabupaten', $kabupaten->id);
                                });
                        })
                        ->distinct()
                        ->count();

                    return [
                        'id' => $kabupaten->id,
                        'nama' => $kabupaten->nama_kabupaten ?? 'Unknown',
                        'komda_count' => $komdaCount,
                    ];
                })->filter();

                if ($kabupatens->isEmpty()) {
                    return null;
                }

                $totalKomda = $kabupatens->sum('komda_count');

                return [
                    'id' => $provinsi->id,
                    'nama' => $provinsi->nama_provinsi ?? 'Unknown',
                    'komwil_count' => $komwilCount,
                    'total_komda' => $totalKomda,
                    'kabupatens' => $kabupatens,
                ];
            })->filter();
    }

    public function render()
    {
        $data = $this->getKomwilKomdaCounts();

        return view('livewire.komwil-per-provinsi-counts-widget', [
            'provinsis' => $data,
            'totalKomwil' => $data->sum('komwil_count'),
            'totalKomda' => $data->sum('total_komda'),
        ]);
    }
}
