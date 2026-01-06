<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Murid;
use App\Models\Sekolah;
use App\Http\Requests\StoreAlumniRequest;
use App\Imports\AlumniImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AlumniController extends Controller
{
    /**
     * Display a listing of the alumni.
     */
    public function index(Request $request)
    {
        $query = Alumni::query();

        // Search by nama or nisn from related murid
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('murid', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter by status (bekerja / tidak)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'bekerja') {
                $query->whereNotNull('profesi_sekarang')
                      ->where('profesi_sekarang', '!=', '');
            } elseif ($status === 'belum') {
                $query->whereNull('profesi_sekarang')
                      ->orWhere('profesi_sekarang', '=', '');
            }
        }

        // Apply naungan scope (role-based filtering)
        $alumni = $query->with(['murid'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->appends($request->query());

        // Status options
        $statusOptions = [
            'bekerja' => 'Sudah Bekerja',
            'belum' => 'Belum Bekerja',
        ];

        return view('pages.alumni.index', [
            'title' => 'Alumni',
            'alumni' => $alumni,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Show the form for creating a new alumni record.
     */
    public function create()
    {
        // Get all murids that are not yet alumni
        $muridOptions = Murid::whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                  ->from('alumni')
                  ->whereColumn('alumni.id_murid', 'murid.id');
        })
        ->orderBy('nama')
        ->limit(1000)
        ->get()
        ->map(function ($murid) {
            return [
                'id' => $murid->id,
                'text' => "{$murid->nama} (NIK: {$murid->nik})"
            ];
        });

        return view('pages.alumni.create', [
            'title' => 'Tambah Alumni',
            'muridOptions' => $muridOptions,
        ]);
    }

    /**
     * Store a newly created alumni record in storage.
     */
    public function store(StoreAlumniRequest $request)
    {
        try {
            $validated = $request->validated();
            
            Alumni::create($validated);

            return redirect()
                ->route('alumni.index')
                ->with('success', 'Data alumni berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data alumni: ' . $e->getMessage());
        }
    }

    /**
     * Store alumni from bulk file upload
     */
    public function storeFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV',
        ]);

        try {
            $file = $request->file('file');
            $import = new AlumniImport();
            
            Excel::import($import, $file);

            $successCount = session('alumni_import_success', 0);
            $failureCount = session('alumni_import_failure', 0);
            $errors = session('alumni_import_errors', []);

            if ($failureCount > 0) {
                return redirect()
                    ->back()
                    ->with('warning', "Import selesai: {$successCount} data berhasil, {$failureCount} data gagal")
                    ->with('import_errors', $errors);
            }

            return redirect()
                ->route('alumni.index')
                ->with('success', "Import selesai: {$successCount} data alumni berhasil ditambahkan");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Download template for bulk import
     */
    public function downloadTemplate()
    {
        $fileName = 'template-alumni-' . date('Y-m-d') . '.csv';
        $filePath = storage_path('app/templates/template-alumni.csv');

        if (!file_exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', 'File template tidak ditemukan. Silakan hubungi administrator.');
        }

        return response()->download($filePath, $fileName);
    }

    /**
     * Display the specified alumni record.
     */
    public function show(Alumni $alumni)
    {
        $alumni->load(['murid']);

        return view('pages.alumni.show', [
            'title' => 'Detail Alumni',
            'alumni' => $alumni,
        ]);
    }

    /**
     * Show the form for editing the specified alumni record.
     */
    public function edit(Alumni $alumni)
    {
        $alumni->load(['murid']);

        return view('pages.alumni.edit', [
            'title' => 'Edit Alumni',
            'alumni' => $alumni,
        ]);
    }

    /**
     * Update the specified alumni record in storage.
     */
    public function update(Request $request, Alumni $alumni)
    {
        try {
            $validated = $request->validate([
                'profesi_sekarang' => 'nullable|string|max:255',
                'nama_tempat_kerja' => 'nullable|string|max:255',
                'kota_tempat_kerja' => 'nullable|string|max:255',
                'riwayat_pekerjaan' => 'nullable|string|max:1000',
            ]);

            $alumni->update($validated);

            return redirect()
                ->route('alumni.show', $alumni)
                ->with('success', 'Data alumni berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data alumni: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified alumni record from storage.
     */
    public function destroy(Alumni $alumni)
    {
        try {
            // Update murid status_alumni to false
            if ($alumni->murid) {
                $alumni->murid->update(['status_alumni' => false]);
            }

            $alumni->delete();

            return redirect()
                ->route('alumni.index')
                ->with('success', 'Data alumni berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data alumni: ' . $e->getMessage());
        }
    }
}
