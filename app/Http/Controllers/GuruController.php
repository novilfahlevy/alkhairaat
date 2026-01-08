<?php

namespace App\Http\Controllers;

use App\Models\Guru;
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
                    'jabatanGurus.sekolah', // Try this first
                    'jabatans.sekolah',     // Or this
                    'positions.sekolah',    // Or this
                    'jabatanGuru.sekolah',  // Or this (singular)
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
        
        // You can implement this if needed for direct teacher creation
        return view('pages.guru.create');
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
        
        // Implement if needed
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
        
        $guru->load(['jabatanGuru.sekolah', 'alamats']);
        
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
        
        return view('pages.guru.edit', [
            'guru' => $guru,
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
        
        // Implement update logic
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
        
        // Implement delete logic
    }
}