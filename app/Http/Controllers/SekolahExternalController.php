<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSekolahExternalRequest;
use App\Http\Requests\UpdateSekolahExternalRequest;
use App\Models\SekolahExternal;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SekolahExternalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SekolahExternal::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('kota_sekolah', 'like', "%{$search}%");
            });
        }

        // Apply jenis_sekolah filter
        if ($request->filled('jenis_sekolah')) {
            $query->where('jenis_sekolah', $request->input('jenis_sekolah'));
        }

        // Apply bentuk_pendidikan filter
        if ($request->filled('bentuk_pendidikan')) {
            $query->where('bentuk_pendidikan', $request->input('bentuk_pendidikan'));
        }

        $sekolahExternal = $query->orderBy('nama_sekolah')->paginate(20);

        return view('pages.sekolah-external.index', [
            'title' => 'Data Sekolah External',
            'sekolahExternal' => $sekolahExternal,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.sekolah-external.create', [
            'title' => 'Tambah Sekolah External',
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSekolahExternalRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        SekolahExternal::create($validated);

        return redirect()->route('sekolah-external.index')
            ->with('success', 'Sekolah external berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SekolahExternal $sekolahExternal): View
    {
        return view('pages.sekolah-external.show', [
            'title' => 'Detail Sekolah External',
            'sekolahExternal' => $sekolahExternal,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SekolahExternal $sekolahExternal): View
    {
        return view('pages.sekolah-external.edit', [
            'title' => 'Edit Sekolah External',
            'sekolahExternal' => $sekolahExternal,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSekolahExternalRequest $request, SekolahExternal $sekolahExternal): RedirectResponse
    {
        $validated = $request->validated();
        
        $sekolahExternal->update($validated);

        return redirect()->route('sekolah-external.show', ['sekolahExternal' => $sekolahExternal->id])
            ->with('success', 'Sekolah external berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SekolahExternal $sekolahExternal): RedirectResponse
    {
        $sekolahExternal->delete();

        return redirect()->route('sekolah-external.index')
            ->with('success', 'Sekolah external berhasil dihapus.');
    }
}
