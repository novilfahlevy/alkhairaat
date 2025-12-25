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
            <div>
                <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                    {{ $sekolah->nama }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi lengkap sekolah {{ $sekolah->nama }}
                </p>
            </div>
            <div class="my-4 border-b border-gray-200 dark:border-gray-700"></div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('sekolah.create-murid', $sekolah) }}"
                    class="bg-green-500 hover:bg-green-600 flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Murid
                </a>
                <a href="{{ route('sekolah.edit', $sekolah) }}"
                    class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Sekolah
                </a>
                <form action="{{ route('sekolah.destroy', $sekolah) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus sekolah ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
                <a href="{{ route('sekolah.index') }}"
                    class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $muridCount = $sekolah->murid()->count();
            $alumniCount = $sekolah->murid()->where('status_alumni', true)->count();
            $pengelolaCount = $sekolah->editorLists()->count();
            $guruCount = $sekolah->jabatanGuru()->count();
        @endphp

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Murid -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Murid</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $muridCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Murid Aktif -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-green-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alumni</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $alumniCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Murid Non-Aktif -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Guru</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guruCount }}</p>
                    </div>
                </div>
            </div>



            <!-- Total Pengelola -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-500 flex h-12 w-12 items-center justify-center rounded-lg">
                            <i class="fas fa-user-cog text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengelola</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pengelolaCount }}</p>
                    </div>
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
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Jenis Sekolah:
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span
                                        class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $jenisSekolahOptions[$sekolah->jenis_sekolah] ?? 'Tidak diatur' }}
                                    </span>
                                </dd>
                            </div>

                            <!-- Bentuk Pendidikan -->
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Bentuk Pendidikan:
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                    <span
                                        class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ $sekolah->bentuk_pendidikan ?? 'Tidak diatur' }}
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
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                        {{ $sekolah->bank_rekening }}</dd>
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
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">No. Rekening:
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                        {{ $sekolah->nomor_rekening }}</dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">No. Rekening:
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-500 sm:mt-0">-</dd>
                                </div>
                            @endif

                            <!-- Atas Nama -->
                            @if ($sekolah->rekening_atas_nama)
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Atas Nama:
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                        {{ $sekolah->rekening_atas_nama }}</dd>
                                </div>
                            @else
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Atas Nama:
                                    </dt>
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

                        {{-- Alamat Lengkap --}}
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-40">Alamat Lengkap:</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0">
                                {{ $alamat->alamat_lengkap ?? '-' }}</dd>
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

                <!-- Map Section -->
                @if ($alamat->koordinat_x && $alamat->koordinat_y)
                    <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Peta Lokasi</h3>
                        <div id="map-container" class="rounded-lg overflow-hidden shadow-md h-96 bg-gray-100 dark:bg-gray-800"></div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Galeri Sekolah Section -->
        @if ($sekolah->galeri->count() > 0)
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <h2 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">Galeri Sekolah</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($sekolah->galeri as $galeri)
                        <div class="group relative overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
                            <img src="{{ asset('storage/' . $galeri->image_path) }}" alt="Galeri Sekolah"
                                class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-110">

                            <!-- Overlay -->
                            <div
                                class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/40 flex items-center justify-center">
                                <button type="button"
                                    onclick="openLightbox('{{ asset('storage/' . $galeri->image_path) }}')"
                                    class="opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Murid Table Section -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Murid</h2>
            </div>

            <!-- Search and Per-Page Controls -->
            <div class="mb-6 space-y-3 sm:space-y-4">
                <!-- Search Form -->
                <form method="GET" class="flex flex-col gap-2 sm:flex-row">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NISN, atau NIK murid..."
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition sm:px-6">
                        Cari
                    </button>
                    @if (request('search'))
                        <a href="{{ route('sekolah.show', $sekolah) }}"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Desktop Table View -->
            @if ($murid->count() > 0)
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    NISN
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    NIK
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Jenis Kelamin
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Tahun Masuk
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Kelas
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Status Kelulusan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($murid as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->nama }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->nisn }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->nik ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->jenis_kelamin_label }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->pivot->tahun_masuk }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->pivot->kelas ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($item->pivot->status_kelulusan === 'ya')
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Lulus
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Belum Lulus
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="block space-y-4 md:hidden">
                    @foreach ($murid as $item)
                        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="mb-3 flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item->nama }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">NISN: {{ $item->nisn }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">NIK:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->nik ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Jenis Kelamin:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->jenis_kelamin_label }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tahun Masuk:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->pivot->tahun_masuk }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kelas:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->pivot->kelas ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span>
                                        @if ($item->pivot->status_kelulusan === 'ya')
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Lulus
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Belum Lulus
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($murid->hasPages())
                    <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                        <!-- Mobile Layout -->
                        <div class="flex flex-col gap-3 sm:hidden">
                            <!-- Page Info -->
                            <div class="text-center text-sm font-medium text-gray-700 dark:text-gray-400">
                                Halaman <span class="font-semibold">{{ $murid->currentPage() }}</span> dari <span
                                    class="font-semibold">{{ $murid->lastPage() }}</span>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex gap-2">
                                @if ($murid->onFirstPage())
                                    <button disabled
                                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Prev
                                    </button>
                                @else
                                    <a href="{{ $murid->previousPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Prev
                                    </a>
                                @endif

                                @if ($murid->hasMorePages())
                                    <a href="{{ $murid->nextPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                        Next
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <button disabled
                                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                        Next
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Per-Page Selector -->
                            <div class="flex items-center gap-2 sm:gap-3">
                                <label for="per_page"
                                    class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-400">Per
                                    Halaman:</label>
                                <form method="GET" class="flex-1 sm:flex-none">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:w-auto">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                        </option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                        </option>
                                        <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500
                                        </option>
                                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <!-- Desktop Layout -->
                        <div class="hidden sm:flex items-center justify-between gap-4">
                            <div class="flex items-center gap-x-4">
                                <!-- Per-Page Selector -->
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <label for="per_page"
                                        class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-400">Per
                                        Halaman:</label>
                                    <form method="GET" class="flex-1 sm:flex-none">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:w-auto">
                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                                10</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                            </option>
                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                            </option>
                                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500
                                            </option>
                                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>
                                                Semua</option>
                                        </select>
                                    </form>
                                </div>

                                <!-- Previous Button -->
                                @if ($murid->onFirstPage())
                                    <button disabled
                                        class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Sebelumnya</span>
                                    </button>
                                @else
                                    <a href="{{ $murid->previousPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                        class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Sebelumnya</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Page Info -->
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                Halaman <span class="font-semibold">{{ $murid->currentPage() }}</span> dari <span
                                    class="font-semibold">{{ $murid->lastPage() }}</span>
                            </span>

                            <!-- Next Button -->
                            @if ($murid->hasMorePages())
                                <a href="{{ $murid->nextPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                    class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                    <span>Selanjutnya</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <button disabled
                                    class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                    <span>Selanjutnya</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div
                    class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if (request('search'))
                            Tidak ada murid yang ditemukan dengan pencarian "<strong>{{ request('search') }}</strong>".
                        @else
                            Belum ada data murid di sekolah ini.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4 hidden z-999999">
        <div class="max-w-4xl w-full">
            <div class="relative max-h-[90vh] flex flex-col items-center justify-center">
                <div class="overflow-y-auto max-h-screen w-full flex items-center justify-center">
                    <img id="lightbox-image" src="" alt="Galeri Sekolah - Full Size"
                        class="w-full h-auto rounded-lg object-contain">
                </div>
                <button type="button" onclick="closeLightbox()"
                    class="absolute top-4 right-4 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <p id="lightbox-caption" class="mt-4 text-white text-center text-sm hidden"></p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openLightbox(imageSrc, caption = '') {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxCaption = document.getElementById('lightbox-caption');

            lightboxImage.src = imageSrc;
            lightboxCaption.textContent = caption;

            if (caption) {
                lightboxCaption.classList.remove('hidden');
            } else {
                lightboxCaption.classList.add('hidden');
            }

            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when lightbox is open
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close lightbox when clicking outside the image
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Close lightbox with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });

        // Initialize Map with School Coordinates
        @php
            $mapAlamat = $sekolah->alamatList()->where('jenis', 'asli')->first();
        @endphp

        @if ($mapAlamat && $mapAlamat->koordinat_x && $mapAlamat->koordinat_y)
            document.addEventListener('DOMContentLoaded', function() {
                const koordinatX = {{ $mapAlamat->koordinat_x }};
                const koordinatY = {{ $mapAlamat->koordinat_y }};
                const sekolahNama = '{{ $sekolah->nama }}';

                // Initialize map
                const map = L.map('map-container').setView([koordinatX, koordinatY], 15);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                // Add marker at school location
                const marker = L.marker([koordinatX, koordinatY]).addTo(map);
                marker.bindPopup(`<div class="font-semibold text-gray-800">${sekolahNama}</div><div class="text-sm text-gray-600">Lat: ${koordinatX}, Lon: ${koordinatY}</div>`);
                marker.openPopup();

                // Add custom marker icon styling
                const markerIcon = L.AwesomeMarkers ? L.AwesomeMarkers.icon({
                    prefix: 'fa',
                    icon: 'school',
                    markerColor: 'blue'
                }) : undefined;

                if (markerIcon) {
                    marker.setIcon(markerIcon);
                }
            });
        @endif
    </script>
@endpush
