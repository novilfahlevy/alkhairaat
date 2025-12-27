<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\StoreExistingGuruRequest;
use App\Http\Requests\StoreBulkFileRequest;
use App\Jobs\ProcessGuruBulkFile;
use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Alamat;
use App\Models\Scopes\GuruSekolahNauanganScope;
use App\Models\Sekolah;
use App\Models\TambahGuruBulkFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait GuruSekolahTrait
{
    /**
     * Show the form for creating a new Guru (manual input).
     */
    public function createGuru(Sekolah $sekolah, Request $request): View
    {
        $tab = $request->input('tab', 'manual');

        if ($tab === 'existing') {
            return view('pages.sekolah.guru.existing', [
                'sekolah' => $sekolah,
                'title' => 'Tambah Guru dari Data yang Ada',
            ]);
        }

        if ($tab === 'file') {
            // Fetch uploaded files for this sekolah
            $uploadedFiles = TambahGuruBulkFile::where('id_sekolah', $sekolah->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('pages.sekolah.guru.tambah-guru-files', [
                'title' => 'Tambah Guru dengan File - ' . $sekolah->nama,
                'sekolah' => $sekolah,
                'uploadedFiles' => $uploadedFiles,
            ]);
        }

        return view('pages.sekolah.guru.create', [
            'sekolah' => $sekolah,
            'title' => 'Tambah Guru',
        ]);
    }

    /**
     * Download template CSV for guru bulk import.
     */
    public function downloadGuruTemplate(): BinaryFileResponse
    {
        $templatePath = storage_path('app/templates/template-guru.csv');

        if (!file_exists($templatePath)) {
            abort(404, 'Template file not found');
        }

        return response()->download($templatePath, 'template-guru.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Store a newly created Guru in storage (manual input).
     */
    public function storeGuru(StoreGuruRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validated();

        // Extract jabatan fields sebelum disimpan ke Guru
        $jenisJabatan = $validated['jenis_jabatan'];
        $keteranganJabatan = $validated['keterangan_jabatan'];
        unset($validated['jenis_jabatan'], $validated['keterangan_jabatan']);

        // Simpan Guru baru
        $guru = Guru::create($validated);

        // Simpan alamat guru (domisili) jika ada input
        $alamatData = [
            'id_guru' => $guru->id,
            'jenis' => Alamat::JENIS_DOMISILI,
            'provinsi' => $validated['alamat_provinsi'] ?? null,
            'kabupaten' => $validated['alamat_kabupaten'] ?? null,
            'kecamatan' => $validated['alamat_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_kelurahan'] ?? null,
            'rt' => $validated['alamat_rt'] ?? null,
            'rw' => $validated['alamat_rw'] ?? null,
            'kode_pos' => $validated['alamat_kode_pos'] ?? null,
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? null
        ];

        if (array_filter($alamatData, fn($v) => $v !== null && $v !== '')) {
            Alamat::create($alamatData);
        }

        // Assign ke sekolah dengan jabatan dan keterangan dari form
        JabatanGuru::create([
            'id_guru' => $guru->id,
            'id_sekolah' => $sekolah->id,
            'jenis_jabatan' => $jenisJabatan,
            'keterangan_jabatan' => $keteranganJabatan,
        ]);

        return redirect()->route('sekolah.show', $sekolah)->with('success', 'Guru berhasil ditambahkan.');
    }

    /**
     * Get guru that exist in the system but not yet assigned to this sekolah (AJAX endpoint for Select2)
     */
    public function getExistingGuru(Request $request, Sekolah $sekolah)
    {
        $query = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class);

        // Apply search filter (nama, nik, nuptk) - Select2 sends search term as 'q'
        if ($request->query('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('nuptk', 'like', "%$search%");
            });
        }

        $guru = $query->orderBy('nama')->limit(20)->get();

        // Format for Select2
        return response()
            ->json([
            'results' => $guru->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama . ' (NIK: ' . ($item->nik ?? '-') . ')'
                ];
            })->values()->all(),
        ]);
    }

    /**
     * Store selected existing Guru to sekolah (assign JabatanGuru).
     */
    public function storeExistingGuru(StoreExistingGuruRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validated();
        $guruId = $validated['id_guru'];

        try {
            // Check if guru exists
            $guru = Guru::find($guruId);
            if (!$guru) {
                return redirect()->back()->with('error', 'Guru yang dipilih tidak ditemukan.');
            }

            // Check if guru already assigned to this sekolah with same position
            $exists = JabatanGuru::where('id_sekolah', $sekolah->id)
                ->where('id_guru', $guruId)
                ->where('jenis_jabatan', $validated['jenis_jabatan'])
                ->where('keterangan_jabatan', $validated['keterangan_jabatan'])
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', "{$guru->nama} sudah terdaftar dengan jabatan ini di sekolah ini.");
            }

            // Assign guru ke sekolah (JabatanGuru)
            JabatanGuru::create([
                'id_guru' => $guruId,
                'id_sekolah' => $sekolah->id,
                'jenis_jabatan' => $validated['jenis_jabatan'],
                'keterangan_jabatan' => $validated['keterangan_jabatan'],
            ]);

            return redirect()->route('sekolah.show', $sekolah)
                ->with('success', "{$guru->nama} berhasil ditambahkan ke sekolah ini.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store guru file for bulk processing.
     */
    public function storeGuruFile(StoreBulkFileRequest $request, Sekolah $sekolah): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $file = $validated['file'];

            // Store file to disk
            $filePath = $file->store('guru-bulk-files', 'local');

            // Create record in tambah_guru_bulk_files table
            $bulkFile = TambahGuruBulkFile::create([
                'file_path' => $filePath,
                'id_sekolah' => $sekolah->id,
                'is_finished' => null,
            ]);

            // Dispatch job untuk processing file di background
            ProcessGuruBulkFile::dispatch($bulkFile);

            return redirect()->back()
                ->with('success', 'File berhasil diunggah dan akan diproses oleh sistem dalam beberapa saat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * API: Cek apakah NIK guru sudah ada
     */
    public function checkNikGuru(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
        ]);

        $guru = Guru::withoutGlobalScope(GuruSekolahNauanganScope::class)->where('nik', $request->input('nik'))->first();

        return response()->json([
            'exists' => $guru !== null,
            'message' => $guru ? 'NIK sudah digunakan oleh <b>' . $guru->nama . '</b>.' : 'NIK tersedia.'
        ]);
    }

    /**
     * Remove guru from sekolah (delete JabatanGuru record only).
     */
    public function deleteGuru(Request $request, Sekolah $sekolah, Guru $guru): RedirectResponse
    {
        try {
            // Find and delete the JabatanGuru record
            $jabatanGuru = JabatanGuru::where('id_sekolah', $sekolah->id)
                ->where('id_guru', $guru->id)
                ->first();

            if (!$jabatanGuru) {
                return redirect()->back()
                    ->with('error', 'Data guru tidak ditemukan di sekolah ini.');
            }

            $namaGuru = $guru->nama;
            $jabatanGuru->delete();

            return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
                ->with('success', 'Guru ' . $namaGuru . ' berhasil dihapus dari sekolah ini.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus guru: ' . $e->getMessage())
                ->withInput();
        }
    }
}
