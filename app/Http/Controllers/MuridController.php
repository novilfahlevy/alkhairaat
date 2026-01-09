<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use Illuminate\Http\Request;

class MuridController extends Controller
{
    public function index(Request $request)
    {
        $query = Murid::query();

        // 1. Search (DIPERBAIKI DENGAN GROUPING)
        $query->when($request->search, function ($q) use ($request) {
            // Kita bungkus lagi dengan where(function(...)) agar menjadi satu kesatuan logika
            $q->where(function($sub) use ($request) {
                $sub->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        });

        // 2. Filter Gender
        $query->when($request->jenis_kelamin, function ($q) use ($request) {
            $q->where('jenis_kelamin', $request->jenis_kelamin);
        });

        // 3. Filter Status
        if ($request->has('status_alumni') && $request->status_alumni != '') {
            $query->where('status_alumni', $request->status_alumni);
        }

        $murid = $query->latest()->paginate(100)->withQueryString();

        // AJAX Response
        if ($request->ajax()) {
            return view('pages.murid._table', compact('murid'))->render();
        }
        
        return view('pages.murid.index', compact('murid'));
    }

    public function create()
    {
        return view('pages.murid.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Lengkap
        $validated = $request->validate([
            // --- WAJIB DIISI (SESUAI TANDA BINTANG DI BLADE) ---
            'nisn'          => 'required|numeric|unique:murid,nisn',
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:255', // Diubah jadi required
            'tanggal_lahir' => 'required|date',           // Diubah jadi required
            'status_alumni' => 'required|boolean',        // Diubah jadi required

            // --- OPSIONAL (BOLEH KOSONG) ---
            'nik'           => 'nullable|string|digits:16',
            
            // Kontak
            'kontak_wa_hp'  => 'nullable|string|max:50',
            'kontak_email'  => 'nullable|email|max:255',

            // Data Orang Tua
            'nama_ayah'     => 'nullable|string|max:255',
            'nomor_hp_ayah' => 'nullable|string|max:50',
            'nama_ibu'      => 'nullable|string|max:255',
            'nomor_hp_ibu'  => 'nullable|string|max:50',
        ], [
            // --- PESAN ERROR ---
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'unique'   => ':attribute sudah terdaftar di sistem.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute tidak boleh lebih dari :max karakter.',
            'digits'   => ':attribute harus berisi :digits digit.',
            'email'    => 'Format :attribute tidak valid.',
            'in'       => 'Pilihan :attribute tidak valid.',
            'date'     => ':attribute bukan format tanggal yang benar.',
            'boolean'  => ':attribute harus bernilai benar atau salah.',
        ], [
            // --- LABEL ATRIBUT ---
            'nisn'          => 'NISN',
            'nama'          => 'Nama Lengkap',
            'nik'           => 'NIK',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir'  => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'status_alumni' => 'Status Alumni',
            'kontak_wa_hp'  => 'No. HP/WA Siswa',
            'kontak_email'  => 'Email Siswa',
            'nama_ayah'     => 'Nama Ayah',
            'nomor_hp_ayah' => 'Nomor HP Ayah',
            'nama_ibu'      => 'Nama Ibu',
            'nomor_hp_ibu'  => 'Nomor HP Ibu',
        ]);

        // 2. Simpan Data
        Murid::create($validated);

        // 3. Redirect
        return redirect()->route('murid.index')->with('success', 'Data murid berhasil ditambahkan');
    }

    public function show(Murid $murid)
    {
        return view('pages.murid.show', compact('murid'));
    }

    public function edit(Murid $murid)
    {
        return view('pages.murid.edit', compact('murid'));
    }

    public function update(Request $request, Murid $murid)
    {
        // 1. Validasi (Sama persis dengan Store, kecuali Unique NISN)
        $validated = $request->validate([
            // --- WAJIB DIISI (SESUAI TANDA BINTANG DI BLADE) ---
            // PENTING: Unique mengecualikan ID murid ini sendiri
            'nisn'          => 'required|numeric|unique:murid,nisn,' . $murid->id,
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:255', // Disamakan dengan Store
            'tanggal_lahir' => 'required|date',           // Disamakan dengan Store
            'status_alumni' => 'required|boolean',        // Disamakan dengan Store

            // --- OPSIONAL (BOLEH KOSONG) ---
            'nik'           => 'nullable|string|digits:16',
            
            // Kontak
            'kontak_wa_hp'  => 'nullable|string|max:50',
            'kontak_email'  => 'nullable|email|max:255',

            // Data Orang Tua
            'nama_ayah'     => 'nullable|string|max:255',
            'nomor_hp_ayah' => 'nullable|string|max:50',
            'nama_ibu'      => 'nullable|string|max:255',
            'nomor_hp_ibu'  => 'nullable|string|max:50',
        ], [
            // --- PESAN ERROR ---
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'unique'   => ':attribute sudah terdaftar di sistem.',
            'string'   => ':attribute harus berupa teks.',
            'max'      => ':attribute tidak boleh lebih dari :max karakter.',
            'digits'   => ':attribute harus berisi :digits digit.',
            'email'    => 'Format :attribute tidak valid.',
            'in'       => 'Pilihan :attribute tidak valid.',
            'date'     => ':attribute bukan format tanggal yang benar.',
            'boolean'  => ':attribute harus bernilai benar atau salah.',
        ], [
            // --- LABEL ATRIBUT ---
            'nisn'          => 'NISN',
            'nama'          => 'Nama Lengkap',
            'nik'           => 'NIK',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir'  => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'status_alumni' => 'Status Alumni',
            'kontak_wa_hp'  => 'No. HP/WA Siswa',
            'kontak_email'  => 'Email Siswa',
            'nama_ayah'     => 'Nama Ayah',
            'nomor_hp_ayah' => 'Nomor HP Ayah',
            'nama_ibu'      => 'Nama Ibu',
            'nomor_hp_ibu'  => 'Nomor HP Ibu',
        ]);

        // 2. Proses Update
        try {
            $murid->update($validated);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }

        // 3. Redirect
        return redirect()->route('murid.index')->with('success', 'Data murid berhasil diperbarui.');
    }

    public function destroy(Murid $murid)
    {
        $murid->delete();
        return redirect()->route('murid.index')->with('success', 'Data berhasil dihapus');    
    }
}