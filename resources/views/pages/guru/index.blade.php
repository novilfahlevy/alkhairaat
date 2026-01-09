@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola data guru pendidikan Alkhairaat
            </p>
        </div>
        @if(auth()->user()->isSuperuser() || auth()->user()->isKomisariatWilayah())
            <div>
                <a href="{{ route('guru.create') }}"
                    class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Guru
                </a>
            </div>
        @endif
    </div>

    <!-- Filters Card -->
    <x-ui.card>
        <x-slot:header>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Data</h2>
        </x-slot:header>

        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- Search -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Cari
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama, NIK, NPK, atau NUPTK"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </div>

                <!-- Status -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status Keaktifan
                    </label>
                    <select name="status"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak" {{ request('status') == 'tidak' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Filter & Clear Button -->
                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 h-11 flex-1 rounded-lg px-4 text-sm font-medium text-white transition">
                        Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('guru.index') }}"
                            class="border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 h-11 flex-1 rounded-lg border px-4 text-sm font-medium text-gray-700 transition dark:text-gray-300">
                            Bersihkan Filter
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop Table View -->
        <div class="hidden md:block">
            <x-ui.table>
                <x-slot:thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Guru
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            NIK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            NPK/NUPTK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        </th>
                    </tr>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($guru as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->nama_gelar_depan ? $item->nama_gelar_depan . ' ' : '' }}
                                        {{ $item->nama }}
                                        {{ $item->nama_gelar_belakang ? ', ' . $item->nama_gelar_belakang : '' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->jenis_kelamin_label }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->nik ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->npk ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->nuptk ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($item->status === 'aktif')
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-start space-x-2">
                                    <a href="{{ route('guru.show', $item) }}"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20">
                                        Detail
                                    </a>
                                    @if(auth()->user()->isSuperuser() || (auth()->user()->isSekolah() && $item->jabatanGurus->where('id_sekolah', auth()->user()->sekolah->id)->count() > 0))
                                        <a href="{{ route('guru.edit', $item) }}"
                                            class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300 flex-1 rounded-md bg-yellow-50 px-3 py-2 text-center text-sm font-medium dark:bg-yellow-900/20">
                                            Edit
                                        </a>
                                        <form action="{{ route('guru.destroy', $item) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 flex-1 rounded-md bg-red-50 px-3 py-2 text-center text-sm font-medium dark:bg-red-900/20">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data guru yang ditemukan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>

        <!-- Mobile Card List -->
        <div class="block md:hidden">
            <div class="space-y-4">
                @forelse($guru as $item)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-3 flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->nama_gelar_depan ? $item->nama_gelar_depan . ' ' : '' }}
                                    {{ $item->nama }}
                                    {{ $item->nama_gelar_belakang ? ', ' . $item->nama_gelar_belakang : '' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->nik ?? '-' }}
                                </p>
                            </div>
                            <div class="ml-2 flex items-center space-x-2">
                                @if ($item->status === 'aktif')
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 space-y-2">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                <div>NPK: {{ $item->npk ?? '-' }}</div>
                                <div>NUPTK: {{ $item->nuptk ?? '-' }}</div>
                            </div>

                            @if($item->jabatanGurus->count() > 0)
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    @foreach($item->jabatanGurus as $jabatan)
                                        <div>{{ $jabatan->sekolah->nama ?? '-' }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">{{ $jabatan->jenis_jabatan_label }}</div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500 dark:text-gray-400">Belum ada penugasan sekolah</div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('guru.show', $item) }}"
                                class="flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-sm">Detail</span>
                            </a>
                            @if(auth()->user()->isSuperuser() || (auth()->user()->isSekolah() && $item->jabatanGurus->where('id_sekolah', auth()->user()->sekolah->id)->count() > 0))
                                <a href="{{ route('guru.edit', $item) }}"
                                    class="flex items-center text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="text-sm">Edit</span>
                                </a>
                                <form action="{{ route('guru.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="text-sm">Hapus</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data guru yang ditemukan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </x-ui.card>

    <!-- Pagination -->
    <x-pagination :paginator="$guru" />
@endsection
