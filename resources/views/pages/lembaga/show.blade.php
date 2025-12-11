@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $title }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap lembaga {{ $lembaga->nama }}
                    </p>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    @can('manage_lembaga')
                    <a href="{{ route('lembaga.edit', $lembaga) }}" 
                       class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Lembaga
                    </a>
                    @endcan
                    <a href="{{ route('lembaga.index') }}" 
                       class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Info Card -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Informasi Umum</h3>
                        
                        <div class="space-y-4">
                            <!-- Kode Lembaga -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Kode Lembaga:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $lembaga->kode_lembaga }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Nama Lembaga -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Nama:</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white sm:mt-0">{{ $lembaga->nama }}</dd>
                            </div>

                            <!-- Jenjang -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Jenjang:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        {{ $lembaga->jenjang }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Status -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Status:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    @if($lembaga->status === 'aktif')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Lokasi & Kontak</h3>
                        
                        <div class="space-y-4">
                            <!-- Provinsi -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Provinsi:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">{{ $lembaga->provinsi ?? 'Tidak diketahui' }}</dd>
                            </div>

                            <!-- Kabupaten -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Kabupaten:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">{{ $lembaga->kabupaten?->nama_kabupaten ?? 'Tidak diketahui' }}</dd>
                            </div>

                            <!-- Alamat -->
                            @if($lembaga->alamat)
                            <div class="flex flex-col sm:flex-row">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Alamat:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">{{ $lembaga->alamat }}</dd>
                            </div>
                            @endif

                            <!-- Telepon -->
                            @if($lembaga->telepon)
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Telepon:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <a href="tel:{{ $lembaga->telepon }}" class="text-brand-500 hover:text-brand-600">{{ $lembaga->telepon }}</a>
                                </dd>
                            </div>
                            @endif

                            <!-- Email -->
                            @if($lembaga->email)
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32">Email:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <a href="mailto:{{ $lembaga->email }}" class="text-brand-500 hover:text-brand-600">{{ $lembaga->email }}</a>
                                </dd>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $santriCount = $lembaga->santri()->count();
            $userCount = $lembaga->users()->count();
            $santriAktif = $lembaga->santri()->where('status', 'aktif')->count();
            $santriNonAktif = $lembaga->santri()->where('status', 'non_aktif')->count();
        @endphp

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Santri -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Santri</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($santriCount) }}</p>
                    </div>
                </div>
            </div>

            <!-- Santri Aktif -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-green-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Santri Aktif</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($santriAktif) }}</p>
                    </div>
                </div>
            </div>

            <!-- Santri Non-Aktif -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-red-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Santri Non-Aktif</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($santriNonAktif) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengelola -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengelola</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($userCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity or Additional Info -->
        {{-- <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Informasi Tambahan</h3>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Metadata Sistem</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Dibuat pada:</span>
                            <span class="text-gray-900 dark:text-white">{{ $lembaga->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Terakhir diperbarui:</span>
                            <span class="text-gray-900 dark:text-white">{{ $lembaga->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">ID Lembaga:</span>
                            <span class="text-gray-900 dark:text-white">#{{ $lembaga->id }}</span>
                        </div>
                    </div>
                </div>

                @canany(['access_santri', 'access_alumni'])
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Aksi Cepat</h4>
                    <div class="space-y-2">
                        @can('access_santri')
                        <a href="{{ route('santri.index', ['lembaga' => $lembaga->id]) }}" 
                           class="block w-full rounded-lg border border-gray-200 p-3 text-left hover:border-brand-500 hover:bg-brand-50 dark:border-gray-700 dark:hover:border-brand-400 dark:hover:bg-brand-900/10">
                            <div class="flex items-center">
                                <svg class="mr-3 h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Kelola Data Santri</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Melihat dan mengelola data santri lembaga ini</p>
                        </a>
                        @endcan

                        @can('access_alumni')
                        <a href="{{ route('alumni.index', ['lembaga' => $lembaga->id]) }}" 
                           class="block w-full rounded-lg border border-gray-200 p-3 text-left hover:border-brand-500 hover:bg-brand-50 dark:border-gray-700 dark:hover:border-brand-400 dark:hover:bg-brand-900/10">
                            <div class="flex items-center">
                                <svg class="mr-3 h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Kelola Data Alumni</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Melihat dan mengelola data alumni lembaga ini</p>
                        </a>
                        @endcan
                    </div>
                </div>
                @endcanany
            </div>
        </div> --}}
    </div>
@endsection