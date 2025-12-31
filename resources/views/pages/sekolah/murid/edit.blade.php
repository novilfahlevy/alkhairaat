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
                <a href="{{ route('sekolah.show-detail-murid', ['sekolah' => $sekolah, 'murid' => $murid]) }}"
                    class="hover:text-brand-600 dark:hover:text-brand-400">
                    {{ $murid->nama }}
                </a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">Edit Data</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Edit Data Murid
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Edit data pribadi dan alamat {{ $murid->nama }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('sekolah.show-detail-murid', ['sekolah' => $sekolah, 'murid' => $murid]) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
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

        <form action="{{ route('sekolah.update-murid', ['sekolah' => $sekolah->id, 'murid' => $murid->id]) }}"
            method="POST" class="space-y-6" x-data="formData()" x-init="init()" x-on:submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <!-- Data Pribadi -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Pribadi</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $murid->nama) }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nama lengkap murid" />
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NISN <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="nisn" name="nisn" x-model="nisnValue" @blur="checkNisn()"
                                value="{{ old('nisn', $murid->nisn) }}" required
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 {{ $errors->has('nisn') ? 'border-red-500' : '' }}"
                                :class="{ 'border-red-500 dark:border-red-500': nisnExists }"
                                :disabled="isCheckingNisn" />
                            <template x-if="isCheckingNisn">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="h-5 w-5 animate-spin text-brand-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <template x-if="nisnCheckMessage">
                            <p :class="nisnExists ? 'text-red-600 dark:text-red-400' :
                                'text-green-600 dark:text-green-400'"
                                class="mt-1 text-sm">
                                <span x-html="nisnCheckMessage"></span>
                            </p>
                        </template>
                        @error('nisn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            @foreach ($jenisKelaminOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('jenis_kelamin', $murid->jenis_kelamin) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Masuk -->
                    <div>
                        <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="tahun_masuk" name="tahun_masuk"
                            value="{{ old('tahun_masuk', $sekolahMurid->tahun_masuk) }}" required min="1900" max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Contoh: {{ date('Y') }}" />
                        @error('tahun_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tempat Lahir
                        </label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $murid->tempat_lahir) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tempat lahir" />
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NIK
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik', $murid->nik) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor Identitas Kartu" />
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <x-form.date-picker
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            label="Tanggal Lahir"
                            placeholder="Pilih tanggal lahir"
                            :defaultDate="old('tanggal_lahir', $murid->tanggal_lahir?->format('Y-m-d'))"
                        />
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp / HP -->
                    <div>
                        <label for="kontak_wa_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            WhatsApp / HP
                        </label>
                        <input type="tel" id="kontak_wa_hp" name="kontak_wa_hp"
                            value="{{ old('kontak_wa_hp', $murid->kontak_wa_hp) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor WhatsApp/HP" />
                        @error('kontak_wa_hp')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="kontak_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" id="kontak_email" name="kontak_email"
                            value="{{ old('kontak_email', $murid->kontak_email) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Email murid" />
                        @error('kontak_email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Data Sekolah & Pendidikan -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Sekolah & Pendidikan</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Kelas -->
                    <div class="lg:col-span-2">
                        <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelas
                        </label>
                        <input type="text" id="kelas" name="kelas"
                            value="{{ old('kelas', $sekolahMurid->kelas) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Contoh: X-A, 10-1" />
                        @error('kelas')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Keluar -->
                    <div class="lg:col-span-2">
                        <label for="tahun_keluar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Keluar
                        </label>
                        <input type="number" id="tahun_keluar" name="tahun_keluar"
                            value="{{ old('tahun_keluar', $sekolahMurid->tahun_keluar) }}" min="1900" max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tahun keluar" />
                        @error('tahun_keluar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Mutasi Masuk -->
                    <div class="lg:col-span-2">
                        <label for="tahun_mutasi_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Mutasi Masuk
                        </label>
                        <input type="number" id="tahun_mutasi_masuk" name="tahun_mutasi_masuk"
                            value="{{ old('tahun_mutasi_masuk', $sekolahMurid->tahun_mutasi_masuk) }}" min="1900" max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tahun mutasi masuk" />
                        @error('tahun_mutasi_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan Mutasi Masuk -->
                    <div class="lg:col-span-2">
                        <label for="alasan_mutasi_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alasan Mutasi Masuk
                        </label>
                        <input type="text" id="alasan_mutasi_masuk" name="alasan_mutasi_masuk"
                            value="{{ old('alasan_mutasi_masuk', $sekolahMurid->alasan_mutasi_masuk) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alasan mutasi masuk" />
                        @error('alasan_mutasi_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Mutasi Keluar -->
                    <div class="lg:col-span-2">
                        <label for="tahun_mutasi_keluar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Mutasi Keluar
                        </label>
                        <input type="number" id="tahun_mutasi_keluar" name="tahun_mutasi_keluar"
                            value="{{ old('tahun_mutasi_keluar', $sekolahMurid->tahun_mutasi_keluar) }}" min="1900" max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tahun mutasi keluar" />
                        @error('tahun_mutasi_keluar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan Mutasi Keluar -->
                    <div class="lg:col-span-2">
                        <label for="alasan_mutasi_keluar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alasan Mutasi Keluar
                        </label>
                        <input type="text" id="alasan_mutasi_keluar" name="alasan_mutasi_keluar"
                            value="{{ old('alasan_mutasi_keluar', $sekolahMurid->alasan_mutasi_keluar) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alasan mutasi keluar" />
                        @error('alasan_mutasi_keluar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Data Orang Tua -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Orang Tua</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Nama Ayah -->
                    <div>
                        <label for="nama_ayah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Ayah
                        </label>
                        <input type="text" id="nama_ayah" name="nama_ayah"
                            value="{{ old('nama_ayah', $murid->nama_ayah) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nama ayah" />
                        @error('nama_ayah')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP Ayah -->
                    <div>
                        <label for="nomor_hp_ayah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor HP Ayah
                        </label>
                        <input type="tel" id="nomor_hp_ayah" name="nomor_hp_ayah"
                            value="{{ old('nomor_hp_ayah', $murid->nomor_hp_ayah) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor HP ayah" />
                        @error('nomor_hp_ayah')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Ibu -->
                    <div>
                        <label for="nama_ibu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Ibu
                        </label>
                        <input type="text" id="nama_ibu" name="nama_ibu"
                            value="{{ old('nama_ibu', $murid->nama_ibu) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nama ibu" />
                        @error('nama_ibu')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP Ibu -->
                    <div>
                        <label for="nomor_hp_ibu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor HP Ibu
                        </label>
                        <input type="tel" id="nomor_hp_ibu" name="nomor_hp_ibu"
                            value="{{ old('nomor_hp_ibu', $murid->nomor_hp_ibu) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor HP ibu" />
                        @error('nomor_hp_ibu')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Alamat Asli -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Asli</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="alamat_asli_provinsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <input type="text" id="alamat_asli_provinsi" name="alamat_asli_provinsi"
                            value="{{ old('alamat_asli_provinsi', $alamatAsli?->provinsi) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_asli_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_asli_kabupaten" name="alamat_asli_kabupaten"
                            value="{{ old('alamat_asli_kabupaten', $alamatAsli?->kabupaten) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_asli_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_asli_kecamatan" name="alamat_asli_kecamatan"
                            value="{{ old('alamat_asli_kecamatan', $alamatAsli?->kecamatan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_asli_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_asli_kelurahan" name="alamat_asli_kelurahan"
                            value="{{ old('alamat_asli_kelurahan', $alamatAsli?->kelurahan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_asli_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_asli_rt" name="alamat_asli_rt"
                            value="{{ old('alamat_asli_rt', $alamatAsli?->rt) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_asli_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_asli_rw" name="alamat_asli_rw"
                            value="{{ old('alamat_asli_rw', $alamatAsli?->rw) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_asli_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_asli_kode_pos" name="alamat_asli_kode_pos"
                            value="{{ old('alamat_asli_kode_pos', $alamatAsli?->kode_pos) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_asli_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_asli_lengkap" name="alamat_asli_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_asli_lengkap', $alamatAsli?->alamat_lengkap) }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_asli_koordinat_x" name="alamat_asli_koordinat_x"
                            value="{{ old('alamat_asli_koordinat_x', $alamatAsli?->koordinat_x) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_asli_koordinat_y" name="alamat_asli_koordinat_y"
                            value="{{ old('alamat_asli_koordinat_y', $alamatAsli?->koordinat_y) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Alamat Domisili -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Domisili</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="alamat_domisili_provinsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <input type="text" id="alamat_domisili_provinsi" name="alamat_domisili_provinsi"
                            value="{{ old('alamat_domisili_provinsi', $alamatDomisili?->provinsi) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_domisili_kabupaten" name="alamat_domisili_kabupaten"
                            value="{{ old('alamat_domisili_kabupaten', $alamatDomisili?->kabupaten) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_domisili_kecamatan" name="alamat_domisili_kecamatan"
                            value="{{ old('alamat_domisili_kecamatan', $alamatDomisili?->kecamatan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_domisili_kelurahan" name="alamat_domisili_kelurahan"
                            value="{{ old('alamat_domisili_kelurahan', $alamatDomisili?->kelurahan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_domisili_rt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_domisili_rt" name="alamat_domisili_rt"
                            value="{{ old('alamat_domisili_rt', $alamatDomisili?->rt) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_domisili_rw"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_domisili_rw" name="alamat_domisili_rw"
                            value="{{ old('alamat_domisili_rw', $alamatDomisili?->rw) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_domisili_kode_pos" name="alamat_domisili_kode_pos"
                            value="{{ old('alamat_domisili_kode_pos', $alamatDomisili?->kode_pos) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_domisili_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_domisili_lengkap" name="alamat_domisili_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_domisili_lengkap', $alamatDomisili?->alamat_lengkap) }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_domisili_koordinat_x" name="alamat_domisili_koordinat_x"
                            value="{{ old('alamat_domisili_koordinat_x', $alamatDomisili?->koordinat_x) }}"
                            step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_domisili_koordinat_y" name="alamat_domisili_koordinat_y"
                            value="{{ old('alamat_domisili_koordinat_y', $alamatDomisili?->koordinat_y) }}"
                            step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Alamat Ayah -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Ayah</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="alamat_ayah_provinsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <input type="text" id="alamat_ayah_provinsi" name="alamat_ayah_provinsi"
                            value="{{ old('alamat_ayah_provinsi', $alamatAyah?->provinsi) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_ayah_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_ayah_kabupaten" name="alamat_ayah_kabupaten"
                            value="{{ old('alamat_ayah_kabupaten', $alamatAyah?->kabupaten) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_ayah_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_ayah_kecamatan" name="alamat_ayah_kecamatan"
                            value="{{ old('alamat_ayah_kecamatan', $alamatAyah?->kecamatan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_ayah_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_ayah_kelurahan" name="alamat_ayah_kelurahan"
                            value="{{ old('alamat_ayah_kelurahan', $alamatAyah?->kelurahan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_ayah_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_ayah_rt" name="alamat_ayah_rt"
                            value="{{ old('alamat_ayah_rt', $alamatAyah?->rt) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_ayah_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_ayah_rw" name="alamat_ayah_rw"
                            value="{{ old('alamat_ayah_rw', $alamatAyah?->rw) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_ayah_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_ayah_kode_pos" name="alamat_ayah_kode_pos"
                            value="{{ old('alamat_ayah_kode_pos', $alamatAyah?->kode_pos) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_ayah_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_ayah_lengkap" name="alamat_ayah_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_ayah_lengkap', $alamatAyah?->alamat_lengkap) }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_ayah_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_ayah_koordinat_x" name="alamat_ayah_koordinat_x"
                            value="{{ old('alamat_ayah_koordinat_x', $alamatAyah?->koordinat_x) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_ayah_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_ayah_koordinat_y" name="alamat_ayah_koordinat_y"
                            value="{{ old('alamat_ayah_koordinat_y', $alamatAyah?->koordinat_y) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Alamat Ibu -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Ibu</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="alamat_ibu_provinsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <input type="text" id="alamat_ibu_provinsi" name="alamat_ibu_provinsi"
                            value="{{ old('alamat_ibu_provinsi', $alamatIbu?->provinsi) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_ibu_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_ibu_kabupaten" name="alamat_ibu_kabupaten"
                            value="{{ old('alamat_ibu_kabupaten', $alamatIbu?->kabupaten) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_ibu_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_ibu_kecamatan" name="alamat_ibu_kecamatan"
                            value="{{ old('alamat_ibu_kecamatan', $alamatIbu?->kecamatan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_ibu_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_ibu_kelurahan" name="alamat_ibu_kelurahan"
                            value="{{ old('alamat_ibu_kelurahan', $alamatIbu?->kelurahan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_ibu_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_ibu_rt" name="alamat_ibu_rt"
                            value="{{ old('alamat_ibu_rt', $alamatIbu?->rt) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_ibu_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_ibu_rw" name="alamat_ibu_rw"
                            value="{{ old('alamat_ibu_rw', $alamatIbu?->rw) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_ibu_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_ibu_kode_pos" name="alamat_ibu_kode_pos"
                            value="{{ old('alamat_ibu_kode_pos', $alamatIbu?->kode_pos) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_ibu_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_ibu_lengkap" name="alamat_ibu_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_ibu_lengkap', $alamatIbu?->alamat_lengkap) }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_ibu_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_ibu_koordinat_x" name="alamat_ibu_koordinat_x"
                            value="{{ old('alamat_ibu_koordinat_x', $alamatIbu?->koordinat_x) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_ibu_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_ibu_koordinat_y" name="alamat_ibu_koordinat_y"
                            value="{{ old('alamat_ibu_koordinat_y', $alamatIbu?->koordinat_y) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Action Buttons - Sticky -->
            <div
                class="fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900 mb-0">
                <div class="w-full flex justify-end gap-3">
                    <a href="{{ route('sekolah.show-detail-murid', ['sekolah' => $sekolah->id, 'murid' => $murid->id]) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center justify-center rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white hover:bg-brand-600 transition"
                        :disabled="isSubmitting || nisnExists"
                        x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting || nisnExists }">
                        <template x-if="isSubmitting">
                            <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!isSubmitting">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

            <!-- Spacer untuk fixed buttons -->
            <div class="h-24"></div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function formData() {
            return {
                isSubmitting: false,
                isCheckingNisn: false,
                nisnExists: false,
                nisnCheckMessage: '',
                nisnValue: '{{ old('nisn', $murid->nisn) }}',
                originalNisn: '{{ $murid->nisn }}',

                async checkNisn() {
                    if (!this.nisnValue || this.nisnValue.trim().length === 0) {
                        this.nisnExists = false;
                        this.nisnCheckMessage = '';
                        return;
                    }

                    // If NISN hasn't changed, no need to check
                    if (this.nisnValue === this.originalNisn) {
                        this.nisnExists = false;
                        this.nisnCheckMessage = '';
                        return;
                    }

                    this.isCheckingNisn = true;

                    try {
                        const csrfToken = document.querySelector('input[name="_token"]').value;
                        const checkNisnUrl = '{{ route('sekolah.check-nisn', $sekolah) }}';

                        const response = await fetch(checkNisnUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                nisn: this.nisnValue
                            })
                        });

                        const data = await response.json();
                        this.nisnExists = data.exists;
                        this.nisnCheckMessage = data.message;
                    } catch (error) {
                        console.error('Error checking NISN:', error);
                    } finally {
                        this.isCheckingNisn = false;
                    }
                },

                init() {
                    this.checkNisn();
                }
            };
        }
    </script>
@endpush