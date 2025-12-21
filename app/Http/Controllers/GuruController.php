<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\UpdateGuruRequest;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\EditorList;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->whereHas('roles', function ($q) {
                $q->where('name', User::ROLE_GURU);
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

        return view('pages.guru.index', [
            'title' => 'Manajemen Guru',
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

        return view('pages.guru.create', [
            'title' => 'Tambah Guru',
            'sekolahByProvinsi' => $sekolahByProvinsi,
        ]);
    }

    public function store(StoreGuruRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);
        $user->assignRole(User::ROLE_GURU);

        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $user->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.guru.index')
            ->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(User $guru): View
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

        $currentSekolahIds = EditorList::where('id_user', $guru->id)->pluck('id_sekolah')->toArray();

        return view('pages.guru.edit', [
            'title' => 'Edit Guru',
            'user' => $guru,
            'sekolahByProvinsi' => $sekolahByProvinsi,
            'currentSekolahIds' => $currentSekolahIds,
        ]);
    }

    public function update(UpdateGuruRequest $request, User $guru): RedirectResponse
    {
        $guru->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? $request->input('password') : $guru->password,
        ]);
        $guru->syncRoles(User::ROLE_GURU);

        EditorList::where('id_user', $guru->id)->delete();
        $sekolahIds = $request->input('sekolah_ids', []);
        if (!empty($sekolahIds)) {
            foreach ($sekolahIds as $idSekolah) {
                EditorList::create([
                    'id_user' => $guru->id,
                    'id_sekolah' => $idSekolah,
                ]);
            }
        }

        return redirect()->route('manajemen.guru.index')
            ->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(User $guru): RedirectResponse
    {
        EditorList::where('id_user', $guru->id)->delete();
        $guru->delete();
        return redirect()->route('manajemen.guru.index')
            ->with('success', 'Guru berhasil dihapus.');
    }
}
