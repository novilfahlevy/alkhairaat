<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    /**
     * Display a listing of kabupaten.
     */
    public function index()
    {
        $kabupaten = Kabupaten::query()
            ->with('provinsi')
            ->orderBy('nama_kabupaten', 'asc')
            ->paginate(15);

        return view('pages.kabupaten.index', [
            'title' => 'Data Kabupaten',
            'kabupaten' => $kabupaten,
        ]);
    }

    /**
     * Show the form for creating a new kabupaten.
     */
    public function create()
    {
        $provinsi = Provinsi::orderBy('nama_provinsi', 'asc')->get();

        return view('pages.kabupaten.create', [
            'title' => 'Tambah Kabupaten',
            'provinsi' => $provinsi,
        ]);
    }

    /**
     * Store a newly created kabupaten in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kabupaten' => 'required|string|max:10|unique:kabupaten,kode_kabupaten',
            'nama_kabupaten' => 'required|string|max:255|unique:kabupaten,nama_kabupaten',
            'provinsi_id' => 'required|exists:provinsi,id',
        ], [
            'kode_kabupaten.required' => 'Kode kabupaten harus diisi',
            'kode_kabupaten.unique' => 'Kode kabupaten sudah digunakan',
            'nama_kabupaten.required' => 'Nama kabupaten harus diisi',
            'nama_kabupaten.unique' => 'Nama kabupaten sudah digunakan',
            'provinsi_id.required' => 'Provinsi harus dipilih',
            'provinsi_id.exists' => 'Provinsi tidak ditemukan',
        ]);

        Kabupaten::create($validated);

        return redirect()->route('kabupaten.index')
            ->with('success', 'Kabupaten berhasil ditambahkan');
    }

    /**
     * Display the specified kabupaten.
     */
    public function show(Kabupaten $kabupaten)
    {
        $kabupaten->load('provinsi', 'lembaga');

        return view('pages.kabupaten.show', [
            'title' => 'Detail Kabupaten',
            'kabupaten' => $kabupaten,
        ]);
    }

    /**
     * Show the form for editing the specified kabupaten.
     */
    public function edit(Kabupaten $kabupaten)
    {
        $provinsi = Provinsi::orderBy('nama_provinsi', 'asc')->get();

        return view('pages.kabupaten.edit', [
            'title' => 'Edit Kabupaten',
            'kabupaten' => $kabupaten,
            'provinsi' => $provinsi,
        ]);
    }

    /**
     * Update the specified kabupaten in storage.
     */
    public function update(Request $request, Kabupaten $kabupaten)
    {
        $validated = $request->validate([
            'kode_kabupaten' => 'required|string|max:10|unique:kabupaten,kode_kabupaten,' . $kabupaten->id,
            'nama_kabupaten' => 'required|string|max:255|unique:kabupaten,nama_kabupaten,' . $kabupaten->id,
            'provinsi_id' => 'required|exists:provinsi,id',
        ], [
            'kode_kabupaten.required' => 'Kode kabupaten harus diisi',
            'kode_kabupaten.unique' => 'Kode kabupaten sudah digunakan',
            'nama_kabupaten.required' => 'Nama kabupaten harus diisi',
            'nama_kabupaten.unique' => 'Nama kabupaten sudah digunakan',
            'provinsi_id.required' => 'Provinsi harus dipilih',
            'provinsi_id.exists' => 'Provinsi tidak ditemukan',
        ]);

        $kabupaten->update($validated);

        return redirect()->route('kabupaten.index')
            ->with('success', 'Kabupaten berhasil diperbarui');
    }

    /**
     * Remove the specified kabupaten from storage.
     */
    public function destroy(Kabupaten $kabupaten)
    {
        // Check if kabupaten has related lembaga
        if ($kabupaten->lembaga()->count() > 0) {
            return redirect()->route('kabupaten.index')
                ->with('error', 'Tidak dapat menghapus kabupaten yang memiliki lembaga');
        }

        $kabupaten->delete();

        return redirect()->route('kabupaten.index')
            ->with('success', 'Kabupaten berhasil dihapus');
    }
}
