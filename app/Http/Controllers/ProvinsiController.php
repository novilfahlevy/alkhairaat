<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of provinsi.
     */
    public function index()
    {
        $provinsi = Provinsi::query()
            ->orderBy('nama_provinsi', 'asc')
            ->paginate(15);

        return view('pages.provinsi.index', [
            'title' => 'Data Provinsi',
            'provinsi' => $provinsi,
        ]);
    }

    /**
     * Show the form for creating a new provinsi.
     */
    public function create()
    {
        return view('pages.provinsi.create', [
            'title' => 'Tambah Provinsi',
        ]);
    }

    /**
     * Store a newly created provinsi in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_provinsi' => 'required|string|max:10|unique:provinsi,kode_provinsi',
            'nama_provinsi' => 'required|string|max:255|unique:provinsi,nama_provinsi',
        ], [
            'kode_provinsi.required' => 'Kode provinsi harus diisi',
            'kode_provinsi.unique' => 'Kode provinsi sudah digunakan',
            'nama_provinsi.required' => 'Nama provinsi harus diisi',
            'nama_provinsi.unique' => 'Nama provinsi sudah digunakan',
        ]);

        Provinsi::create($validated);

        return redirect()->route('provinsi.index')
            ->with('success', 'Provinsi berhasil ditambahkan');
    }

    /**
     * Display the specified provinsi.
     */
    public function show(Provinsi $provinsi)
    {
        return view('pages.provinsi.show', [
            'title' => 'Detail Provinsi',
            'provinsi' => $provinsi,
        ]);
    }

    /**
     * Show the form for editing the specified provinsi.
     */
    public function edit(Provinsi $provinsi)
    {
        return view('pages.provinsi.edit', [
            'title' => 'Edit Provinsi',
            'provinsi' => $provinsi,
        ]);
    }

    /**
     * Update the specified provinsi in storage.
     */
    public function update(Request $request, Provinsi $provinsi)
    {
        $validated = $request->validate([
            'kode_provinsi' => 'required|string|max:10|unique:provinsi,kode_provinsi,' . $provinsi->id,
            'nama_provinsi' => 'required|string|max:255|unique:provinsi,nama_provinsi,' . $provinsi->id,
        ], [
            'kode_provinsi.required' => 'Kode provinsi harus diisi',
            'kode_provinsi.unique' => 'Kode provinsi sudah digunakan',
            'nama_provinsi.required' => 'Nama provinsi harus diisi',
            'nama_provinsi.unique' => 'Nama provinsi sudah digunakan',
        ]);

        $provinsi->update($validated);

        return redirect()->route('provinsi.index')
            ->with('success', 'Provinsi berhasil diperbarui');
    }

    /**
     * Remove the specified provinsi from storage.
     */
    public function destroy(Provinsi $provinsi)
    {
        // Check if provinsi has related kabupaten
        if ($provinsi->kabupaten()->count() > 0) {
            return redirect()->route('provinsi.index')
                ->with('error', 'Tidak dapat menghapus provinsi yang memiliki kabupaten');
        }

        $provinsi->delete();

        return redirect()->route('provinsi.index')
            ->with('success', 'Provinsi berhasil dihapus');
    }
}
