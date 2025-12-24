<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKomwilRequest;
use App\Http\Requests\UpdateKomwilRequest;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\EditorList;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KomwilController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', User::ROLE_KOMISARIAT_WILAYAH);
            })
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('pages.komwil.index', [
            'title' => 'Manajemen Komwil',
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        $provinsi = Provinsi::whereHas('kabupaten', function ($query) {
            $query->whereHas('sekolah');
        })
            ->orderBy('nama_provinsi')
            ->get();

        // Get sekolah grouped by kabupaten and provinsi
        $sekolahByProvinsi = [];
        foreach ($provinsi as $prov) {
            $kabupaten = Kabupaten::where('id_provinsi', $prov->id)
                ->whereHas('sekolah')
                ->with(['sekolah' => function ($query) {
                    $query->where('status', Sekolah::STATUS_AKTIF)
                        ->orderBy('jenis_sekolah');
                }])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        return view('pages.komwil.create', [
            'title' => 'Tambah Komwil',
            'sekolahByProvinsi' => $sekolahByProvinsi,
        ]);
    }

    public function store(StoreKomwilRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        $user->assignRole(User::ROLE_KOMISARIAT_WILAYAH);

        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $user->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.komwil.index')
            ->with('success', 'Komwil berhasil ditambahkan.');
    }

    public function edit(User $komwil): View
    {
        $provinsi = Provinsi::whereHas('kabupaten', function ($query) {
            $query->whereHas('sekolah');
        })
            ->orderBy('nama_provinsi')
            ->get();

        // Get sekolah grouped by kabupaten and provinsi
        $sekolahByProvinsi = [];
        foreach ($provinsi as $prov) {
            $kabupaten = Kabupaten::where('id_provinsi', $prov->id)
                ->whereHas('sekolah')
                ->with(['sekolah' => function ($query) {
                    $query->where('status', Sekolah::STATUS_AKTIF)
                        ->orderBy('jenis_sekolah');
                }])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        // Get current sekolah for this user
        $currentSekolahIds = EditorList::where('id_user', $komwil->id)
            ->pluck('id_sekolah')
            ->toArray();

        return view('pages.komwil.edit', [
            'title' => 'Edit Komwil',
            'user' => $komwil,
            'sekolahByProvinsi' => $sekolahByProvinsi,
            'currentSekolahIds' => $currentSekolahIds,
        ]);
    }

    public function update(UpdateKomwilRequest $request, User $komwil): RedirectResponse
    {
        $komwil->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? $request->input('password') : $komwil->password,
        ]);

        $komwil->syncRoles(User::ROLE_KOMISARIAT_WILAYAH);

        EditorList::where('id_user', $komwil->id)->delete();
        $sekolahIds = $request->input('sekolah_ids', []);

        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $komwil->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.komwil.index')
            ->with('success', 'Komwil berhasil diperbarui.');
    }

    public function destroy(User $komwil): RedirectResponse
    {
        EditorList::where('id_user', $komwil->id)->delete();
        $komwil->delete();
        return redirect()->route('manajemen.komwil.index')
            ->with('success', 'Komwil berhasil dihapus.');
    }
}
