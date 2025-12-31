<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\StoreMuridBulkRequest;
use App\Http\Requests\StoreBulkFileRequest;
use App\Http\Requests\UpdateMuridRequest;
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

            return view('pages.sekolah.murid.tambah-murid-files', [
                'title' => 'Tambah Murid dengan File - ' . $sekolah->nama,
                'sekolah' => $sekolah,
                'uploadedFiles' => $uploadedFiles,
            ]);
        }

        if ($tab === 'existing') {
            return view('pages.sekolah.murid.tambah-murid-existing', [
                'title' => 'Pilih Murid yang Ada - ' . $sekolah->nama,
                'sekolah' => $sekolah
            ]);
        }

        return view('pages.sekolah.murid.tambah-murid', [
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

        // Simpan data murid
        $murid = Murid::create($validated);

        // Simpan data sekolah murid
        SekolahMurid::create([
            'id_murid' => $murid->id,
            'id_sekolah' => $sekolah->id,
            'tahun_masuk' => $validated['tahun_masuk'],
            'kelas' => $validated['kelas'] ?? null,
        ]);

        // Simpan alamat asli
        $alamatAsliFields = [
            'id_murid' => $murid->id,
            'jenis' => Alamat::JENIS_ASLI,
            'provinsi' => $validated['alamat_asli_provinsi'] ?? null,
            'kabupaten' => $validated['alamat_asli_kabupaten'] ?? null,
            'kecamatan' => $validated['alamat_asli_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_asli_kelurahan'] ?? null,
            'rt' => $validated['alamat_asli_rt'] ?? null,
            'rw' => $validated['alamat_asli_rw'] ?? null,
            'kode_pos' => $validated['alamat_asli_kode_pos'] ?? null,
            'alamat_lengkap' => $validated['alamat_asli_lengkap'] ?? null,
            'koordinat_x' => $validated['alamat_asli_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_asli_koordinat_y'] ?? null,
        ];

        if (array_filter($alamatAsliFields, fn($v) => $v !== null && $v !== '')) {
            Alamat::create($alamatAsliFields);
        }

        // Simpan alamat domisili
        $alamatDomisiliFields = [
            'id_murid' => $murid->id,
            'jenis' => Alamat::JENIS_DOMISILI,
            'provinsi' => $validated['alamat_domisili_provinsi'] ?? null,
            'kabupaten' => $validated['alamat_domisili_kabupaten'] ?? null,
            'kecamatan' => $validated['alamat_domisili_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_domisili_kelurahan'] ?? null,
            'rt' => $validated['alamat_domisili_rt'] ?? null,
            'rw' => $validated['alamat_domisili_rw'] ?? null,
            'kode_pos' => $validated['alamat_domisili_kode_pos'] ?? null,
            'alamat_lengkap' => $validated['alamat_domisili_lengkap'] ?? null,
            'koordinat_x' => $validated['alamat_domisili_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_domisili_koordinat_y'] ?? null,
        ];

        if (array_filter($alamatDomisiliFields, fn($v) => $v !== null && $v !== '')) {
            Alamat::create($alamatDomisiliFields);
        }

        // Simpan alamat ayah
        $alamatAyahFields = [
            'id_murid' => $murid->id,
            'jenis' => Alamat::JENIS_AYAH,
            'provinsi' => $validated['alamat_ayah_provinsi'] ?? null,
            'kabupaten' => $validated['alamat_ayah_kabupaten'] ?? null,
            'kecamatan' => $validated['alamat_ayah_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_ayah_kelurahan'] ?? null,
            'rt' => $validated['alamat_ayah_rt'] ?? null,
            'rw' => $validated['alamat_ayah_rw'] ?? null,
            'kode_pos' => $validated['alamat_ayah_kode_pos'] ?? null,
            'alamat_lengkap' => $validated['alamat_ayah_lengkap'] ?? null,
            'koordinat_x' => $validated['alamat_ayah_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_ayah_koordinat_y'] ?? null,
        ];

        if (array_filter($alamatAyahFields, fn($v) => $v !== null && $v !== '')) {
            Alamat::create($alamatAyahFields);
        }

        // Simpan alamat ibu
        $alamatIbuFields = [
            'id_murid' => $murid->id,
            'jenis' => Alamat::JENIS_IBU,
            'provinsi' => $validated['alamat_ibu_provinsi'] ?? null,
            'kabupaten' => $validated['alamat_ibu_kabupaten'] ?? null,
            'kecamatan' => $validated['alamat_ibu_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_ibu_kelurahan'] ?? null,
            'rt' => $validated['alamat_ibu_rt'] ?? null,
            'rw' => $validated['alamat_ibu_rw'] ?? null,
            'kode_pos' => $validated['alamat_ibu_kode_pos'] ?? null,
            'alamat_lengkap' => $validated['alamat_ibu_lengkap'] ?? null,
            'koordinat_x' => $validated['alamat_ibu_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_ibu_koordinat_y'] ?? null,
        ];

        if (array_filter($alamatIbuFields, fn($v) => $v !== null && $v !== '')) {
            Alamat::create($alamatIbuFields);
        }

        return redirect()->route('sekolah.show-murid', $sekolah)
            ->with('success', 'Data murid berhasil ditambahkan.');
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
     * Show detail of a murid.
     */
    public function showDetailMurid(Sekolah $sekolah, Murid $murid): View
    {
        $sekolah->load(['kabupaten.provinsi']);
        $murid->load(['sekolahMurid.sekolah']);

        // Get the SekolahMurid record for this specific sekolah
        $sekolahMurid = $murid->sekolahMurid()
            ->where('id_sekolah', $sekolah->id)
            ->first();

        if (!$sekolahMurid) {
            abort(404, 'Murid tidak ditemukan di sekolah ini.');
        }

        // Get alamat records indexed by jenis
        $alamatRecords = Alamat::where('id_murid', $murid->id)->get()->keyBy('jenis');

        return view('pages.sekolah.murid.detail', [
            'title' => 'Detail Murid - ' . $murid->nama,
            'sekolah' => $sekolah,
            'murid' => $murid,
            'sekolahMurid' => $sekolahMurid,
            'alamatAsli' => $alamatRecords->get('asli'),
            'alamatDomisili' => $alamatRecords->get('domisili'),
            'alamatAyah' => $alamatRecords->get('ayah'),
            'alamatIbu' => $alamatRecords->get('ibu'),
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

    /**
     * Show the form for editing a murid.
     */
    public function editMurid(Sekolah $sekolah, Murid $murid): View
    {
        $sekolah->load(['kabupaten.provinsi']);
        $murid->load(['sekolahMurid']);

        // Get the SekolahMurid record for this specific sekolah
        $sekolahMurid = $murid->sekolahMurid()
            ->where('id_sekolah', $sekolah->id)
            ->first();

        if (!$sekolahMurid) {
            abort(404, 'Murid tidak ditemukan di sekolah ini.');
        }

        $alamatRecords = Alamat::where('id_murid', $murid->id)->get()->keyBy('jenis');

        return view('pages.sekolah.murid.edit', [
            'title' => 'Edit data - ' . $murid->nama,
            'sekolah' => $sekolah,
            'murid' => $murid,
            'sekolahMurid' => $sekolahMurid,
            'alamatAsli' => $alamatRecords->get('asli'),
            'alamatDomisili' => $alamatRecords->get('domisili'),
            'alamatAyah' => $alamatRecords->get('ayah'),
            'alamatIbu' => $alamatRecords->get('ibu'),
            'jenisKelaminOptions' => Murid::JENIS_KELAMIN_OPTIONS,
        ]);
    }

    /**
     * Update a murid.
     */
    public function updateMurid(UpdateMuridRequest $request, Sekolah $sekolah, Murid $murid): RedirectResponse
    {
        $validated = $request->validated();

        try {
            // Verify that murid exists in this sekolah
            $sekolahMurid = $murid->sekolahMurid()
                ->where('id_sekolah', $sekolah->id)
                ->first();

            if (!$sekolahMurid) {
                return redirect()->back()
                    ->with('error', 'Murid tidak ditemukan di sekolah ini.');
            }

            // Update murid data
            $muridData = [
                'nama' => $validated['nama'],
                'nisn' => $validated['nisn'],
                'nik' => $validated['nik'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'kontak_wa_hp' => $validated['kontak_wa_hp'] ?? null,
                'kontak_email' => $validated['kontak_email'] ?? null,
                'nama_ayah' => $validated['nama_ayah'] ?? null,
                'nomor_hp_ayah' => $validated['nomor_hp_ayah'] ?? null,
                'nama_ibu' => $validated['nama_ibu'] ?? null,
                'nomor_hp_ibu' => $validated['nomor_hp_ibu'] ?? null,
                'tanggal_update_data' => now(),
            ];

            $murid->update($muridData);

            // Update sekolah_murid data
            $sekolahMuridData = [
                'tahun_masuk' => $validated['tahun_masuk'],
                'tahun_keluar' => $validated['tahun_keluar'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
                'status_kelulusan' => $validated['status_kelulusan'] ?? null,
                'tahun_mutasi_masuk' => $validated['tahun_mutasi_masuk'] ?? null,
                'alasan_mutasi_masuk' => $validated['alasan_mutasi_masuk'] ?? null,
                'tahun_mutasi_keluar' => $validated['tahun_mutasi_keluar'] ?? null,
                'alasan_mutasi_keluar' => $validated['alasan_mutasi_keluar'] ?? null,
            ];

            $sekolahMurid->update($sekolahMuridData);

            // Process alamat records
            $jenisAlamat = ['asli', 'domisili', 'ayah', 'ibu'];

            foreach ($jenisAlamat as $jenis) {
                $prefix = 'alamat_' . $jenis . '_';

                $alamatData = [
                    'provinsi' => $validated[$prefix . 'provinsi'] ?? null,
                    'kabupaten' => $validated[$prefix . 'kabupaten'] ?? null,
                    'kecamatan' => $validated[$prefix . 'kecamatan'] ?? null,
                    'kelurahan' => $validated[$prefix . 'kelurahan'] ?? null,
                    'rt' => $validated[$prefix . 'rt'] ?? null,
                    'rw' => $validated[$prefix . 'rw'] ?? null,
                    'kode_pos' => $validated[$prefix . 'kode_pos'] ?? null,
                    'alamat_lengkap' => $validated[$prefix . 'lengkap'] ?? null,
                    'koordinat_x' => $validated[$prefix . 'koordinat_x'] ?? null,
                    'koordinat_y' => $validated[$prefix . 'koordinat_y'] ?? null,
                ];

                // Only save if there's data
                if (array_filter($alamatData)) {
                    Alamat::updateOrCreate(
                        [
                            'id_murid' => $murid->id,
                            'jenis' => $jenis,
                        ],
                        $alamatData
                    );
                }
            }

            return redirect()->route('sekolah.show-detail-murid', ['sekolah' => $sekolah->id, 'murid' => $murid->id])
                ->with('success', 'Data murid ' . $murid->nama . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui murid: ' . $e->getMessage())
                ->withInput();
        }
    }
}
