<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Scopes\GuruSekolahNauanganScope;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Check if user is authenticated using Auth facade like in SekolahController
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        // Determine which teachers to show based on user role
        if ($user->isSuperuser() || $user->isKomisariatWilayah()) {
            // Show all teachers for superusers and wilayah admins
            $query = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)
                ->with([
                    'jabatanGurus.sekolah',
                ]);
            
            // Add search functionality
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('npk', 'like', "%{$search}%")
                      ->orWhere('nuptk', 'like', "%{$search}%");
                });
            }
            
            $guru = $query->latest()->paginate(20);
            $title = 'Data Guru Semua Sekolah';
            
        } elseif ($user->isSekolah() && $user->sekolah) {
            // Show teachers only from this school
            $sekolah = $user->sekolah;
            $query = $sekolah->guru();
            
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%")
                      ->orWhere('npk', 'like', "%{$search}%")
                      ->orWhere('nuptk', 'like', "%{$search}%");
                });
            }
            
            $guru = $query->paginate(20);
            $title = 'Data Guru - ' . $sekolah->nama;
            
        } else {
            // For other roles, show empty or limited data
            $guru = collect();
            $title = 'Data Guru';
        }
        
        return view('pages.guru.index', compact('guru', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Only superusers and wilayah admins can create teachers
        if (!$user->isSuperuser() && !$user->isKomisariatWilayah()) {
            abort(403, 'Unauthorized');
        }

        // Get all active schools for assignment
        $sekolah = Sekolah::aktif()->orderBy('nama')->get();

        return view('pages.guru.create', compact('sekolah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Only superusers and wilayah admins can create teachers
        if (!$user->isSuperuser() && !$user->isKomisariatWilayah()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_gelar_depan' => 'nullable|string|max:50',
            'nama_gelar_belakang' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'required|string|size:16|unique:guru,nik',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'status_perkawinan' => 'nullable|in:lajang,menikah',
            'status_kepegawaian' => 'nullable|in:PNS,Non PNS,PPPK',
            'npk' => 'nullable|string|max:50|unique:guru,npk',
            'nuptk' => 'nullable|string|max:16|unique:guru,nuptk',
            'kontak_wa_hp' => 'nullable|string|max:20',
            'kontak_email' => 'nullable|email|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
            'rekening_atas_nama' => 'nullable|string|max:100',
            'bank_rekening' => 'nullable|string|max:50',
            'id_sekolah' => 'required|array|min:1',
            'id_sekolah.*' => 'required|exists:sekolah,id',
            'jenis_jabatan' => 'required|array|min:1',
            'jenis_jabatan.*' => 'required|in:' . implode(',', array_keys(JabatanGuru::JENIS_JABATAN_OPTIONS)),
            'keterangan_jabatan' => 'nullable|array',
            'keterangan_jabatan.*' => 'nullable|string|max:255',
        ]);

        // Create the teacher
        $guru = Guru::create([
            'nama' => $request->nama,
            'nama_gelar_depan' => $request->nama_gelar_depan,
            'nama_gelar_belakang' => $request->nama_gelar_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status_perkawinan' => $request->status_perkawinan,
            'status_kepegawaian' => $request->status_kepegawaian,
            'npk' => $request->npk,
            'nuptk' => $request->nuptk,
            'kontak_wa_hp' => $request->kontak_wa_hp,
            'kontak_email' => $request->kontak_email,
            'nomor_rekening' => $request->nomor_rekening,
            'rekening_atas_nama' => $request->rekening_atas_nama,
            'bank_rekening' => $request->bank_rekening,
            'status' => Guru::STATUS_AKTIF, // Default to active
        ]);

        // Create jabatan_guru records for each school assignment
        foreach ($request->id_sekolah as $index => $idSekolah) {
            JabatanGuru::create([
                'id_guru' => $guru->id,
                'id_sekolah' => $idSekolah,
                'jenis_jabatan' => $request->jenis_jabatan[$index],
                'keterangan_jabatan' => $request->keterangan_jabatan[$index] ?? null,
            ]);
        }

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Load the guru without scope restrictions first
        $guru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)->findOrFail($guru->id);

        $guru->load(['jabatanGurus.sekolah', 'alamatList']);

        return view('pages.guru.show', [
            'guru' => $guru,
            'title' => 'Detail Guru - ' . $guru->nama
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Load the guru without scope restrictions first
        $guru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)->findOrFail($guru->id);

        // Check if user can edit this teacher
        if (!$user->isSuperuser() && !$user->isKomisariatWilayah()) {
            // School users can only edit teachers from their school
            if ($user->isSekolah()) {
                $canEdit = $guru->jabatanGurus()->where('id_sekolah', $user->sekolah->id)->exists();
                if (!$canEdit) {
                    abort(403, 'Unauthorized');
                }
            } else {
                abort(403, 'Unauthorized');
            }
        }

        // Get all schools for assignment (including inactive ones for existing assignments)
        $sekolah = Sekolah::orderBy('nama')->get();

        // Load relationships
        $guru->load(['jabatanGurus.sekolah', 'alamatList']);

        return view('pages.guru.edit', [
            'guru' => $guru,
            'sekolah' => $sekolah,
            'title' => 'Edit Guru - ' . $guru->nama
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Load the guru without scope restrictions first
        $guru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)->findOrFail($guru->id);

        // Check if user can update this teacher
        if (!$user->isSuperuser() && !$user->isKomisariatWilayah()) {
            // School users can only update teachers from their school
            if ($user->isSekolah()) {
                $canUpdate = $guru->jabatanGurus()->where('id_sekolah', $user->sekolah->id)->exists();
                if (!$canUpdate) {
                    abort(403, 'Unauthorized');
                }
            } else {
                abort(403, 'Unauthorized');
            }
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_gelar_depan' => 'nullable|string|max:50',
            'nama_gelar_belakang' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'nik' => 'required|string|size:16|unique:guru,nik,' . $guru->id,
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'status_perkawinan' => 'nullable|in:lajang,menikah',
            'status_kepegawaian' => 'nullable|in:PNS,Non PNS,PPPK',
            'npk' => 'nullable|string|max:50|unique:guru,npk,' . $guru->id,
            'nuptk' => 'nullable|string|max:16|unique:guru,nuptk,' . $guru->id,
            'kontak_wa_hp' => 'nullable|string|max:20',
            'kontak_email' => 'nullable|email|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
            'rekening_atas_nama' => 'nullable|string|max:100',
            'bank_rekening' => 'nullable|string|max:50',
            'id_sekolah' => 'required|array|min:1',
            'id_sekolah.*' => 'required|exists:sekolah,id',
            'jenis_jabatan' => 'required|array|min:1',
            'jenis_jabatan.*' => 'required|in:' . implode(',', array_keys(JabatanGuru::JENIS_JABATAN_OPTIONS)),
            'keterangan_jabatan' => 'nullable|array',
            'keterangan_jabatan.*' => 'nullable|string|max:255',
        ]);

        // Update the teacher
        $guru->update([
            'nama' => $request->nama,
            'nama_gelar_depan' => $request->nama_gelar_depan,
            'nama_gelar_belakang' => $request->nama_gelar_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status_perkawinan' => $request->status_perkawinan,
            'status_kepegawaian' => $request->status_kepegawaian,
            'npk' => $request->npk,
            'nuptk' => $request->nuptk,
            'kontak_wa_hp' => $request->kontak_wa_hp,
            'kontak_email' => $request->kontak_email,
            'nomor_rekening' => $request->nomor_rekening,
            'rekening_atas_nama' => $request->rekening_atas_nama,
            'bank_rekening' => $request->bank_rekening,
        ]);

        // Delete existing jabatan_guru records
        $guru->jabatanGurus()->delete();

        // Create new jabatan_guru records for each school assignment
        foreach ($request->id_sekolah as $index => $idSekolah) {
            JabatanGuru::create([
                'id_guru' => $guru->id,
                'id_sekolah' => $idSekolah,
                'jenis_jabatan' => $request->jenis_jabatan[$index],
                'keterangan_jabatan' => $request->keterangan_jabatan[$index] ?? null,
            ]);
        }

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Load the guru without scope restrictions first
        $guru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)->findOrFail($guru->id);

        // Check if user can delete this teacher
        if (!$user->isSuperuser() && !$user->isKomisariatWilayah()) {
            // School users can only delete teachers from their school
            if ($user->isSekolah()) {
                $canDelete = $guru->jabatanGurus()->where('id_sekolah', $user->sekolah->id)->exists();
                if (!$canDelete) {
                    abort(403, 'Unauthorized');
                }
            } else {
                abort(403, 'Unauthorized');
            }
        }

        // Delete associated jabatan_guru records first
        $guru->jabatanGurus()->delete();

        // Delete associated alamat records
        $guru->alamatList()->delete();

        // Delete the guru
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}