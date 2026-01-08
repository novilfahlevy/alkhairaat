@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
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

        <!-- Breadcrumb -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <span class="text-gray-900 dark:text-white">Data Guru</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div>
                <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                    {{ $title }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Daftar semua guru dalam sistem
                </p>
            </div>
            <div class="my-4 border-b border-gray-200 dark:border-gray-700"></div>
            
            <!-- Search and Filter -->
            <form method="GET" action="{{ route('guru.index') }}" class="mb-4">
                <div class="flex flex-col gap-4 sm:flex-row">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari berdasarkan nama, NIK, NPK, atau NUPTK..."
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <button type="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('guru.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Guru Table -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            @if($guru->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">NIK</th>
                                <th class="px-6 py-3">NPK</th>
                                <th class="px-6 py-3">NUPTK</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Sekolah</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guru as $item)
                                <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4">{{ $loop->iteration + ($guru->currentPage() - 1) * $guru->perPage() }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $item->nama_gelar_depan ? $item->nama_gelar_depan . ' ' : '' }}
                                        {{ $item->nama }}
                                        {{ $item->nama_gelar_belakang ? ', ' . $item->nama_gelar_belakang : '' }}
                                    </td>
                                    <td class="px-6 py-4">{{ $item->nik ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $item->npk ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $item->nuptk ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full px-2 py-1 text-xs font-medium
                                            {{ $item->status === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                               ($item->status === 'nonaktif' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 
                                               'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300') }}">
                                            {{ ucfirst($item->status ?? 'tidak diketahui') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->jabatanGurus->count() > 0)
                                            <div class="space-y-1">
                                                @foreach($item->jabatanGurus as $jabatan)
                                                    <div class="text-sm">
                                                        {{ $jabatan->sekolah->nama ?? '-' }}
                                                        <span class="text-xs text-gray-500">
                                                            ({{ $jabatan->keterangan_jabatan }})
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('guru.show', $item) }}"
                                               class="rounded-lg bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                                Detail
                                            </a>
                                            @if(auth()->user()->isSuperuser() || (auth()->user()->isSekolah() && $item->jabatanGurus->where('id_sekolah', auth()->user()->sekolah->id)->count() > 0))
                                                <a href="{{ route('guru.edit', $item) }}"
                                                   class="rounded-lg bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800">
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $guru->links() }}
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                    <svg class="mx-auto mb-4 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">
                        Tidak ada data guru yang ditemukan.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection