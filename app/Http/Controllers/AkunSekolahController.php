<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAkunSekolahRequest;
use App\Http\Requests\UpdateAkunSekolahRequest;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\EditorList;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkunSekolahController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', User::ROLE_SEKOLAH);
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

        return view('pages.akun-sekolah.index', [
            'title' => 'Manajemen Akun Sekolah',
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        $provinsi = Provinsi::naungan()
            ->orderBy('nama_provinsi')
            ->get();

        // Get sekolah grouped by kabupaten and provinsi
        $sekolahByProvinsi = [];
        foreach ($provinsi as $prov) {
            $kabupaten = Kabupaten::naungan()
                ->where('id_provinsi', $prov->id)
                ->with(['sekolah' => fn ($query) => $query->naungan()])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        return view('pages.akun-sekolah.create', [
            'title' => 'Tambah Akun Sekolah',
            'sekolahByProvinsi' => $sekolahByProvinsi,
        ]);
    }

    public function store(StoreAkunSekolahRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);
        $user->assignRole(User::ROLE_SEKOLAH);

        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $user->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.akun-sekolah.index')
            ->with('success', 'Akun sekolah berhasil ditambahkan.');
    }

    public function edit(User $akunSekolah): View
    {
        $provinsi = Provinsi::naungan()
            ->orderBy('nama_provinsi')
            ->get();

        // Get sekolah grouped by kabupaten and provinsi
        $sekolahByProvinsi = [];
        foreach ($provinsi as $prov) {
            $kabupaten = Kabupaten::naungan()
                ->where('id_provinsi', $prov->id)
                ->with(['sekolah' => fn ($query) => $query->naungan()])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        $currentSekolahIds = EditorList::where('id_user', $akunSekolah->id)->pluck('id_sekolah')->toArray();

        return view('pages.akun-sekolah.edit', [
            'title' => 'Edit Akun Sekolah',
            'user' => $akunSekolah,
            'sekolahByProvinsi' => $sekolahByProvinsi,
            'currentSekolahIds' => $currentSekolahIds,
        ]);
    }

    public function update(UpdateAkunSekolahRequest $request, User $akunSekolah): RedirectResponse
    {
        $akunSekolah->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? $request->input('password') : $akunSekolah->password,
        ]);
        $akunSekolah->syncRoles(User::ROLE_SEKOLAH);

        EditorList::where('id_user', $akunSekolah->id)->delete();
        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $akunSekolah->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.akun-sekolah.index')
            ->with('success', 'Akun sekolah berhasil diperbarui.');
    }

    public function destroy(User $akunSekolah): RedirectResponse
    {
        EditorList::where('id_user', $akunSekolah->id)->delete();
        $akunSekolah->delete();
        return redirect()->route('manajemen.akun-sekolah.index')
            ->with('success', 'Akun sekolah berhasil dihapus.');
    }
}
