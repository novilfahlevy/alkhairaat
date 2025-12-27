<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\StoreMuridBulkRequest;
use App\Http\Requests\StoreBulkFileRequest;
use App\Jobs\ProcessMuridBulkFile;
use App\Models\Murid;
use App\Models\SekolahMurid;
use App\Models\Alamat;
use App\Models\Scopes\MuridNauanganScope;
use App\Models\Scopes\NauanganSekolahScope;
use App\Models\Sekolah;
use App\Models\TambahMuridBulkFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait MuridSekolahTrait
{
    /**
     * Show the form for creating multiple murid in bulk.
     */
    public function createMurid(Sekolah $sekolah, Request $request): View
    {
        $tab = $request->query('tab', 'manual');

        if ($tab === 'file') {
            // Fetch uploaded files for this sekolah
            $uploadedFiles = TambahMuridBulkFile::where('id_sekolah', $sekolah->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('pages.sekolah.tambah-murid-files', [
                'title' => 'Tambah Murid dengan File - ' . $sekolah->nama,
                'sekolah' => $sekolah,
                'uploadedFiles' => $uploadedFiles,
            ]);
        }

        if ($tab === 'existing') {
            return view('pages.sekolah.tambah-murid-existing', [
                'title' => 'Pilih Murid yang Ada - ' . $sekolah->nama,
                'sekolah' => $sekolah
            ]);
        }

        return view('pages.sekolah.tambah-murid', [
            'title' => 'Tambah Murid - ' . $sekolah->nama,
            'sekolah' => $sekolah,
            'jenisKelaminOptions' => Murid::JENIS_KELAMIN_OPTIONS
        ]);
    }

    /**
     * Check if NISN already exists in the system.
     */
    public function checkNisn(Request $request, Sekolah $sekolah)
    {
        $nisn = $request->input('nisn', '');

        if (empty($nisn)) {
            return response()->json([
                'exists' => false,
                'message' => ''
            ]);
        }

        // Check if NISN exists
        $murid = Murid::withoutGlobalScope(MuridNauanganScope::class)->where('nisn', $nisn)->first();

        if ($murid) {
            return response()->json([
                'exists' => true,
                'message' => 'NISN ini sudah terdaftar atas nama <b>' . $murid->nama . '</b>.'
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'NISN ini belum terdaftar.'
        ]);
    }

    /**
     * Store a single murid.
     */
    public function storeMurid(StoreMuridBulkRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validated();

        try {
            // Create or find murid by NISN
            $murid = Murid::firstOrCreate(
                ['nisn' => $validated['nisn']],
                [
                    'nama' => $validated['nama'],
                    'nik' => $validated['nik'] ?? null,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'nama_ayah' => $validated['nama_ayah'] ?? null,
                    'nomor_hp_ayah' => $validated['nomor_hp_ayah'] ?? null,
                    'nama_ibu' => $validated['nama_ibu'] ?? null,
                    'nomor_hp_ibu' => $validated['nomor_hp_ibu'] ?? null,
                    'kontak_wa_hp' => $validated['kontak_wa_hp'] ?? null,
                    'kontak_email' => $validated['kontak_email'] ?? null,
                    'tanggal_update_data' => now(),
                ]
            );

            // Create sekolah_murid record
            SekolahMurid::firstOrCreate(
                [
                    'id_murid' => $murid->id,
                    'id_sekolah' => $sekolah->id,
                ],
                [
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'kelas' => $validated['kelas'] ?? null
                ]
            );

            // Create alamat record if any address data is provided
            $alamatData = [
                'provinsi' => $validated['provinsi'] ?? null,
                'kabupaten' => $validated['kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kelurahan' => $validated['kelurahan'] ?? null,
                'rt' => $validated['rt'] ?? null,
                'rw' => $validated['rw'] ?? null,
                'kode_pos' => $validated['kode_pos'] ?? null,
                'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
                'koordinat_x' => $validated['koordinat_x'] ?? null,
                'koordinat_y' => $validated['koordinat_y'] ?? null,
            ];

            if (array_filter($alamatData)) {
                Alamat::firstOrCreate(
                    [
                        'id_murid' => $murid->id,
                        'jenis' => Alamat::JENIS_ASLI,
                    ],
                    $alamatData
                );
            }

            return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
                ->with('success', 'Murid ' . $validated['nama'] . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan murid: ' . $e->getMessage());
        }
    }

    /**
     * Store murid file for bulk processing.
     */
    public function storeMuridFile(StoreBulkFileRequest $request, Sekolah $sekolah): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $file = $validated['file'];

            // Store file to disk
            $filePath = $file->store('murid-bulk-files', 'local');

            // Create record in tambah_murid_bulk_files table
            $bulkFile = TambahMuridBulkFile::create([
                'file_path' => $filePath,
                'id_sekolah' => $sekolah->id,
                'is_finished' => null,
            ]);

            // Dispatch job untuk processing file di background
            ProcessMuridBulkFile::dispatch($bulkFile);

            return redirect()->back()
                ->with('success', 'File berhasil diunggah dan akan diproses oleh sistem dalam beberapa saat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Get murid that exist in the system for this sekolah.
     */
    public function getExistingMurid(Request $request, Sekolah $sekolah)
    {
        $query = Murid::withoutGlobalScope(MuridNauanganScope::class)
            ->whereDoesntHave('sekolahMurid', function ($q) use ($sekolah) {
                $q->where('id_sekolah', $sekolah->id);
            })
            ->orderBy('nama', 'asc');

        // Apply search filter (nama, nisn)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $perPage = $request->input('per_page', 20);
        $murid = $query->paginate($perPage);

        return response()->json([
            'data' => $murid->items(),
            'pagination' => [
                'current_page' => $murid->currentPage(),
                'last_page' => $murid->lastPage(),
                'per_page' => $murid->perPage(),
                'total' => $murid->total(),
                'from' => $murid->firstItem(),
                'to' => $murid->lastItem(),
            ]
        ]);
    }

    /**
     * Store selected murid that already exist in the system to a sekolah.
     */
    public function storeExistingMurid(Request $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validate([
            'murid_ids' => 'required|json',
            'tahun_masuk' => 'required|integer|min:1990|max:' . now()->year,
            'kelas' => 'nullable|string|max:50'
        ]);

        $muridIds = json_decode($validated['murid_ids'], true);
        $tahunMasuk = $validated['tahun_masuk'];
        $kelas = $validated['kelas'] ?? null;

        if (!is_array($muridIds) || empty($muridIds)) {
            return redirect()->back()
                ->with('error', 'Tidak ada murid yang dipilih');
        }

        try {
            $successCount = 0;
            $skipCount = 0;
            $errors = [];

            foreach ($muridIds as $muridId) {
                // Check if murid exists
                $murid = Murid::find($muridId);
                if (!$murid) {
                    $errors[] = "Murid dengan ID {$muridId} tidak ditemukan";
                    continue;
                }

                // Check if murid already registered in this sekolah
                $exists = SekolahMurid::where('id_sekolah', $sekolah->id)
                    ->where('id_murid', $muridId)
                    ->exists();

                if ($exists) {
                    $skipCount++;
                    continue;
                }

                // Create new sekolah_murid record
                SekolahMurid::create([
                    'id_murid' => $muridId,
                    'id_sekolah' => $sekolah->id,
                    'tahun_masuk' => $tahunMasuk,
                    'kelas' => $kelas,
                ]);

                $successCount++;
            }

            $message = "Berhasil menambahkan {$successCount} murid";
            if ($skipCount > 0) {
                $message .= " ({$skipCount} murid sudah terdaftar)";
            }

            return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download template CSV for murid bulk import.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $templatePath = storage_path('app/templates/template-murid.csv');

        if (!file_exists($templatePath)) {
            abort(404, 'Template file not found');
        }

        return response()->download($templatePath, 'template-murid.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Remove murid from sekolah (delete SekolahMurid record only).
     */
    public function deleteMurid(Request $request, Sekolah $sekolah, Murid $murid): RedirectResponse
    {
        try {
            // Find and delete the SekolahMurid record
            $sekolahMurid = SekolahMurid::where('id_sekolah', $sekolah->id)
                ->where('id_murid', $murid->id)
                ->first();

            if (!$sekolahMurid) {
                return redirect()->back()
                    ->with('error', 'Data murid tidak ditemukan di sekolah ini.');
            }

            $namaMusid = $murid->nama;
            $sekolahMurid->delete();

            return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
                ->with('success', 'Murid ' . $namaMusid . ' berhasil dihapus dari sekolah ini.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus murid: ' . $e->getMessage())
                ->withInput();
        }
    }
}