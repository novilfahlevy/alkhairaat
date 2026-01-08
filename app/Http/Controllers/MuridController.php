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

        $murids = $query->latest()->paginate(10)->withQueryString();

        // AJAX Response
        if ($request->ajax()) {
            return view('pages.murid._table', compact('murids'))->render();
        }
        
        return view('pages.murid.index', compact('murids'));
    }

    public function create()
    {
        return view('pages.murid.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Lengkap dengan Pesan Bahasa Indonesia
        $validated = $request->validate([
            // --- ATURAN (RULES) ---
            'nisn' => 'required|numeric|unique:murid,nisn', 
            'nama' => 'required|string|max:255',
            'nik'  => 'nullable|string|digits:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status_alumni' => 'nullable|boolean',
            
            // Kontak
            'kontak_wa_hp' => 'nullable|string|max:50',
            'kontak_email' => 'nullable|email|max:255',

            // Data Orang Tua
            'nama_ayah' => 'nullable|string|max:255',
            'nomor_hp_ayah' => 'nullable|string|max:50',
            'nama_ibu'  => 'nullable|string|max:255',
            'nomor_hp_ibu'  => 'nullable|string|max:50',
        ], [
            // --- PESAN ERROR (CUSTOM MESSAGES) ---
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
            'nisn' => 'NISN',
            'nama' => 'Nama Lengkap',
            'nik'  => 'NIK',
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

        // 2. Handling Default Value
        if (!isset($validated['status_alumni'])) {
            $validated['status_alumni'] = 0;
        }

        // 3. Simpan Data
        Murid::create($validated);

        // 4. Redirect
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
        // 1. Validasi dengan Pesan Bahasa Indonesia
        $validated = $request->validate([
            // --- ATURAN (RULES) ---
            // PENTING: 'unique' di sini mengecualikan ID murid yang sedang diedit agar tidak dianggap duplikat diri sendiri
            'nisn' => 'required|numeric|unique:murid,nisn,' . $murid->id, 
            
            'nama' => 'required|string|max:255',
            'nik'  => 'nullable|string|digits:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'status_alumni' => 'nullable|boolean',
            
            // Kontak
            'kontak_wa_hp' => 'nullable|string|max:50',
            'kontak_email' => 'nullable|email|max:255',

            // Data Orang Tua
            'nama_ayah' => 'nullable|string|max:255',
            'nomor_hp_ayah' => 'nullable|string|max:50',
            'nama_ibu'  => 'nullable|string|max:255',
            'nomor_hp_ibu'  => 'nullable|string|max:50',
        ], [
            // --- PESAN ERROR (CUSTOM MESSAGES) ---
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
            // --- NAMA ATRIBUT (CUSTOM ATTRIBUTES) ---
            'nisn' => 'NISN',
            'nama' => 'Nama Lengkap',
            'nik'  => 'NIK',
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

        // 2. Handling Status Alumni
        // Jika checkbox tidak dicentang (atau null), anggap 0.
        $validated['status_alumni'] = $request->filled('status_alumni') ? $request->status_alumni : 0;

        // 3. Proses Update
        try {
            $murid->update($validated);
        } catch (\Exception $e) {
            // Tangkap error database jika ada (misal kolom tidak ditemukan)
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }

        // 4. Redirect
        return redirect()->route('murid.index')->with('success', 'Data murid berhasil diperbarui.');    
    }

    public function destroy(Murid $murid)
    {
        $murid->delete();
        return redirect()->route('murid.index')->with('success', 'Data berhasil dihapus');    
    }
}