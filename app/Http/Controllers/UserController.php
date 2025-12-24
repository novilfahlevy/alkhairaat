<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\EditorList;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::where('id', '!=', Auth::id())->orderBy('name');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->input('role'));
            });
        }

        $users = $query->paginate(20);

        return view('pages.user.index', [
            'title' => 'Manajemen User',
            'users' => $users,
            'roles' => User::ROLES,
            'roleLabels' => [
                'superuser' => 'Superuser',
                'pengurus_besar' => 'Pengurus Besar',
                'komisariat_wilayah' => 'Komisariat Wilayah',
                'komisariat_daerah' => 'Komisariat Daerah',
                'sekolah' => 'Sekolah',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
                ->whereHas('sekolah', function ($query) {
                    $query->orderBy('jenis_sekolah');
                })
                ->with(['sekolah' => function ($query) {
                    $query->where('status', Sekolah::STATUS_AKTIF)->orderBy('nama');
                }])
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        return view('pages.user.create', [
            'title' => 'Tambah User',
            'roles' => array_slice(User::ROLES, 1), // Exclude superuser from creation
            'roleLabels' => [
                'pengurus_besar' => 'Pengurus Besar',
                'komisariat_wilayah' => 'Komisariat Wilayah',
                'komisariat_daerah' => 'Komisariat Daerah',
                'sekolah' => 'Sekolah',
            ],
            'sekolahByProvinsi' => $sekolahByProvinsi,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        // Assign role using spatie/laravel-permission
        $user->assignRole($request->input('role'));

        // Store selected sekolah in editor_lists
        if ($request->input('role') != User::ROLE_PENGURUS_BESAR) {
            $sekolahIds = $request->input('sekolah_ids', []);
            if (!empty($sekolahIds)) {
                $editorListData = array_map(function ($sekolahId) use ($user) {
                    return [
                        'id_user' => $user->id,
                        'id_sekolah' => $sekolahId,
                    ];
                }, $sekolahIds);
    
                EditorList::insert($editorListData);
            }
        } else {
            // For pengurus_besar, ensure no sekolah are assigned
            EditorList::where('id_user', $user->id)->delete();
        }

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
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
                ->whereHas('sekolah', function ($query) {
                    $query->orderBy('jenis_sekolah');
                })
                ->whereHas('sekolah', function ($query) {
                    $query->where('status', Sekolah::STATUS_AKTIF);
                })
                ->orderBy('nama_kabupaten')
                ->get();

            if ($kabupaten->isNotEmpty()) {
                $sekolahByProvinsi[$prov->nama_provinsi] = $kabupaten;
            }
        }

        // Get current sekolah for this user
        $currentSekolahIds = EditorList::where('id_user', $user->id)
            ->pluck('id_sekolah')
            ->toArray();

        return view('pages.user.edit', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => array_slice(User::ROLES, 1), // Exclude superuser from editing
            'roleLabels' => [
                'pengurus_besar' => 'Pengurus Besar',
                'komisariat_wilayah' => 'Komisariat Wilayah',
                'komisariat_daerah' => 'Komisariat Daerah',
                'sekolah' => 'Sekolah',
            ],
            'sekolahByProvinsi' => $sekolahByProvinsi,
            'currentSekolahIds' => $currentSekolahIds,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $updateData = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email')
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = $request->input('password');
        }

        $user->update($updateData);

        // Update role using spatie/laravel-permission
        $user->syncRoles($request->input('role'));

        // Update editor_lists
        EditorList::where('id_user', $user->id)->delete();

        if ($request->input('role') != User::ROLE_PENGURUS_BESAR) {
            $sekolahIds = $request->input('sekolah_ids', []);
            if (!empty($sekolahIds)) {
                $editorListData = array_map(function ($sekolahId) use ($user) {
                    return [
                        'id_user' => $user->id,
                        'id_sekolah' => $sekolahId,
                    ];
                }, $sekolahIds);
    
                EditorList::insert($editorListData);
            }
        }

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting the authenticated user
        if ($user->id === Auth::id()) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Delete associated editor lists
        EditorList::where('id_user', $user->id)->delete();

        // Delete the user
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
