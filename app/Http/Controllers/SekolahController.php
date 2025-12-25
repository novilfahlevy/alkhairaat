<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSekolahRequest;
use App\Http\Requests\UpdateSekolahRequest;
use App\Http\Requests\StoreMuridBulkRequest;
use App\Http\Requests\StoreMuridFileRequest;
use App\Jobs\ProcessMuridBulkFile;
use App\Models\Sekolah;
use App\Models\Murid;
use App\Models\SekolahMurid;
use App\Models\Alamat;
use App\Models\GaleriSekolah;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\TambahMuridBulkFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Sekolah::query()->with(['kabupaten.provinsi']);

        // Apply filters based on user role
        $user = Auth::user();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_sekolah', 'like', "%{$search}%");
            });
        }

        // Apply jenis_sekolah filter
        if ($request->filled('jenis_sekolah')) {
            $query->where('jenis_sekolah', $request->input('jenis_sekolah'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $sekolah = $query->orderBy('nama')->paginate(20);

        return view('pages.sekolah.index', [
            'title' => 'Data Sekolah',
            'sekolah' => $sekolah,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $provinsi = Provinsi::orderBy('nama_provinsi')->get();

        return view('pages.sekolah.create', [
            'title' => 'Tambah Sekolah',
            'provinsi' => $provinsi,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSekolahRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Get nama provinsi and kabupaten from database
        $provinsi = Provinsi::find($validated['id_provinsi']);
        $kabupaten = Kabupaten::find($validated['id_kabupaten']);

        // Extract alamat-related fields for alamat table
        $alamatData = [
            'provinsi' => $provinsi?->nama_provinsi,
            'kabupaten' => $kabupaten?->nama_kabupaten,
            'kecamatan' => $validated['alamat_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_kelurahan'] ?? null,
            'rt' => $validated['alamat_rt'] ?? null,
            'rw' => $validated['alamat_rw'] ?? null,
            'kode_pos' => $validated['alamat_kode_pos'] ?? null,
            'koordinat_x' => $validated['alamat_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_koordinat_y'] ?? null,
            'alamat_lengkap' => $validated['alamat'] ?? null,
        ];

        // Remove alamat_* prefixed fields from validated data (keep 'alamat' for sekolah table)
        unset($validated['alamat_kecamatan']);
        unset($validated['alamat_kelurahan']);
        unset($validated['alamat_rt']);
        unset($validated['alamat_rw']);
        unset($validated['alamat_kode_pos']);
        unset($validated['alamat_koordinat_x']);
        unset($validated['alamat_koordinat_y']);

        // Extract galeri files
        $galeriFiles = $request->file('galeri_files') ?? [];
        unset($validated['galeri_files']);

        // Create sekolah
        $sekolah = Sekolah::create($validated);

        // Create alamat record if any alamat data is provided
        if (array_filter($alamatData)) {
            $alamatData['id_sekolah'] = $sekolah->id;
            $alamatData['jenis'] = Alamat::JENIS_ASLI;
            Alamat::create($alamatData);
        }

        // Handle galeri upload
        if (!empty($galeriFiles)) {
            foreach ($galeriFiles as $file) {
                $filePath = $file->store('galeri-sekolah/' . $sekolah->kode_sekolah ?? $sekolah->id, 'public');
                GaleriSekolah::create([
                    'id_sekolah' => $sekolah->id,
                    'image_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekolah $sekolah, Request $request): View
    {
        $sekolah->load(['kabupaten.provinsi', 'alamatList']);

        // Fetch murid with sekolah_murid relationship
        $muridQuery = $sekolah->murid();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $muridQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Get per_page parameter, default to 10
        $perPage = $request->input('per_page', 10);
        if ($perPage === 'all') {
            $murid = $muridQuery->paginate(PHP_INT_MAX);
        } else {
            $perPage = (int) $perPage;
            $murid = $muridQuery->paginate($perPage);
        }

        return view('pages.sekolah.show', [
            'title' => 'Detail Sekolah',
            'sekolah' => $sekolah,
            'murid' => $murid,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekolah $sekolah): View
    {
        $provinsi = Provinsi::orderBy('nama_provinsi')->get();
        $kabupaten = $sekolah->kabupaten?->id_provinsi
            ? Kabupaten::where('id_provinsi', $sekolah->kabupaten->id_provinsi)->orderBy('nama_kabupaten')->get()
            : collect();

        return view('pages.sekolah.edit', [
            'title' => 'Edit Sekolah',
            'sekolah' => $sekolah,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'jenisSekolahOptions' => Sekolah::JENIS_SEKOLAH_OPTIONS,
            'bentukPendidikanOptions' => Sekolah::BENTUK_PENDIDIKAN_OPTIONS,
            'statusOptions' => Sekolah::STATUS_LABELS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSekolahRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validated();

        // Get nama provinsi and kabupaten from database
        $provinsi = Provinsi::find($validated['id_provinsi']);
        $kabupaten = Kabupaten::find($validated['id_kabupaten']);

        // Extract alamat-related fields for alamat table
        $alamatData = [
            'provinsi' => $provinsi?->nama_provinsi,
            'kabupaten' => $kabupaten?->nama_kabupaten,
            'kecamatan' => $validated['alamat_kecamatan'] ?? null,
            'kelurahan' => $validated['alamat_kelurahan'] ?? null,
            'rt' => $validated['alamat_rt'] ?? null,
            'rw' => $validated['alamat_rw'] ?? null,
            'kode_pos' => $validated['alamat_kode_pos'] ?? null,
            'koordinat_x' => $validated['alamat_koordinat_x'] ?? null,
            'koordinat_y' => $validated['alamat_koordinat_y'] ?? null,
            'alamat_lengkap' => $validated['alamat'] ?? null,
        ];

        // Remove alamat_* prefixed fields from validated data (keep 'alamat' for sekolah table)
        unset($validated['alamat_kecamatan']);
        unset($validated['alamat_kelurahan']);
        unset($validated['alamat_rt']);
        unset($validated['alamat_rw']);
        unset($validated['alamat_kode_pos']);
        unset($validated['alamat_koordinat_x']);
        unset($validated['alamat_koordinat_y']);

        // Extract galeri files and deleted files
        $galeriFiles = $request->file('galeri_files') ?? [];
        $deletedGaleriIds = $request->input('deleted_galeri_ids', []);
        unset($validated['galeri_files']);

        // Update sekolah
        $sekolah->update($validated);

        // Update or create alamat record if any alamat data is provided
        if (array_filter($alamatData)) {
            $alamatData['jenis'] = Alamat::JENIS_ASLI;
            $sekolah->alamatList()->updateOrCreate(
                ['jenis' => Alamat::JENIS_ASLI],
                $alamatData
            );
        }

        // Delete galeri yang dipilih untuk dihapus
        if (!empty($deletedGaleriIds)) {
            foreach ($deletedGaleriIds as $galeriId) {
                $galeri = GaleriSekolah::find($galeriId);
                if ($galeri && $galeri->id_sekolah === $sekolah->id) {
                    Storage::disk('public')->delete($galeri->image_path);
                    $galeri->delete();
                }
            }
        }

        // Handle galeri upload
        if (!empty($galeriFiles)) {
            foreach ($galeriFiles as $file) {
                $filePath = $file->store('galeri-sekolah/' . $sekolah->kode_sekolah ?? $sekolah->id, 'public');
                GaleriSekolah::create([
                    'id_sekolah' => $sekolah->id,
                    'image_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('sekolah.show', ['sekolah' => $sekolah->id])
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sekolah $sekolah): RedirectResponse
    {
        // Check if sekolah has users or murid
        // if ($sekolah->guru()->count() > 0 || $sekolah->murid()->count() > 0) {
        //     return redirect()->route('sekolah.index')
        //         ->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki user atau murid.');
        // }

        $sekolah->delete();

        return redirect()->route('sekolah.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    /**
     * Get kabupaten by provinsi (AJAX endpoint)
     */
    public function getKabupaten(Request $request)
    {
        $provinsiId = $request->input('id_provinsi');
        $kabupaten = Kabupaten::where('id_provinsi', $provinsiId)
            ->orderBy('nama_kabupaten')
            ->get(['id', 'nama_kabupaten']);

        return response()->json($kabupaten);
    }

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

        return view('pages.sekolah.tambah-murid', [
            'title' => 'Tambah Murid - ' . $sekolah->nama,
            'sekolah' => $sekolah,
            'jenisKelaminOptions' => Murid::JENIS_KELAMIN_OPTIONS,
            'statusKelulusanOptions' => SekolahMurid::STATUS_KELULUSAN_OPTIONS,
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
        $murid = Murid::where('nisn', $nisn)->first();

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
                    'kelas' => $validated['kelas'] ?? null,
                    'status_kelulusan' => $validated['status_kelulusan'] ?? null,
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
    public function storeMuridFile(StoreMuridFileRequest $request, Sekolah $sekolah): RedirectResponse
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
     * Download template CSV for murid bulk import.
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $templatePath = storage_path('app/templates/template-murid.csv');

        if (!file_exists($templatePath)) {
            abort(404, 'Template file not found');
        }

        return response()->download($templatePath, 'template-murid.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
