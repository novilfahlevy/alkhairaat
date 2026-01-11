<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Sekolah;
use App\Models\Murid;
use App\Models\Guru;
use App\Models\SekolahMurid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperuser() || $user->isPengurusBesar()) {
            return view('pages.dashboard.super');
        }

        if ($user->isKomisariatWilayah()) {
            return view('pages.dashboard.super');
        }

        if ($user->isKomisariatDaerah()) {
            return view('pages.dashboard.super');
        }

        $sekolahCount = Sekolah::count();
        return view('pages.dashboard.sekolah', compact('sekolahCount'));
    }

    public function exportPdf()
    {
        $user = Auth::user();

        // Hitung jumlah sekolah yang dinaungi user
        $jumlahSekolahNaungan = Sekolah::count();

        // Siapkan data yang sama seperti di widget
        $data = [
            'sekolah' => $this->getSekolahCounts(),
            'murid_guru' => $this->getMuridGuruCounts(),
            'user' => $user,
            'tanggal_cetak' => now()->translatedFormat('d F Y H:i'),
            'jumlah_sekolah_naungan' => $jumlahSekolahNaungan,
        ];

        // Hanya tampilkan data provinsi untuk super/pengurus besar/komisariat
        if ($user->isSuperuser() || $user->isPengurusBesar() || $user->isKomisariatWilayah() || $user->isKomisariatDaerah()) {
            $data['provinsi'] = $this->getMuridCountsByProvince();
        }

        // Generate PDF
        $pdf = Pdf::loadView('pages.dashboard.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        $filename = 'Laporan_Dashboard_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get total counts for sekolah
     */
    private function getSekolahCounts(): array
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

    /**
     * Get total counts for murid and guru
     */
    private function getMuridGuruCounts(): array
    {
        $user = Auth::user();

        $totalMurid = Murid::count();
        // $muridAktif = Murid::nonAlumni()->count();
        // $muridAlumni = Murid::alumni()->count();

        $muridLulus = SekolahMurid::whereHas('sekolah.editorLists', function ($query) use ($user) {
            if ($user->isPengurusBesar() || $user->isSuperuser()) {
                return;
            }
            $query->where('id_user', $user->id);
        })
            ->where('status_kelulusan', SekolahMurid::STATUS_LULUS_YA)
            ->count();

        $muridTidakLulus = SekolahMurid::whereHas('sekolah.editorLists', function ($query) use ($user) {
            if ($user->isPengurusBesar() || $user->isSuperuser()) {
                return;
            }
            $query->where('id_user', $user->id);
        })
            ->where('status_kelulusan', SekolahMurid::STATUS_LULUS_TIDAK)
            ->count();

        $muridBelumLulus = SekolahMurid::whereHas('sekolah.editorLists', function ($query) use ($user) {
            if ($user->isPengurusBesar() || $user->isSuperuser()) {
                return;
            }
            $query->where('id_user', $user->id);
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

    /**
     * Get murid counts grouped by provinsi and kabupaten
     */
    private function getMuridCountsByProvince(): Collection
    {
        $accessibleSekolahIds = Sekolah::query()->pluck('id');

        if ($accessibleSekolahIds->isEmpty()) {
            return collect();
        }

        $accessibleKabupatenIds = Sekolah::whereIn('id', $accessibleSekolahIds)
            ->distinct()
            ->pluck('id_kabupaten');

        $accessibleProvinsiIds = Kabupaten::whereIn('id', $accessibleKabupatenIds)
            ->distinct()
            ->pluck('id_provinsi');

        return Provinsi::naungan()
            ->whereIn('id', $accessibleProvinsiIds)
            ->with([
                'kabupaten' => function ($query) use ($accessibleKabupatenIds) {
                    $query->whereIn('id', $accessibleKabupatenIds);
                },
                'kabupaten.sekolah' => function ($query) use ($accessibleSekolahIds) {
                    $query->whereIn('id', $accessibleSekolahIds);
                },
                'kabupaten.sekolah.sekolahMurid.murid'
            ])
            ->get()
            ->map(function (Provinsi $provinsi) {
                $kabupatens = $provinsi->kabupaten->map(function ($kabupaten) {
                    $schools = $kabupaten->sekolah;

                    if ($schools->isEmpty()) {
                        return null;
                    }

                    $sekolahMurids = $schools->flatMap(fn($sekolah) => $sekolah->sekolahMurid);
                    $totalMurid = $sekolahMurids->count();

                    $alumniCount = $sekolahMurids
                        ->filter(fn($sm) => $sm->murid !== null && $sm->murid->isAlumni())
                        ->pluck('murid.id')
                        ->unique()
                        ->count();

                    if ($totalMurid === 0) {
                        return null;
                    }

                    return [
                        'id' => $kabupaten->id,
                        'nama' => $kabupaten->nama_kabupaten ?? 'Unknown',
                        'murid_count' => $totalMurid,
                        'alumni_count' => $alumniCount,
                    ];
                })->filter();

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
            })->filter();
    }
}
