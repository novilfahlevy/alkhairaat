@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Breadcrumb -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('sekolah.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    Sekolah
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show', $sekolah) }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    {{ $sekolah->nama }}
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show-murid', $sekolah) }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    Data Murid
                </a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">{{ $murid->nama }}</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $murid->nama }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        NISN: <strong>{{ $murid->nisn }}</strong>
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('sekolah.show-murid', $sekolah) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('sekolah.edit-murid', ['sekolah' => $sekolah->id, 'murid' => $murid->id]) }}"
                        class="flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data
                    </a>
                </div>
            </div>
        </div>

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

        <!-- Data Murid Section -->
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Data Pribadi -->
            <div class="md:col-span-2">
                <x-ui.card>
                    <x-slot name="header">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Pribadi</h2>
                    </x-slot>

                    <div class="space-y-6">
                        <!-- Nama -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nama Lengkap
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $murid->nama ?? '-' }}
                            </p>
                        </div>

                        <!-- NISN -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                NISN
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $murid->nisn ?? '-' }}
                            </p>
                        </div>

                        <!-- NIK -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                NIK
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $murid->nik ?? '-' }}
                            </p>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jenis Kelamin
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                <span
                                    class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $murid->jenis_kelamin_label ?? '-' }}
                                </span>
                            </p>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tempat Lahir
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $murid->tempat_lahir ?? '-' }}
                            </p>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Lahir
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($murid->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($murid->tanggal_lahir)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Status Alumni -->
                        {{-- <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status Kelulusan
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($murid->status_kelulusan === 'ya')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Sudah Alumni
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Tidak Lulus
                                    </span>
                                @endif
                            </p>
                        </div> --}}
                    </div>
                </x-ui.card>
            </div>

            <!-- Data Kontak -->
            <div>
                <x-ui.card>
                    <x-slot name="header">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Kontak</h2>
                    </x-slot>

                    <div class="space-y-6">
                        <!-- WhatsApp / HP -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                WhatsApp / HP
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($murid->kontak_wa_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $murid->kontak_wa_hp) }}"
                                        target="_blank"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                        {{ $murid->kontak_wa_hp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($murid->kontak_email)
                                    <a href="mailto:{{ $murid->kontak_email }}"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                        {{ $murid->kontak_email }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Terakhir Update -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Terakhir Update
                            </label>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                @if ($murid->tanggal_update_data)
                                    {{ \Carbon\Carbon::parse($murid->tanggal_update_data)->translatedFormat('d F Y H:i') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>

        <!-- Data Orang Tua -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Data Ayah -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Ayah</h2>
                </x-slot>

                <div class="space-y-6">
                    <!-- Nama Ayah -->
                    <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Ayah
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $murid->nama_ayah ?? '-' }}
                        </p>
                    </div>

                    <!-- Nomor HP Ayah -->
                    <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor HP
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            @if ($murid->nomor_hp_ayah)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $murid->nomor_hp_ayah) }}"
                                    target="_blank"
                                    class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                    {{ $murid->nomor_hp_ayah }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Data Ibu -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Ibu</h2>
                </x-slot>

                <div class="space-y-6">
                    <!-- Nama Ibu -->
                    <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Ibu
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $murid->nama_ibu ?? '-' }}
                        </p>
                    </div>

                    <!-- Nomor HP Ibu -->
                    <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor HP
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            @if ($murid->nomor_hp_ibu)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $murid->nomor_hp_ibu) }}"
                                    target="_blank"
                                    class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                    {{ $murid->nomor_hp_ibu }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Data Alamat -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Alamat Asli -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Asli</h2>
                </x-slot>

                <div class="space-y-6">
                    @if ($alamatAsli)
                        <!-- Provinsi -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAsli->provinsi ?? '-' }}</p>
                        </div>

                        <!-- Kabupaten -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAsli->kabupaten ?? '-' }}
                            </p>
                        </div>

                        <!-- Kecamatan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAsli->kecamatan ?? '-' }}
                            </p>
                        </div>

                        <!-- Kelurahan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAsli->kelurahan ?? '-' }}
                            </p>
                        </div>

                        <!-- RT/RW -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">RT / RW</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($alamatAsli->rt || $alamatAsli->rw)
                                    {{ $alamatAsli->rt ?? '-' }} / {{ $alamatAsli->rw ?? '-' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Kode Pos -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Pos</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAsli->kode_pos ?? '-' }}</p>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                                Lengkap</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatAsli->alamat_lengkap ?? '-' }}</p>
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400">Belum ada data</p>
                    @endif
                </div>
            </x-ui.card>

            <!-- Alamat Domisili -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Domisili</h2>
                </x-slot>

                <div class="space-y-6">
                    @if ($alamatDomisili)
                        <!-- Provinsi -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatDomisili->provinsi ?? '-' }}
                            </p>
                        </div>

                        <!-- Kabupaten -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatDomisili->kabupaten ?? '-' }}</p>
                        </div>

                        <!-- Kecamatan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatDomisili->kecamatan ?? '-' }}</p>
                        </div>

                        <!-- Kelurahan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatDomisili->kelurahan ?? '-' }}</p>
                        </div>

                        <!-- RT/RW -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">RT / RW</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($alamatDomisili->rt || $alamatDomisili->rw)
                                    {{ $alamatDomisili->rt ?? '-' }} / {{ $alamatDomisili->rw ?? '-' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Kode Pos -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Pos</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatDomisili->kode_pos ?? '-' }}
                            </p>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                                Lengkap</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatDomisili->alamat_lengkap ?? '-' }}</p>
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400">Belum ada data</p>
                    @endif
                </div>
            </x-ui.card>
        </div>

        <!-- Alamat Ayah dan Ibu -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Alamat Ayah -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Ayah</h2>
                </x-slot>

                <div class="space-y-6">
                    @if ($alamatAyah)
                        <!-- Provinsi -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAyah->provinsi ?? '-' }}</p>
                        </div>

                        <!-- Kabupaten -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAyah->kabupaten ?? '-' }}
                            </p>
                        </div>

                        <!-- Kecamatan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAyah->kecamatan ?? '-' }}
                            </p>
                        </div>

                        <!-- Kelurahan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAyah->kelurahan ?? '-' }}
                            </p>
                        </div>

                        <!-- RT/RW -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">RT / RW</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($alamatAyah->rt || $alamatAyah->rw)
                                    {{ $alamatAyah->rt ?? '-' }} / {{ $alamatAyah->rw ?? '-' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Kode Pos -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Pos</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatAyah->kode_pos ?? '-' }}</p>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                                Lengkap</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatAyah->alamat_lengkap ?? '-' }}</p>
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400">Belum ada data</p>
                    @endif
                </div>
            </x-ui.card>

            <!-- Alamat Ibu -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Ibu</h2>
                </x-slot>

                <div class="space-y-6">
                    @if ($alamatIbu)
                        <!-- Provinsi -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatIbu->provinsi ?? '-' }}</p>
                        </div>

                        <!-- Kabupaten -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatIbu->kabupaten ?? '-' }}</p>
                        </div>

                        <!-- Kecamatan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatIbu->kecamatan ?? '-' }}</p>
                        </div>

                        <!-- Kelurahan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatIbu->kelurahan ?? '-' }}</p>
                        </div>

                        <!-- RT/RW -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">RT / RW</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($alamatIbu->rt || $alamatIbu->rw)
                                    {{ $alamatIbu->rt ?? '-' }} / {{ $alamatIbu->rw ?? '-' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Kode Pos -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Pos</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">{{ $alamatIbu->kode_pos ?? '-' }}</p>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                                Lengkap</label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $alamatIbu->alamat_lengkap ?? '-' }}</p>
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400">Belum ada data</p>
                    @endif
                </div>
            </x-ui.card>
        </div>

        <!-- Data Sekolah & Pendidikan -->
        <x-ui.card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Sekolah & Pendidikan</h2>
            </x-slot>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Sekolah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Sekolah
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolah->nama }}
                    </p>
                </div>

                <!-- Tahun Masuk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tahun Masuk
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->tahun_masuk ?? '-' }}
                    </p>
                </div>

                <!-- Tahun Keluar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tahun Keluar
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->tahun_keluar ?? '-' }}
                    </p>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kelas
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->kelas ?? '-' }}
                    </p>
                </div>

                <!-- Status Kelulusan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status Kelulusan
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        @if ($sekolahMurid->status_kelulusan !== null)
                            <span
                                class="inline-flex rounded-full {{ $sekolahMurid->isLulus() ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }} px-3 py-1 text-sm font-semibold">
                                {{ $sekolahMurid->status_kelulusan_label }}
                            </span>
                        @else
                            <span
                                class="inline-flex rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 px-3 py-1 text-sm font-semibold">
                                Belum lulus
                            </span>
                        @endif
                    </p>
                </div>

                <!-- Tahun Mutasi Masuk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tahun Mutasi Masuk
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->tahun_mutasi_masuk ?? '-' }}
                    </p>
                </div>

                <!-- Alasan Mutasi Masuk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alasan Mutasi Masuk
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->alasan_mutasi_masuk ?? '-' }}
                    </p>
                </div>

                <!-- Tahun Mutasi Keluar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tahun Mutasi Keluar
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->tahun_mutasi_keluar ?? '-' }}
                    </p>
                </div>

                <!-- Alasan Mutasi Keluar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alasan Mutasi Keluar
                    </label>
                    <p class="mt-2 text-base text-gray-900 dark:text-white">
                        {{ $sekolahMurid->alasan_mutasi_keluar ?? '-' }}
                    </p>
                </div>
            </div>
        </x-ui.card>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="{{ route('sekolah.show-murid', $sekolah) }}"
                class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <a href="{{ route('sekolah.edit-murid', ['sekolah' => $sekolah->id, 'murid' => $murid->id]) }}"
                class="flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Data
            </a>
            {{-- <button type="button" @click="showDeleteModal = true"
                class="flex items-center justify-center rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus dari Sekolah
            </button> --}}
        </div>
    </div>
@endsection
