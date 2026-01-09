<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Alumni;
use App\Models\ValidasiAlumni;
use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidasiAlumniController extends Controller
{
    /**
     * Show the alumni validation form (public).
     */
    public function form(Request $request)
    {
        return view('pages.validasi-alumni.form');
    }

    /**
     * Store alumni validation data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'exists:murid,nik'],
            'murid_id' => ['required', 'integer', 'exists:murid,id'],
            'profesi_sekarang' => ['nullable', 'string', 'max:255'],
            'nama_tempat_kerja' => ['nullable', 'string', 'max:255'],
            'kota_tempat_kerja' => ['nullable', 'string', 'max:255'],
            'riwayat_pekerjaan' => ['nullable', 'string', 'max:255'],
            'kontak_wa' => ['nullable', 'string', 'max:50'],
            'kontak_email' => ['nullable', 'email', 'max:255'],
            'update_alamat_sekarang' => ['nullable', 'string', 'max:255'],
        ]);

        $murid = Murid::withoutGlobalScopes()
            ->where('id', $validated['murid_id'])
            ->where('nik', $validated['nik'])
            ->first();

        if (!$murid) {
            return back()->withInput()->with('error', 'NIK tidak ditemukan.');
        }

        $validasi = ValidasiAlumni::where('id_murid', $murid->id)->first();
        if (!$validasi) {
            $validasi = new ValidasiAlumni();
        }

        $validasi->id_murid = $murid->id;
        $validasi->profesi_sekarang = $validated['profesi_sekarang'] ?? null;
        $validasi->nama_tempat_kerja = $validated['nama_tempat_kerja'] ?? null;
        $validasi->kota_tempat_kerja = $validated['kota_tempat_kerja'] ?? null;
        $validasi->riwayat_pekerjaan = $validated['riwayat_pekerjaan'] ?? null;
        $validasi->kontak_wa = $validated['kontak_wa'] ?? null;
        $validasi->kontak_email = $validated['kontak_email'] ?? null;
        $validasi->update_alamat_sekarang = $validated['update_alamat_sekarang'] ?? null;
        $validasi->tanggal_update_data_alumni = now();
        $validasi->save();

        return redirect()->route('validasi-alumni.form')->with('success', 'Terima kasih! Data alumni Anda berhasil divalidasi.');
    }

    public function cariNik(Request $request)
    {
        $nik = $request->query('nik');

        if (!$nik) {
            return response()->json(['found' => false], 200);
        }

        $murid = Murid::withoutGlobalScopes()->where('nik', $nik)->first();

        if (!$murid) {
            return response()->json(['found' => false], 200);
        }

        return response()->json([
            'found' => true,
            'nama' => "$murid->nama (NISN: $murid->nisn)",
            'id' => $murid->id,
            'nisn' => $murid->nisn,
            'status_alumni' => $murid->status_alumni,
        ], 200);
    }

    /**
     * Show the alumni validation list (authenticated).
     */
    public function index(Request $request)
    {
        $validasi = ValidasiAlumni::with('murid')
            ->orderBy('is_accepted', 'asc')
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && !empty($request->query('search'))) {
            $search = $request->query('search');
            $validasi->whereHas('murid', function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && in_array($request->query('status'), ['accepted', 'pending'])) {
            if ($request->query('status') == 'accepted') {
                $validasi->where('is_accepted', true);
            } else {
                $validasi->where('is_accepted', false);
            }
        }

        $validasi = $validasi->paginate(10)->withQueryString();

        return view('pages.validasi-alumni.index', [
            'title' => 'Validasi Alumni',
            'validasi' => $validasi,
        ]);
    }

    /**
     * Approve alumni validation and update related data.
     */
    public function approve(Request $request, ValidasiAlumni $validasi)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        if (!$validasi->murid) {
            return back()->with('error', 'Data murid tidak ditemukan.');
        }

        $murid = $validasi->murid;

        try {
            // Update murid data
            $murid->update([
                'status_alumni' => true,
                'kontak_wa_hp' => $validasi->kontak_wa ?? $murid->kontak_wa_hp,
                'kontak_email' => $validasi->kontak_email ?? $murid->kontak_email,
            ]);

            // Create or update Alumni record
            $alumni = Alumni::firstOrCreate(
                ['id_murid' => $murid->id],
                [
                    'profesi_sekarang' => $validasi->profesi_sekarang,
                    'nama_tempat_kerja' => $validasi->nama_tempat_kerja,
                    'kota_tempat_kerja' => $validasi->kota_tempat_kerja,
                    'riwayat_pekerjaan' => $validasi->riwayat_pekerjaan,
                ]
            );

            // Update Alumni if already exists
            if ($alumni->exists && $alumni->wasRecentlyCreated === false) {
                $alumni->update([
                    'profesi_sekarang' => $validasi->profesi_sekarang,
                    'nama_tempat_kerja' => $validasi->nama_tempat_kerja,
                    'kota_tempat_kerja' => $validasi->kota_tempat_kerja,
                    'riwayat_pekerjaan' => $validasi->riwayat_pekerjaan,
                ]);
            }

            // Create or update Alamat (domisili) if alamat_sekarang provided
            if ($validasi->update_alamat_sekarang) {
                Alamat::updateOrCreate(
                    [
                        'id_murid' => $murid->id,
                        'jenis' => 'domisili',
                    ],
                    [
                        'alamat_lengkap' => $validasi->update_alamat_sekarang,
                    ]
                );
            }

            $validasi->is_accepted = true;
            $validasi->save();

            return back()->with('success', 'Validasi alumni telah disetujui dan data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
