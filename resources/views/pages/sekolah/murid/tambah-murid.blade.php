@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Tab Navigation -->
        @include('pages.sekolah.murid.tambah-murid-tabs', compact('sekolah'))

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

        <form action="{{ route('sekolah.store-murid', $sekolah) }}" method="POST" id="muridForm" x-data="formData()"
            x-init="init()" x-on:submit="isSubmitting = true" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Data Pribadi -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Pribadi</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NISN <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="nisn" name="nisn" x-model="nisnValue" @blur="checkNisn()"
                                value="{{ old('nisn', '') }}" placeholder="Nomor Induk Siswa Nasional" required
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 {{ $errors->has('nisn') ? 'border-red-500' : '' }}"
                                :class="{ 'border-red-500 dark:border-red-500': nisnExists }" :disabled="isCheckingNisn" />
                            <template x-if="isCheckingNisn">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="h-5 w-5 animate-spin text-brand-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
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

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', '') }}"
                            placeholder="Nama lengkap murid" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                        @error('nama')
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
                                    {{ old('jenis_kelamin', 'L') === $value ? 'selected' : '' }}>
                                    {{ $label }}</option>
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
                            value="{{ old('tahun_masuk', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                        @error('tahun_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NIK
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik', '') }}"
                            placeholder="Nomor Induk Kependudukan"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tempat Lahir
                        </label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', '') }}"
                            placeholder="Kota/Kabupaten lahir"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <x-form.date-picker id="tanggal_lahir" name="tanggal_lahir" label="Tanggal Lahir"
                            placeholder="Pilih tanggal lahir" :defaultDate="old('tanggal_lahir')" />
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
                            value="{{ old('kontak_wa_hp', '') }}" placeholder="Nomor WhatsApp/HP"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
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
                            value="{{ old('kontak_email', '') }}" placeholder="Email murid"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
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
                            value="{{ old('kelas') }}"
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
                            value="{{ old('tahun_keluar') }}" min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tahun keluar" />
                        @error('tahun_keluar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Mutasi Masuk -->
                    <div class="lg:col-span-2">
                        <label for="tahun_mutasi_masuk"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Mutasi Masuk
                        </label>
                        <input type="number" id="tahun_mutasi_masuk" name="tahun_mutasi_masuk"
                            value="{{ old('tahun_mutasi_masuk') }}" min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tahun mutasi masuk" />
                        @error('tahun_mutasi_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan Mutasi Masuk -->
                    <div class="lg:col-span-2">
                        <label for="alasan_mutasi_masuk"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alasan Mutasi Masuk
                        </label>
                        <input type="text" id="alasan_mutasi_masuk" name="alasan_mutasi_masuk"
                            value="{{ old('alasan_mutasi_masuk') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alasan mutasi masuk" />
                        @error('alasan_mutasi_masuk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Mutasi Keluar -->
                    <div class="lg:col-span-2">
                        <label for="tahun_mutasi_keluar"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tahun Mutasi Keluar
                        </label>
                        <input type="number" id="tahun_mutasi_keluar" name="tahun_mutasi_keluar"
                            value="{{ old('tahun_mutasi_keluar') }}" min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tahun mutasi keluar" />
                        @error('tahun_mutasi_keluar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan Mutasi Keluar -->
                    <div class="lg:col-span-2">
                        <label for="alasan_mutasi_keluar"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alasan Mutasi Keluar
                        </label>
                        <input type="text" id="alasan_mutasi_keluar" name="alasan_mutasi_keluar"
                            value="{{ old('alasan_mutasi_keluar') }}"
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
                        <input type="text" id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah', '') }}"
                            placeholder="Nama ayah"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
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
                            value="{{ old('nomor_hp_ayah', '') }}" placeholder="0812..."
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                        @error('nomor_hp_ayah')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Ibu -->
                    <div>
                        <label for="nama_ibu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Ibu
                        </label>
                        <input type="text" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu', '') }}"
                            placeholder="Nama ibu"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
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
                            value="{{ old('nomor_hp_ibu', '') }}" placeholder="0812..."
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
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
                            value="{{ old('alamat_asli_provinsi', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                        @error('alamat_asli_provinsi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_asli_kabupaten" name="alamat_asli_kabupaten"
                            value="{{ old('alamat_asli_kabupaten', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                        @error('alamat_asli_kabupaten')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_asli_kecamatan" name="alamat_asli_kecamatan"
                            value="{{ old('alamat_asli_kecamatan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                        @error('alamat_asli_kecamatan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_asli_kelurahan" name="alamat_asli_kelurahan"
                            value="{{ old('alamat_asli_kelurahan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                        @error('alamat_asli_kelurahan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_asli_rt" name="alamat_asli_rt"
                            value="{{ old('alamat_asli_rt', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                        @error('alamat_asli_rt')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_asli_rw" name="alamat_asli_rw"
                            value="{{ old('alamat_asli_rw', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                        @error('alamat_asli_rw')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_asli_kode_pos" name="alamat_asli_kode_pos"
                            value="{{ old('alamat_asli_kode_pos', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                        @error('alamat_asli_kode_pos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_asli_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_asli_lengkap" name="alamat_asli_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_asli_lengkap', '') }}</textarea>
                        @error('alamat_asli_lengkap')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="text" id="alamat_asli_koordinat_x" name="alamat_asli_koordinat_x"
                            value="{{ old('alamat_asli_koordinat_x', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                        @error('alamat_asli_koordinat_x')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="text" id="alamat_asli_koordinat_y" name="alamat_asli_koordinat_y"
                            value="{{ old('alamat_asli_koordinat_y', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                        @error('alamat_asli_koordinat_y')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                            value="{{ old('alamat_domisili_provinsi', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                        @error('alamat_domisili_provinsi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_domisili_kabupaten" name="alamat_domisili_kabupaten"
                            value="{{ old('alamat_domisili_kabupaten', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                        @error('alamat_domisili_kabupaten')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_domisili_kecamatan" name="alamat_domisili_kecamatan"
                            value="{{ old('alamat_domisili_kecamatan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                        @error('alamat_domisili_kecamatan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_domisili_kelurahan" name="alamat_domisili_kelurahan"
                            value="{{ old('alamat_domisili_kelurahan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                        @error('alamat_domisili_kelurahan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_rt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_domisili_rt" name="alamat_domisili_rt"
                            value="{{ old('alamat_domisili_rt', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                        @error('alamat_domisili_rt')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_rw"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_domisili_rw" name="alamat_domisili_rw"
                            value="{{ old('alamat_domisili_rw', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                        @error('alamat_domisili_rw')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_domisili_kode_pos" name="alamat_domisili_kode_pos"
                            value="{{ old('alamat_domisili_kode_pos', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                        @error('alamat_domisili_kode_pos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_domisili_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_domisili_lengkap" name="alamat_domisili_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_domisili_lengkap', '') }}</textarea>
                        @error('alamat_domisili_lengkap')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="text" id="alamat_domisili_koordinat_x" name="alamat_domisili_koordinat_x"
                            value="{{ old('alamat_domisili_koordinat_x', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                        @error('alamat_domisili_koordinat_x')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="text" id="alamat_domisili_koordinat_y" name="alamat_domisili_koordinat_y"
                            value="{{ old('alamat_domisili_koordinat_y', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                        @error('alamat_domisili_koordinat_y')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                            value="{{ old('alamat_ayah_provinsi', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                        @error('alamat_ayah_provinsi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_ayah_kabupaten" name="alamat_ayah_kabupaten"
                            value="{{ old('alamat_ayah_kabupaten', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                        @error('alamat_ayah_kabupaten')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_ayah_kecamatan" name="alamat_ayah_kecamatan"
                            value="{{ old('alamat_ayah_kecamatan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                        @error('alamat_ayah_kecamatan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_ayah_kelurahan" name="alamat_ayah_kelurahan"
                            value="{{ old('alamat_ayah_kelurahan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                        @error('alamat_ayah_kelurahan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_ayah_rt" name="alamat_ayah_rt"
                            value="{{ old('alamat_ayah_rt', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                        @error('alamat_ayah_rt')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_ayah_rw" name="alamat_ayah_rw"
                            value="{{ old('alamat_ayah_rw', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                        @error('alamat_ayah_rw')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_ayah_kode_pos" name="alamat_ayah_kode_pos"
                            value="{{ old('alamat_ayah_kode_pos', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                        @error('alamat_ayah_kode_pos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_ayah_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_ayah_lengkap" name="alamat_ayah_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_ayah_lengkap', '') }}</textarea>
                        @error('alamat_ayah_lengkap')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="text" id="alamat_ayah_koordinat_x" name="alamat_ayah_koordinat_x"
                            value="{{ old('alamat_ayah_koordinat_x', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                        @error('alamat_ayah_koordinat_x')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ayah_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="text" id="alamat_ayah_koordinat_y" name="alamat_ayah_koordinat_y"
                            value="{{ old('alamat_ayah_koordinat_y', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                        @error('alamat_ayah_koordinat_y')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                            value="{{ old('alamat_ibu_provinsi', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                        @error('alamat_ibu_provinsi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_ibu_kabupaten" name="alamat_ibu_kabupaten"
                            value="{{ old('alamat_ibu_kabupaten', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                        @error('alamat_ibu_kabupaten')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_ibu_kecamatan" name="alamat_ibu_kecamatan"
                            value="{{ old('alamat_ibu_kecamatan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                        @error('alamat_ibu_kecamatan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_ibu_kelurahan" name="alamat_ibu_kelurahan"
                            value="{{ old('alamat_ibu_kelurahan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                        @error('alamat_ibu_kelurahan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="text" id="alamat_ibu_rt" name="alamat_ibu_rt"
                            value="{{ old('alamat_ibu_rt', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                        @error('alamat_ibu_rt')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="text" id="alamat_ibu_rw" name="alamat_ibu_rw"
                            value="{{ old('alamat_ibu_rw', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                        @error('alamat_ibu_rw')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="text" id="alamat_ibu_kode_pos" name="alamat_ibu_kode_pos"
                            value="{{ old('alamat_ibu_kode_pos', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                        @error('alamat_ibu_kode_pos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_ibu_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_ibu_lengkap" name="alamat_ibu_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_ibu_lengkap', '') }}</textarea>
                        @error('alamat_ibu_lengkap')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="text" id="alamat_ibu_koordinat_x" name="alamat_ibu_koordinat_x"
                            value="{{ old('alamat_ibu_koordinat_x', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                        @error('alamat_ibu_koordinat_x')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_ibu_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="text" id="alamat_ibu_koordinat_y" name="alamat_ibu_koordinat_y"
                            value="{{ old('alamat_ibu_koordinat_y', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                        @error('alamat_ibu_koordinat_y')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Action Buttons - Sticky -->
            <div
                class="fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900 mb-0">
                <div class="w-full flex justify-end gap-3">
                    <a href="{{ route('sekolah.show', $sekolah) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
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
                        <span>Simpan Murid</span>
                    </button>
                </div>
            </div>
        </form>



        <!-- Spacer untuk fixed buttons -->
        <div class="h-24"></div>
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
                nisnValue: '{{ old('nisn', '') }}',

                async checkNisn() {
                    if (!this.nisnValue || this.nisnValue.trim().length === 0) {
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
