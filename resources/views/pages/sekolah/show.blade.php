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
        
        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $sekolah->nama }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap sekolah {{ $sekolah->nama }}
                    </p>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('sekolah.edit', $sekolah) }}"
                        class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Sekolah
                    </a>
                    <a href="{{ route('sekolah.index') }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
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
                            <!-- Nama Sekolah -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Nama:</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white sm:mt-0">
                                    {{ $sekolah->nama }}</dd>
                            </div>

                            <!-- Kode Sekolah -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Kode Sekolah:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span
                                        class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $sekolah->kode_sekolah }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Jenis Sekolah -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Jenis Sekolah:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span
                                        class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $sekolah->jenisSekolah?->nama_jenis ?? 'Tidak diatur' }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Bentuk Pendidikan -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Bentuk Pendidikan:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span
                                        class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ $sekolah->bentukPendidikan?->nama ?? 'Tidak diatur' }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Status -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Status:</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    @if ($sekolah->status === 'aktif')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <!-- Keterangan -->
                            @if ($sekolah->keterangan)
                                <div class="flex flex-col">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $sekolah->keterangan }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Kontak</h3>

                        <div class="space-y-4">
                            <!-- Telepon -->
                            @if ($sekolah->telepon)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Telepon:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                        <a href="tel:{{ $sekolah->telepon }}"
                                            class="text-brand-500 hover:text-brand-600">{{ $sekolah->telepon }}</a>
                                    </dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Telepon:</dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif

                            <!-- Email -->
                            @if ($sekolah->email)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Email:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                        <a href="mailto:{{ $sekolah->email }}"
                                            class="text-brand-500 hover:text-brand-600">{{ $sekolah->email }}</a>
                                    </dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Email:</dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif

                            <!-- Website -->
                            @if ($sekolah->website)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Website:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                        <a href="{{ $sekolah->website }}" target="_blank" rel="noopener noreferrer"
                                            class="text-brand-500 hover:text-brand-600">{{ $sekolah->website }}</a>
                                    </dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Website:</dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Informasi Rekening</h3>

                        <div class="space-y-4">
                            <!-- Bank -->
                            @if ($sekolah->bank_rekening)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Bank:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">{{ $sekolah->bank_rekening }}</dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Bank:</dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif

                            <!-- Nomor Rekening -->
                            @if ($sekolah->nomor_rekening)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">No. Rekening:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">{{ $sekolah->nomor_rekening }}</dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">No. Rekening:</dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif

                            <!-- Atas Nama -->
                            @if ($sekolah->rekening_atas_nama)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Atas Nama:</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">{{ $sekolah->rekening_atas_nama }}</dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Atas Nama:</dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Alamat Section -->
        @php
            $alamat = $sekolah->alamatList()->where('jenis', 'asli')->first();
        @endphp
        
        @if ($alamat)
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <h2 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Sekolah</h2>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Provinsi -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Provinsi:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->provinsi ?? '-' }}</dd>
                        </div>

                        <!-- Kabupaten -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Kabupaten:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->kabupaten ?? '-' }}</dd>
                        </div>

                        <!-- Kecamatan -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Kecamatan:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->kecamatan ?? '-' }}</dd>
                        </div>

                        <!-- Kelurahan -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Kelurahan:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->kelurahan ?? '-' }}</dd>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- RT/RW -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">RT/RW:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                @if ($alamat->rt || $alamat->rw)
                                    {{ $alamat->rt ?? '-' }} / {{ $alamat->rw ?? '-' }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>

                        <!-- Kode Pos -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Kode Pos:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->kode_pos ?? '-' }}</dd>
                        </div>

                        <!-- Koordinat X -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Koordinat X:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->koordinat_x ?? '-' }}</dd>
                        </div>

                        <!-- Koordinat Y -->
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Koordinat Y:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->koordinat_y ?? '-' }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                @if ($alamat->alamat_lengkap)
                    <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <div class="flex flex-col">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Lengkap:</dt>
                            <dd class="mt-2 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                                {{ $alamat->alamat_lengkap }}</dd>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Statistics Cards -->
        @php
            $muridCount = $sekolah->murid()->count();
        @endphp

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Murid -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Murid</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">0</p>
                    </div>
                </div>
            </div>

            <!-- Murid Aktif -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-green-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Murid Aktif</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">0</p>
                    </div>
                </div>
            </div>

            <!-- Murid Non-Aktif -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-red-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Murid Non-Aktif</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">0</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengelola -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengelola</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
