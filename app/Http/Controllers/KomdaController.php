<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKomdaRequest;
use App\Http\Requests\UpdateKomdaRequest;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\EditorList;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KomdaController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', User::ROLE_KOMISARIAT_DAERAH);
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

        return view('pages.komda.index', [
            'title' => 'Manajemen Komda',
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        $provinsi = Provinsi::whereHas('kabupaten', function ($query) {
            $query->whereHas('sekolah', function ($query) {
                $query->whereHas('editorLists', function ($q) {
                    $q->where('id_user', auth()->user()->id);
                });
            });
        })
            ->orderBy('nama_provinsi')
            ->get();

        // Get sekolah grouped by kabupaten and provinsi
        $sekolahByProvinsi = [];
        foreach ($provinsi as $prov) {
            $kabupaten = Kabupaten::where('id_provinsi', $prov->id)
                ->whereHas('sekolah', function ($query) {
                    $query->whereHas('editorLists', function ($q) {
                        $q->where('id_user', auth()->user()->id);
                    });
                })
                ->with(['sekolah' => function ($query) {
                    $query->where('status', Sekolah::STATUS_AKTIF)
                        ->whereHas('editorLists', function ($q) {
                            $q->where('id_user', auth()->user()->id);
                        })
                        ->orderBy('nama');
                }])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        return view('pages.komda.create', [
            'title' => 'Tambah Komda',
            'sekolahByProvinsi' => $sekolahByProvinsi,
        ]);
    }

    public function store(StoreKomdaRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        $user->assignRole(User::ROLE_KOMISARIAT_DAERAH);

        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $user->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.komda.index')
            ->with('success', 'Komda berhasil ditambahkan.');
    }

    public function edit(User $komda): View
    {
        $provinsi = Provinsi::whereHas('kabupaten', function ($query) {
            $query->whereHas('sekolah', function ($query) {
                $query->whereHas('editorLists', function ($q) {
                    $q->where('id_user', auth()->user()->id);
                });
            });
        })
            ->orderBy('nama_provinsi')
            ->get();

        // Get sekolah grouped by kabupaten and provinsi
        $sekolahByProvinsi = [];
        foreach ($provinsi as $prov) {
            $kabupaten = Kabupaten::where('id_provinsi', $prov->id)
                ->whereHas('sekolah', function ($query) {
                    $query->whereHas('editorLists', function ($q) {
                        $q->where('id_user', auth()->user()->id);
                    });
                })
                ->with(['sekolah' => function ($query) {
                    $query->where('status', Sekolah::STATUS_AKTIF)
                        ->whereHas('editorLists', function ($q) {
                            $q->where('id_user', auth()->user()->id);
                        })
                        ->orderBy('nama');
                }])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }
        
        $currentSekolahIds = EditorList::where('id_user', $komda->id)->pluck('id_sekolah')->toArray();

        return view('pages.komda.edit', [
            'title' => 'Edit Komda',
            'user' => $komda,
            'sekolahByProvinsi' => $sekolahByProvinsi,
            'currentSekolahIds' => $currentSekolahIds,
        ]);
    }

    public function update(UpdateKomdaRequest $request, User $komda): RedirectResponse
    {
        $komda->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? $request->input('password') : $komda->password,
        ]);
        $komda->syncRoles(User::ROLE_KOMISARIAT_DAERAH);

        EditorList::where('id_user', $komda->id)->delete();
        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $komda->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.komda.index')
            ->with('success', 'Komda berhasil diperbarui.');
    }

    public function destroy(User $komda): RedirectResponse
    {
        EditorList::where('id_user', $komda->id)->delete();
        $komda->delete();
        return redirect()->route('manajemen.komda.index')
            ->with('success', 'Komda berhasil dihapus.');
    }
}
