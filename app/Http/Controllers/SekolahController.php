<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSekolahRequest;
use App\Http\Requests\UpdateSekolahRequest;
use App\Models\Sekolah;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $user = auth()->user();
        if ($user->isKomisariatWilayah()) {
            // Komisariat wilayah can only see sekolah in their managed kabupaten
            $kabupatenIds = $user->kabupaten()->pluck('kabupaten.id');
            $query->whereIn('kabupaten_id', $kabupatenIds);
        } elseif ($user->isGuru()) {
            // Guru can only see their own sekolah
            $query->where('id', $user->sekolah_id);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_sekolah', 'like', "%{$search}%");
            });
        }

        // Apply jenjang filter
        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->input('jenjang'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $sekolah = $query->orderBy('nama')->paginate(20);

        return view('pages.sekolah.index', [
            'title' => 'Data Sekolah',
            'sekolah' => $sekolah,
            'jenjangOptions' => Sekolah::JENJANG_OPTIONS,
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
            'jenjangOptions' => Sekolah::JENJANG_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSekolahRequest $request): RedirectResponse
    {
        $sekolah = Sekolah::create($request->validated());

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekolah $sekolah): View
    {
        // Check if user can access this sekolah
        if (!auth()->user()->canAccessSekolah($sekolah->id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $sekolah->load(['kabupaten.provinsi', 'santri', 'users']);

        return view('pages.sekolah.show', [
            'title' => 'Detail Sekolah',
            'sekolah' => $sekolah,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekolah $sekolah): View
    {
        // Check if user can access this sekolah
        if (!auth()->user()->canAccessSekolah($sekolah->id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $provinsi = Provinsi::orderBy('nama_provinsi')->get();
        $kabupaten = $sekolah->kabupaten?->provinsi_id
            ? Kabupaten::where('provinsi_id', $sekolah->kabupaten->provinsi_id)->orderBy('nama_kabupaten')->get()
            : collect();

        return view('pages.sekolah.edit', [
            'title' => 'Edit Sekolah',
            'sekolah' => $sekolah,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'jenjangOptions' => Sekolah::JENJANG_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSekolahRequest $request, Sekolah $sekolah): RedirectResponse
    {
        // Check if user can access this sekolah
        if (!auth()->user()->canAccessSekolah($sekolah->id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        $sekolah->update($request->validated());

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sekolah $sekolah): RedirectResponse
    {
        // Check if user can access this sekolah
        if (!auth()->user()->canAccessSekolah($sekolah->id)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        // Check if sekolah has users or santri
        if ($sekolah->users()->count() > 0 || $sekolah->santri()->count() > 0) {
            return redirect()->route('sekolah.index')
                ->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki user atau santri.');
        }

        $sekolah->delete();

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    /**
     * Get kabupaten by provinsi (AJAX endpoint)
     */
    public function getKabupaten(Request $request)
    {
        $provinsiId = $request->input('provinsi_id');
        $kabupaten = Kabupaten::where('provinsi_id', $provinsiId)
            ->orderBy('nama_kabupaten')
            ->get(['id', 'nama_kabupaten']);

        return response()->json($kabupaten);
    }
}
