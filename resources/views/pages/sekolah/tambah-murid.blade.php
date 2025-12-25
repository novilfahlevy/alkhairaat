@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Tab Navigation -->
        @include('pages.sekolah.tambah-murid-tabs', compact('sekolah'))

        <!-- Form Card -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    <p class="font-medium mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('sekolah.store-murid', $sekolah) }}" method="POST" id="muridForm"
                x-data="formData()" x-init="init()" x-on:submit="isSubmitting = true"
                enctype="multipart/form-data">
                @csrf

                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                    <!-- Form Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Data Murid Baru
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- NISN -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    NISN <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nisn" x-model="nisnValue" @blur="checkNisn()"
                                        value="{{ old('nisn', '') }}" placeholder="Nomor Induk Siswa Nasional"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 {{ $errors->has('nisn') ? 'border-red-500' : '' }}"
                                        :class="{ 'border-red-500 dark:border-red-500': nisnExists }"
                                        :disabled="isCheckingNisn" required />
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

                            <!-- Nama -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama', '') }}"
                                    placeholder="Nama lengkap murid"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 {{ $errors->has('nama') ? 'border-red-500' : '' }}"
                                    required />
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIK -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    NIK
                                </label>
                                <input type="text" name="nik" value="{{ old('nik', '') }}"
                                    placeholder="Nomor Induk Kependudukan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nik')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kelamin"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white {{ $errors->has('jenis_kelamin') ? 'border-red-500' : '' }}"
                                    required>
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

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Tempat Lahir
                                </label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', '') }}"
                                    placeholder="Kota/Kabupaten lahir"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('tempat_lahir')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <x-form.date-picker name="tanggal_lahir" label="Tanggal Lahir" placeholder="Pilih tanggal lahir"
                                mode="single" dateFormat="Y-m-d" :defaultDate="old('tanggal_lahir')" />
                            @error('tanggal_lahir')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Tahun Masuk -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Tahun Masuk <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}"
                                    min="1900" max="{{ date('Y') + 1 }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white {{ $errors->has('tahun_masuk') ? 'border-red-500' : '' }}"
                                    required />
                                @error('tahun_masuk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kelas
                                </label>
                                <input type="text" name="kelas" value="{{ old('kelas', '') }}"
                                    placeholder="Nama/nomor kelas"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kelas')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Ayah -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nama Ayah
                                </label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah', '') }}"
                                    placeholder="Nama ayah"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama_ayah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor HP Ayah -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nomor HP Ayah
                                </label>
                                <input type="tel" name="nomor_hp_ayah" value="{{ old('nomor_hp_ayah', '') }}"
                                    placeholder="0812..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nomor_hp_ayah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Ibu -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nama Ibu
                                </label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu', '') }}"
                                    placeholder="Nama ibu"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama_ibu')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor HP Ibu -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Nomor HP Ibu
                                </label>
                                <input type="tel" name="nomor_hp_ibu" value="{{ old('nomor_hp_ibu', '') }}"
                                    placeholder="0812..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nomor_hp_ibu')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Full Width Section -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Status Kelulusan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Status Kelulusan
                                </label>
                                <select name="status_kelulusan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Belum lulus</option>
                                    @foreach ($statusKelulusanOptions as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('status_kelulusan', 'tidak') === $value ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status_kelulusan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kontak WA/HP -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kontak WA/HP
                                </label>
                                <input type="tel" name="kontak_wa_hp" value="{{ old('kontak_wa_hp', '') }}"
                                    placeholder="0812..."
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kontak_wa_hp')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kontak Email -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kontak Email
                                </label>
                                <input type="email" name="kontak_email" value="{{ old('kontak_email', '') }}"
                                    placeholder="email@example.com"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kontak_email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Section Divider -->
                            <div class="mt-2 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-4">Data Alamat
                                    Murid</h4>
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Provinsi
                                </label>
                                <input type="text" name="provinsi" value="{{ old('provinsi', '') }}"
                                    placeholder="Nama provinsi"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('provinsi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kabupaten -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kabupaten/Kota
                                </label>
                                <input type="text" name="kabupaten" value="{{ old('kabupaten', '') }}"
                                    placeholder="Nama kabupaten/kota"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kabupaten')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kecamatan
                                </label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan', '') }}"
                                    placeholder="Nama kecamatan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kecamatan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelurahan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kelurahan/Desa
                                </label>
                                <input type="text" name="kelurahan" value="{{ old('kelurahan', '') }}"
                                    placeholder="Nama kelurahan/desa"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kelurahan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RT/RW -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        RT
                                    </label>
                                    <input type="text" name="rt" value="{{ old('rt', '') }}" placeholder="RT"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('rt')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        RW
                                    </label>
                                    <input type="text" name="rw" value="{{ old('rw', '') }}" placeholder="RW"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('rw')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kode Pos
                                </label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', '') }}"
                                    placeholder="Kode pos"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kode_pos')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Lengkap -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Alamat Lengkap
                                </label>
                                <textarea name="alamat_lengkap" placeholder="Alamat lengkap" rows="3"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">{{ old('alamat_lengkap', '') }}</textarea>
                                @error('alamat_lengkap')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Koordinat X (Latitude) -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Latitude (Koordinat X)
                                </label>
                                <input type="text" name="koordinat_x" value="{{ old('koordinat_x', '') }}"
                                    placeholder="Latitude (cth: -7.2575)"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('koordinat_x')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Koordinat Y (Longitude) -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Longitude (Koordinat Y)
                                </label>
                                <input type="text" name="koordinat_y" value="{{ old('koordinat_y', '') }}"
                                    placeholder="Longitude (cth: 110.4324)"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('koordinat_y')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div
                    class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <a href="{{ route('sekolah.show', $sekolah) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </template>
                        <span>Simpan Murid</span>
                    </button>
                </div>
            </form>
        </div>
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
