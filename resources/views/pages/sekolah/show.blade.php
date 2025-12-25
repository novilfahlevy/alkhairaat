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
                <a href="{{ route('sekolah.create-guru', $sekolah) }}"
                    class="bg-blue-500 hover:bg-blue-600 flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Guru
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
                <x-sekolah.peta :alamat="$alamat" :sekolah="$sekolah" />
            </div>
        @endif

        <!-- Galeri Sekolah Section -->
        <x-sekolah.galeri :sekolah="$sekolah" />

        <!-- Murid Table Section -->
        <x-sekolah.tabel-murid :murid="$murid" :sekolah="$sekolah" />

        <!-- Guru Table Section -->
        <x-sekolah.tabel-guru :guru="$guru" :sekolah="$sekolah" />
    </div>
@endsection
