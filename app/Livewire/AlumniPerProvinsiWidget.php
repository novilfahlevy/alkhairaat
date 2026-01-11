<?php

namespace App\Livewire;

use App\Models\Alumni;
use App\Models\Provinsi;
use Livewire\Component;
use Illuminate\Support\Collection;

class AlumniPerProvinsiWidget extends Component
{
    /**
     * Placeholder untuk lazy loading
     */
    public function placeholder()
    {
        return view('livewire.placeholders.alumni-per-provinsi-skeleton');
    }

    /**
     * Get alumni counts grouped by provinsi
     */
    private function getAlumniCounts(): Collection
    {
        // Query alumni grouped by provinsi
        $alumniByProvinsi = Alumni::query()
            ->whereNotNull('id_provinsi')
            ->selectRaw('id_provinsi, COUNT(*) as total_alumni')
            ->groupBy('id_provinsi')
            ->pluck('total_alumni', 'id_provinsi');

        // Get total alumni without province set
        $alumniTanpaProvinsi = Alumni::whereNull('id_provinsi')->count();

        // Get all provinsi that have alumni
        $provinsiIds = $alumniByProvinsi->keys()->toArray();
        
        $provinsis = Provinsi::whereIn('id', $provinsiIds)
            ->orderBy('nama_provinsi')
            ->get()
            ->map(function (Provinsi $provinsi) use ($alumniByProvinsi) {
                return [
                    'id' => $provinsi->id,
                    'nama' => $provinsi->nama_provinsi ?? 'Unknown',
                    'total_alumni' => $alumniByProvinsi[$provinsi->id] ?? 0,
                ];
            })
            ->sortByDesc('total_alumni')
            ->values();

        // Add "Belum Diketahui" if there are alumni without province
        if ($alumniTanpaProvinsi > 0) {
            $provinsis->push([
                'id' => null,
                'nama' => 'Belum Diketahui',
                'total_alumni' => $alumniTanpaProvinsi,
            ]);
        }

        return $provinsis;
    }

    /**
     * Get summary statistics
     */
    private function getSummary(): array
    {
        $totalAlumni = Alumni::count();
        $alumniDenganProvinsi = Alumni::whereNotNull('id_provinsi')->count();
        $alumniTanpaProvinsi = Alumni::whereNull('id_provinsi')->count();
        $totalProvinsi = Alumni::whereNotNull('id_provinsi')
            ->distinct('id_provinsi')
            ->count('id_provinsi');

        return [
            'total_alumni' => $totalAlumni,
            'dengan_provinsi' => $alumniDenganProvinsi,
            'tanpa_provinsi' => $alumniTanpaProvinsi,
            'total_provinsi' => $totalProvinsi,
        ];
    }

    public function render()
    {
        return view('livewire.alumni-per-provinsi-widget', [
            'provinsis' => $this->getAlumniCounts(),
            'summary' => $this->getSummary(),
        ]);
    }
}
