<?php

namespace App\Livewire;

use App\Models\Murid;
use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\Kabupaten;
use Livewire\Component;
use Illuminate\Support\Collection;

class MuridAlumniCountsByProvinceWidget extends Component
{
    public $collapsed = [];

    /**
     * Placeholder untuk lazy loading
     */
    public function placeholder()
    {
        return view('livewire.placeholders.murid-alumni-counts-by-province-skeleton');
    }

    /**
     * Get murid counts grouped by provinsi and kabupaten
     * Hanya menampilkan provinsi/kabupaten yang memiliki sekolah di bawah naungan user.
     */
    private function getMuridCounts(): Collection
    {
        // 1. Ambil semua ID Sekolah yang bisa diakses oleh user saat ini.
        // Global Scope 'NauanganSekolah' pada model Sekolah akan otomatis memfilter data ini
        // berdasarkan Auth::user() dan tabel editor_lists.
        $accessibleSekolahIds = Sekolah::query()->pluck('id');

        // Jika user tidak memiliki akses ke sekolah manapun, kembalikan collection kosong
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
        // Kita membatasi relasi agar hanya memuat kabupaten dan sekolah yang relevan (naungan user).
        return Provinsi::naungan()
            ->whereIn('id', $accessibleProvinsiIds)
            ->with([
                'kabupaten' => function ($query) use ($accessibleKabupatenIds) {
                    $query->whereIn('id', $accessibleKabupatenIds);
                },
                'kabupaten.sekolah' => function ($query) use ($accessibleSekolahIds) {
                    // Memastikan hanya sekolah yang diakses yang di-load
                    $query->whereIn('id', $accessibleSekolahIds);
                },
                'kabupaten.sekolah.sekolahMurid.murid' // MuridNauanganScope juga berjalan di sini
            ])
            ->get()
            ->map(function (Provinsi $provinsi) {
                $kabupatens = $provinsi->kabupaten->map(function ($kabupaten) {
                    // $kabupaten->sekolah sudah terfilter oleh eager loading constraint & scope
                    $schools = $kabupaten->sekolah;

                    if ($schools->isEmpty()) {
                        return null;
                    }

                    $sekolahMurids = $schools
                        ->flatMap(fn($sekolah) => $sekolah->sekolahMurid);

                    $totalMurid = $sekolahMurids->count();
                    
                    $alumniCount = $sekolahMurids
                        ->flatMap(fn($sm) => $sm->murid)
                        ->filter(fn($murid) => $murid->isAlumni())
                        ->unique('id')
                        ->count();

                    // Jika tidak ada murid di kabupaten ini (untuk user ini), skip
                    if ($totalMurid === 0) {
                        return null;
                    }

                    return [
                        'id' => $kabupaten->id,
                        'nama' => $kabupaten->nama_kabupaten ?? 'Unknown',
                        'murid_count' => $totalMurid,
                        'alumni_count' => $alumniCount,
                    ];
                })->filter(); // Hapus kabupaten yang null

                // Jika provinsi tidak memiliki kabupaten dengan murid, skip provinsi ini
                if ($kabupatens->isEmpty()) {
                    return null;
                }

                $totalMurid = $kabupatens->sum('murid_count');
                $totalAlumni = $kabupatens->sum('alumni_count');

                return [
                    'id' => $provinsi->id,
                    'nama' => $provinsi->nama_provinsi ?? 'Unknown',
                    'total_murid' => $totalMurid,
                    'total_alumni' => $totalAlumni,
                    'kabupatens' => $kabupatens,
                ];
            })->filter(); // Hapus provinsi yang null
    }

    public function render()
    {
        return view('livewire.murid-alumni-counts-by-province-widget', [
            'provinsis' => $this->getMuridCounts(),
        ]);
    }
}