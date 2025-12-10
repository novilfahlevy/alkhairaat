<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLembagaRequest;
use App\Http\Requests\UpdateLembagaRequest;
use App\Models\Lembaga;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Lembaga::query()->with(['kabupaten.provinsi']);

        // Apply filters based on user role
        $user = auth()->user();
        if ($user->isWilayah()) {
            // Wilayah can only see lembaga in their managed kabupaten
            $kabupatenIds = $user->kabupaten()->pluck('kabupaten.id');
            $query->whereIn('kabupaten_id', $kabupatenIds);
        } elseif ($user->isSekolah()) {
            // Sekolah can only see their own lembaga
            $query->where('id', $user->lembaga_id);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_lembaga', 'like', "%{$search}%");
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

        $lembaga = $query->orderBy('nama')->paginate(20);

        return view('pages.lembaga.index', [
            'title' => 'Data Lembaga',
            'lembaga' => $lembaga,
            'jenjangOptions' => Lembaga::JENJANG_OPTIONS,
            'statusOptions' => Lembaga::STATUS_OPTIONS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $provinsi = Provinsi::orderBy('nama_provinsi')->get();
        
        return view('pages.lembaga.create', [
            'title' => 'Tambah Lembaga',
            'provinsi' => $provinsi,
            'jenjangOptions' => Lembaga::JENJANG_OPTIONS,
            'statusOptions' => Lembaga::STATUS_LABELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLembagaRequest $request): RedirectResponse
    {
        $lembaga = Lembaga::create($request->validated());

        return redirect()->route('lembaga.index')
            ->with('success', 'Lembaga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lembaga $lembaga): View
    {
        // Check if user can access this lembaga
        if (!auth()->user()->canAccessLembaga($lembaga->id)) {
            abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
        }

        $lembaga->load(['kabupaten.provinsi', 'santri', 'users']);

        return view('pages.lembaga.show', [
            'title' => 'Detail Lembaga',
            'lembaga' => $lembaga,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lembaga $lembaga): View
    {
        // Check if user can access this lembaga
        if (!auth()->user()->canAccessLembaga($lembaga->id)) {
            abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
        }

        $provinsi = Provinsi::orderBy('nama_provinsi')->get();
        $kabupaten = $lembaga->kabupaten?->provinsi_id 
            ? Kabupaten::where('provinsi_id', $lembaga->kabupaten->provinsi_id)->orderBy('nama_kabupaten')->get()
            : collect();

        return view('pages.lembaga.edit', [
            'title' => 'Edit Lembaga',
            'lembaga' => $lembaga,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'jenjangOptions' => Lembaga::JENJANG_OPTIONS,
            'statusOptions' => Lembaga::STATUS_LABELS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLembagaRequest $request, Lembaga $lembaga): RedirectResponse
    {
        // Check if user can access this lembaga
        if (!auth()->user()->canAccessLembaga($lembaga->id)) {
            abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
        }

        $lembaga->update($request->validated());

        return redirect()->route('lembaga.index')
            ->with('success', 'Lembaga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lembaga $lembaga): RedirectResponse
    {
        // Check if user can access this lembaga
        if (!auth()->user()->canAccessLembaga($lembaga->id)) {
            abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
        }

        // Check if lembaga has users or santri
        if ($lembaga->users()->count() > 0 || $lembaga->santri()->count() > 0) {
            return redirect()->route('lembaga.index')
                ->with('error', 'Lembaga tidak dapat dihapus karena masih memiliki user atau santri.');
        }

        $lembaga->delete();

        return redirect()->route('lembaga.index')
            ->with('success', 'Lembaga berhasil dihapus.');
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
