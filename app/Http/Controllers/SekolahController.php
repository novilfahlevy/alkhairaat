<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSekolahRequest;
use App\Http\Requests\UpdateSekolahRequest;
use App\Models\Sekolah;
use App\Models\Alamat;
use App\Models\GaleriSekolah;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Sekolah::query()->with(['kabupaten.provinsi']);

        // Apply filters based on user role
        $user = Auth::user();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_sekolah', 'like', "%{$search}%");
            });
        }

        // Apply jenis_sekolah filter
        if ($request->filled('jenis_sekolah')) {
            $query->where('jenis_sekolah', $request->input('jenis_sekolah'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $sekolah = $query->orderBy('nama')->paginate(20);

        return view('pages.sekolah.index', [
            'title' => 'Data Sekolah',
            'sekolah' => $sekolah,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $provinsi = Provinsi::orderBy('nama_provinsi')->get();

        return view('pages.sekolah.create', [
            'title' => 'Tambah Sekolah',
            'provinsi' => $provinsi,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSekolahRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Get nama provinsi and kabupaten from database
        $provinsi = Provinsi::find($validated['id_provinsi']);
        $kabupaten = Kabupaten::find($validated['id_kabupaten']);
        
        // Extract alamat-related fields for alamat table
        $alamatData = [
            'provinsi' => $provinsi?->nama_provinsi,
            'kabupaten' => $kabupaten?->nama_kabupaten,
            'kecamatan' => $validated['alamat_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_kelurahan'] ?? null,
            'rt' => $validated['alamat_rt'] ?? null,
            'rw' => $validated['alamat_rw'] ?? null,
            'kode_pos' => $validated['alamat_kode_pos'] ?? null,
            'koordinat_x' => $validated['alamat_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_koordinat_y'] ?? null,
            'alamat_lengkap' => $validated['alamat'] ?? null,
        ];

        // Remove alamat_* prefixed fields from validated data (keep 'alamat' for sekolah table)
        unset($validated['alamat_kecamatan']);
        unset($validated['alamat_kelurahan']);
        unset($validated['alamat_rt']);
        unset($validated['alamat_rw']);
        unset($validated['alamat_kode_pos']);
        unset($validated['alamat_koordinat_x']);
        unset($validated['alamat_koordinat_y']);

        // Extract galeri files
        $galeriFiles = $request->file('galeri_files') ?? [];
        unset($validated['galeri_files']);

        // Create sekolah
        $sekolah = Sekolah::create($validated);

        // Create alamat record if any alamat data is provided
        if (array_filter($alamatData)) {
            $alamatData['id_sekolah'] = $sekolah->id;
            $alamatData['jenis'] = Alamat::JENIS_ASLI;
            Alamat::create($alamatData);
        }

        // Handle galeri upload
        if (!empty($galeriFiles)) {
            foreach ($galeriFiles as $file) {
                $filePath = $file->store('galeri-sekolah/' . $sekolah->kode_sekolah ?? $sekolah->id, 'public');
                GaleriSekolah::create([
                    'id_sekolah' => $sekolah->id,
                    'image_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekolah $sekolah): View
    {
        $sekolah->load(['kabupaten.provinsi', 'murid', 'alamatList']);

        return view('pages.sekolah.show', [
            'title' => 'Detail Sekolah',
            'sekolah' => $sekolah,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekolah $sekolah): View
    {
        $provinsi = Provinsi::orderBy('nama_provinsi')->get();
        $kabupaten = $sekolah->kabupaten?->id_provinsi
            ? Kabupaten::where('id_provinsi', $sekolah->kabupaten->id_provinsi)->orderBy('nama_kabupaten')->get()
            : collect();

        return view('pages.sekolah.edit', [
            'title' => 'Edit Sekolah',
            'sekolah' => $sekolah,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSekolahRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validated();
        
        // Get nama provinsi and kabupaten from database
        $provinsi = Provinsi::find($validated['id_provinsi']);
        $kabupaten = Kabupaten::find($validated['id_kabupaten']);
        
        // Extract alamat-related fields for alamat table
        $alamatData = [
            'provinsi' => $provinsi?->nama_provinsi,
            'kabupaten' => $kabupaten?->nama_kabupaten,
            'kecamatan' => $validated['alamat_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_kelurahan'] ?? null,
            'rt' => $validated['alamat_rt'] ?? null,
            'rw' => $validated['alamat_rw'] ?? null,
            'kode_pos' => $validated['alamat_kode_pos'] ?? null,
            'koordinat_x' => $validated['alamat_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_koordinat_y'] ?? null,
            'alamat_lengkap' => $validated['alamat'] ?? null,
        ];

        // Remove alamat_* prefixed fields from validated data (keep 'alamat' for sekolah table)
        unset($validated['alamat_kecamatan']);
        unset($validated['alamat_kelurahan']);
        unset($validated['alamat_rt']);
        unset($validated['alamat_rw']);
        unset($validated['alamat_kode_pos']);
        unset($validated['alamat_koordinat_x']);
        unset($validated['alamat_koordinat_y']);

        // Extract galeri files and deleted files
        $galeriFiles = $request->file('galeri_files') ?? [];
        $deletedGaleriIds = $request->input('deleted_galeri_ids', []);
        unset($validated['galeri_files']);

        // Update sekolah
        $sekolah->update($validated);

        // Update or create alamat record if any alamat data is provided
        if (array_filter($alamatData)) {
            $alamatData['jenis'] = Alamat::JENIS_ASLI;
            $sekolah->alamatList()->updateOrCreate(
                ['jenis' => Alamat::JENIS_ASLI],
                $alamatData
            );
        }

        // Delete galeri yang dipilih untuk dihapus
        if (!empty($deletedGaleriIds)) {
            foreach ($deletedGaleriIds as $galeriId) {
                $galeri = GaleriSekolah::find($galeriId);
                if ($galeri && $galeri->id_sekolah === $sekolah->id) {
                    Storage::disk('public')->delete($galeri->image_path);
                    $galeri->delete();
                }
            }
        }

        // Handle galeri upload
        if (!empty($galeriFiles)) {
            foreach ($galeriFiles as $file) {
                $filePath = $file->store('galeri-sekolah/' . $sekolah->kode_sekolah ?? $sekolah->id, 'public');
                GaleriSekolah::create([
                    'id_sekolah' => $sekolah->id,
                    'image_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sekolah $sekolah): RedirectResponse
    {
        // Check if sekolah has users or murid
        // if ($sekolah->guru()->count() > 0 || $sekolah->murid()->count() > 0) {
        //     return redirect()->route('sekolah.index')
        //         ->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki user atau murid.');
        // }

        $sekolah->delete();

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    /**
     * Get kabupaten by provinsi (AJAX endpoint)
     */
    public function getKabupaten(Request $request)
    {
        $provinsiId = $request->input('id_provinsi');
        $kabupaten = Kabupaten::where('id_provinsi', $provinsiId)
            ->orderBy('nama_kabupaten')
            ->get(['id', 'nama_kabupaten']);

        return response()->json($kabupaten);
    }
}

