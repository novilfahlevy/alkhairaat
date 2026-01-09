<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\StoreExistingGuruRequest;
use App\Http\Requests\StoreBulkFileRequest;
use App\Http\Requests\UpdateGuruRequest;
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
                ->limit(6)
                ->get();

            return view('pages.sekolah.guru.tambah-guru-files', [
                'title' => 'Tambah Guru dengan File - ' . $sekolah->nama,
                'sekolah' => $sekolah,
                'uploadedFiles' => $uploadedFiles
            ]);
        }

        return view('pages.sekolah.guru.create', [
            'sekolah' => $sekolah,
            'title' => 'Tambah Guru',
            'jenisJabatanOptions' => JabatanGuru::JENIS_JABATAN_OPTIONS,
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

        // Extract alamat asli fields sebelum disimpan ke Guru
        $alamatAsliFields = array_filter([
            'alamat_asli_provinsi' => $validated['alamat_asli_provinsi'] ?? null,
            'alamat_asli_kabupaten' => $validated['alamat_asli_kabupaten'] ?? null,
            'alamat_asli_kecamatan' => $validated['alamat_asli_kecamatan'] ?? null,
            'alamat_asli_kelurahan' => $validated['alamat_asli_kelurahan'] ?? null,
            'alamat_asli_rt' => $validated['alamat_asli_rt'] ?? null,
            'alamat_asli_rw' => $validated['alamat_asli_rw'] ?? null,
            'alamat_asli_kode_pos' => $validated['alamat_asli_kode_pos'] ?? null,
            'alamat_asli_lengkap' => $validated['alamat_asli_lengkap'] ?? null,
        ], fn($v) => $v !== null && $v !== '');

        // Extract alamat domisili fields sebelum disimpan ke Guru
        $alamatDomisiliFields = array_filter([
            'alamat_provinsi' => $validated['alamat_provinsi'] ?? null,
            'alamat_kabupaten' => $validated['alamat_kabupaten'] ?? null,
            'alamat_kecamatan' => $validated['alamat_kecamatan'] ?? null,
            'alamat_kelurahan' => $validated['alamat_kelurahan'] ?? null,
            'alamat_rt' => $validated['alamat_rt'] ?? null,
            'alamat_rw' => $validated['alamat_rw'] ?? null,
            'alamat_kode_pos' => $validated['alamat_kode_pos'] ?? null,
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
        ], fn($v) => $v !== null && $v !== '');

        // Remove alamat fields dari validated untuk disimpan ke Guru
        unset(
            $validated['alamat_provinsi'],
            $validated['alamat_kabupaten'],
            $validated['alamat_kecamatan'],
            $validated['alamat_kelurahan'],
            $validated['alamat_rt'],
            $validated['alamat_rw'],
            $validated['alamat_kode_pos'],
            $validated['alamat_lengkap'],
            $validated['alamat_asli_provinsi'],
            $validated['alamat_asli_kabupaten'],
            $validated['alamat_asli_kecamatan'],
            $validated['alamat_asli_kelurahan'],
            $validated['alamat_asli_rt'],
            $validated['alamat_asli_rw'],
            $validated['alamat_asli_kode_pos'],
            $validated['alamat_asli_lengkap']
        );

        // Simpan Guru baru
        $guru = Guru::create($validated);

        // Simpan alamat asli guru jika ada input
        if (!empty($alamatAsliFields)) {
            Alamat::create([
                'id_guru' => $guru->id,
                'jenis' => Alamat::JENIS_ASLI,
                'provinsi' => $alamatAsliFields['alamat_asli_provinsi'] ?? null,
                'kabupaten' => $alamatAsliFields['alamat_asli_kabupaten'] ?? null,
                'kecamatan' => $alamatAsliFields['alamat_asli_kecamatan'] ?? null,
                'kelurahan' => $alamatAsliFields['alamat_asli_kelurahan'] ?? null,
                'rt' => $alamatAsliFields['alamat_asli_rt'] ?? null,
                'rw' => $alamatAsliFields['alamat_asli_rw'] ?? null,
                'kode_pos' => $alamatAsliFields['alamat_asli_kode_pos'] ?? null,
                'alamat_lengkap' => $alamatAsliFields['alamat_asli_lengkap'] ?? null
            ]);
        }

        // Simpan alamat guru (domisili) jika ada input
        if (!empty($alamatDomisiliFields)) {
            Alamat::create([
                'id_guru' => $guru->id,
                'jenis' => Alamat::JENIS_DOMISILI,
                'provinsi' => $alamatDomisiliFields['alamat_provinsi'] ?? null,
                'kabupaten' => $alamatDomisiliFields['alamat_kabupaten'] ?? null,
                'kecamatan' => $alamatDomisiliFields['alamat_kecamatan'] ?? null,
                'kelurahan' => $alamatDomisiliFields['alamat_kelurahan'] ?? null,
                'rt' => $alamatDomisiliFields['alamat_rt'] ?? null,
                'rw' => $alamatDomisiliFields['alamat_rw'] ?? null,
                'kode_pos' => $alamatDomisiliFields['alamat_kode_pos'] ?? null,
                'alamat_lengkap' => $alamatDomisiliFields['alamat_lengkap'] ?? null
            ]);
        }

        // Assign ke sekolah dengan jabatan dan keterangan dari form
        JabatanGuru::create([
            'id_guru' => $guru->id,
            'id_sekolah' => $sekolah->id,
            'jenis_jabatan' => $jenisJabatan,
            'keterangan_jabatan' => $keteranganJabatan,
        ]);;

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
                'file_original_name' => $file->getClientOriginalName(),
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
     * Show detail guru for a specific sekolah.
     */
    public function showDetailGuru(Sekolah $sekolah, Guru $guru): View
    {
        $sekolah->load(['kabupaten.provinsi']);
        $guru->load(['jabatanGuru.sekolah']);

        // Get all JabatanGuru records for this specific sekolah
        $jabatanGuruList = $guru->jabatanGuru()
            ->where('id_sekolah', $sekolah->id)
            ->get();

        if ($jabatanGuruList->isEmpty()) {
            abort(404, 'Guru tidak ditemukan di sekolah ini.');
        }

        // Get alamat records indexed by jenis
        $alamatRecords = Alamat::where('id_guru', $guru->id)->get()->keyBy('jenis');

        return view('pages.sekolah.guru.detail', [
            'title' => 'Detail Guru - ' . $guru->nama,
            'sekolah' => $sekolah,
            'guru' => $guru,
            'jabatanGuruList' => $jabatanGuruList,
            'alamatAsli' => $alamatRecords->get('asli'),
            'alamatDomisili' => $alamatRecords->get('domisili'),
        ]);
    }

    /**
     * Show the form for editing a guru.
     */
    public function editGuru(Sekolah $sekolah, Guru $guru): View
    {
        $sekolah->load(['kabupaten.provinsi']);
        $guru->load(['jabatanGuru']);

        // Get all JabatanGuru records for this specific sekolah
        $jabatanGuruList = $guru->jabatanGuru()
            ->where('id_sekolah', $sekolah->id)
            ->get();

        if ($jabatanGuruList->isEmpty()) {
            abort(404, 'Guru tidak ditemukan di sekolah ini.');
        }

        $alamatRecords = Alamat::where('id_guru', $guru->id)->get()->keyBy('jenis');

        return view('pages.sekolah.guru.edit', [
            'title' => 'Edit data - ' . $guru->nama,
            'sekolah' => $sekolah,
            'guru' => $guru,
            'jabatanGuruList' => $jabatanGuruList,
            'alamatAsli' => $alamatRecords->get('asli'),
            'alamatDomisili' => $alamatRecords->get('domisili'),
            'jenisKelaminOptions' => Guru::JENIS_KELAMIN_OPTIONS,
            'statusOptions' => Guru::STATUS_OPTIONS,
            'statusPerkawinanOptions' => Guru::STATUS_PERKAWINAN_OPTIONS,
            'statusKepegawaianOptions' => Guru::STATUS_KEPEGAWAIAN_OPTIONS,
            'jenisJabatanOptions' => JabatanGuru::JENIS_JABATAN_OPTIONS,
        ]);
    }

    /**
     * Update a guru.
     */
    public function updateGuru(UpdateGuruRequest $request, Sekolah $sekolah, Guru $guru): RedirectResponse
    {
        $validated = $request->validated();

        try {
            // Verify that guru exists in this sekolah
            $jabatanGuru = $guru->jabatanGuru()
                ->where('id_sekolah', $sekolah->id)
                ->first();

            if (!$jabatanGuru) {
                return redirect()->back()
                    ->with('error', 'Guru tidak ditemukan di sekolah ini.');
            }

            // Update guru data
            $guruData = [
                'nama_gelar_depan' => $request->input('nama_gelar_depan'),
                'nama' => $request->input('nama'),
                'nama_gelar_belakang' => $request->input('nama_gelar_belakang'),
                'nik' => $request->input('nik'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'status_perkawinan' => $request->input('status_perkawinan'),
                'status_kepegawaian' => $request->input('status_kepegawaian'),
                'status' => $request->input('status'),
                'npk' => $request->input('npk'),
                'nuptk' => $request->input('nuptk'),
                'kontak_wa_hp' => $request->input('kontak_wa_hp'),
                'kontak_email' => $request->input('kontak_email'),
                'nomor_rekening' => $request->input('nomor_rekening'),
                'rekening_atas_nama' => $request->input('rekening_atas_nama'),
                'bank_rekening' => $request->input('bank_rekening'),
            ];

            $guru->update($guruData);

            // Update jabatan guru
            $jabatanGuru->update([
                'jenis_jabatan' => $request->input('jenis_jabatan'),
                'keterangan_jabatan' => $request->input('keterangan_jabatan'),
            ]);

            // Update alamat asli
            $alamatAsliData = [
                'id_guru' => $guru->id,
                'jenis' => Alamat::JENIS_ASLI,
                'provinsi' => $request->input('alamat_asli_provinsi'),
                'kabupaten' => $request->input('alamat_asli_kabupaten'),
                'kecamatan' => $request->input('alamat_asli_kecamatan'),
                'kelurahan' => $request->input('alamat_asli_kelurahan'),
                'rt' => $request->input('alamat_asli_rt'),
                'rw' => $request->input('alamat_asli_rw'),
                'kode_pos' => $request->input('alamat_asli_kode_pos'),
                'alamat_lengkap' => $request->input('alamat_asli_lengkap'),
                'koordinat_x' => $request->input('alamat_asli_koordinat_x'),
                'koordinat_y' => $request->input('alamat_asli_koordinat_y'),
            ];

            if (array_filter($alamatAsliData, fn($v) => $v !== null && $v !== '')) {
                $alamatAsli = Alamat::where('id_guru', $guru->id)
                    ->where('jenis', Alamat::JENIS_ASLI)
                    ->first();

                if ($alamatAsli) {
                    $alamatAsli->update($alamatAsliData);
                } else {
                    Alamat::create($alamatAsliData);
                }
            }

            // Update alamat domisili
            $alamatDomisiliData = [
                'id_guru' => $guru->id,
                'jenis' => Alamat::JENIS_DOMISILI,
                'provinsi' => $request->input('alamat_domisili_provinsi'),
                'kabupaten' => $request->input('alamat_domisili_kabupaten'),
                'kecamatan' => $request->input('alamat_domisili_kecamatan'),
                'kelurahan' => $request->input('alamat_domisili_kelurahan'),
                'rt' => $request->input('alamat_domisili_rt'),
                'rw' => $request->input('alamat_domisili_rw'),
                'kode_pos' => $request->input('alamat_domisili_kode_pos'),
                'alamat_lengkap' => $request->input('alamat_domisili_lengkap'),
                'koordinat_x' => $request->input('alamat_domisili_koordinat_x'),
                'koordinat_y' => $request->input('alamat_domisili_koordinat_y'),
            ];

            if (array_filter($alamatDomisiliData, fn($v) => $v !== null && $v !== '')) {
                $alamatDomisili = Alamat::where('id_guru', $guru->id)
                    ->where('jenis', Alamat::JENIS_DOMISILI)
                    ->first();

                if ($alamatDomisili) {
                    $alamatDomisili->update($alamatDomisiliData);
                } else {
                    Alamat::create($alamatDomisiliData);
                }
            }

            return redirect()->route('sekolah.show-detail-guru', ['sekolah' => $sekolah->id, 'guru' => $guru->id])
                ->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
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

    /**
     * Add new jabatan for guru at a specific sekolah
     */
    public function addJabatanGuru(Request $request, Sekolah $sekolah, Guru $guru): RedirectResponse
    {
        try {
            // Validate input
            $validated = $request->validate([
                'jenis_jabatan' => 'required|string|in:' . implode(',', array_keys(JabatanGuru::JENIS_JABATAN_OPTIONS)),
                'keterangan_jabatan' => 'nullable|string|max:255',
            ], [
                'jenis_jabatan.required' => 'Jenis jabatan wajib dipilih.',
                'jenis_jabatan.in' => 'Jenis jabatan tidak valid.',
                'keterangan_jabatan.max' => 'Keterangan jabatan maksimal 255 karakter.',
            ]);

            // Check if guru already has the same jenis_jabatan in this sekolah
            if (
                $validated['jenis_jabatan'] === JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH
                || $validated['jenis_jabatan'] === JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH
            ) {
                $existingJabatan = JabatanGuru::where('id_guru', $guru->id)
                    ->where('id_sekolah', $sekolah->id)
                    ->where('jenis_jabatan', $validated['jenis_jabatan'])
                    ->first();
            } else {
                $existingJabatan = null;
            }


            if ($existingJabatan) {
                return redirect()->back()
                    ->with('error', 'Guru sudah memiliki jabatan ' . $validated['jenis_jabatan'] . ' di sekolah ini.')
                    ->withInput();
            }

            // Create new jabatan
            JabatanGuru::create([
                'id_guru' => $guru->id,
                'id_sekolah' => $sekolah->id,
                'jenis_jabatan' => $validated['jenis_jabatan'],
                'keterangan_jabatan' => $validated['keterangan_jabatan'] ?? null,
            ]);

            return redirect()->route('sekolah.show-detail-guru', ['sekolah' => $sekolah->id, 'guru' => $guru->id])
                ->with('success', 'Jabatan berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah jabatan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete jabatan for guru
     */
    public function deleteJabatanGuru(Sekolah $sekolah, JabatanGuru $jabatanGuru): RedirectResponse
    {
        try {
            // Verify jabatan belongs to this sekolah
            if ($jabatanGuru->id_sekolah !== $sekolah->id) {
                return redirect()->back()
                    ->with('error', 'Jabatan tidak ditemukan di sekolah ini.');
            }

            if ($jabatanGuru->guru->jabatanGuru()->count() <= 1) {
                return redirect()->back()
                    ->with('error', 'Guru harus memiliki minimal satu jabatan di sekolah ini.');
            }

            $guruId = $jabatanGuru->id_guru;
            $jabatanGuru->delete();

            return redirect()->route('sekolah.show-detail-guru', ['sekolah' => $sekolah->id, 'guru' => $guruId])
                ->with('success', 'Jabatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus jabatan: ' . $e->getMessage());
        }
    }
}
