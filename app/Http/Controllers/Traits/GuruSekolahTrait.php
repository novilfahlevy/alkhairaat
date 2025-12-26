<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\StoreBulkFileRequest;
use App\Jobs\ProcessGuruBulkFile;
use App\Models\Guru;
use App\Models\JabatanGuru;
use App\Models\Alamat;
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
     * Get guru that exist in the system but not yet assigned to this sekolah (AJAX endpoint)
     */
    public function getExistingGuru(Request $request, Sekolah $sekolah)
    {
        $query = Guru::whereDoesntHave('jabatanGuru', function ($q) use ($sekolah) {
            $q->where('id_sekolah', $sekolah->id);
        });

        // Apply search filter (nama, nik, nuptk)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('nuptk', 'like', "%$search%")
                    ->orWhere('kontak_wa_hp', 'like', "%$search%")
                    ->orWhere('status_kepegawaian', 'like', "%$search%")
                    ->orWhere('jenis_kelamin', 'like', "%$search%")
                ;
            });
        }

        $perPage = $request->input('per_page', 20);
        $guru = $query->orderBy('nama')->paginate($perPage);

        // Format for Alpine.js
        return response()->json([
            'data' => $guru->items(),
            'pagination' => [
                'current_page' => $guru->currentPage(),
                'last_page' => $guru->lastPage(),
                'per_page' => $guru->perPage(),
                'total' => $guru->total(),
                'from' => $guru->firstItem(),
                'to' => $guru->lastItem(),
            ],
        ]);
    }

    /**
     * Store selected existing Guru to sekolah (assign JabatanGuru).
     */
    public function storeExistingGuru(Request $request, $sekolah): RedirectResponse
    {
        $validated = $request->validate([
            'guru_ids' => 'required|json',
        ]);

        $guruIds = json_decode($validated['guru_ids'], true);

        if (!is_array($guruIds) || empty($guruIds)) {
            return redirect()->back()->with('error', 'Tidak ada guru yang dipilih');
        }

        try {
            $successCount = 0;
            $skipCount = 0;
            $errors = [];

            foreach ($guruIds as $guruId) {
                // Check if guru exists
                $guru = Guru::find($guruId);
                if (!$guru) {
                    $errors[] = "Guru dengan ID {$guruId} tidak ditemukan";
                    continue;
                }

                // Check if guru already assigned to this sekolah
                $exists = JabatanGuru::where('id_sekolah', $sekolah->id)
                    ->where('id_guru', $guruId)
                    ->where('jenis_jabatan', JabatanGuru::JENIS_JABATAN_GURU)
                    ->exists();

                if ($exists) {
                    $skipCount++;
                    continue;
                }

                // Assign guru ke sekolah (JabatanGuru)
                JabatanGuru::create([
                    'id_guru' => $guruId,
                    'id_sekolah' => $sekolah->id,
                    'jenis_jabatan' => JabatanGuru::JENIS_JABATAN_GURU,
                ]);

                $successCount++;
            }

            $message = "Berhasil menambahkan {$successCount} guru";
            if ($skipCount > 0) {
                $message .= " ({$skipCount} guru sudah terdaftar)";
            }
            if (!empty($errors)) {
                $message .= ". " . implode('; ', $errors);
            }

            return redirect()->route('sekolah.show', $sekolah)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
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

        $guru = Guru::where('nik', $request->input('nik'))->first();

        return response()->json([
            'exists' => $guru !== null,
            'message' => $guru ? 'NIK sudah digunakan oleh <b>' . $guru->nama . '</b>.' : 'NIK tersedia.'
        ]);
    }
}
