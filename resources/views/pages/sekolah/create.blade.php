@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $title }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tambahkan sekolah pendidikan Alkhairaat baru ke dalam sistem
                    </p>
                </div>
                <a href="{{ route('sekolah.index') }}"
                    class="flex items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('sekolah.store') }}" method="POST" id="sekolahForm" x-data="{ isSubmitting: false }"
            x-on:submit="isSubmitting = true">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Kode Sekolah -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kode Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_sekolah" value="{{ old('kode_sekolah') }}"
                            placeholder="Contoh: ALK-001"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kode_sekolah') border-red-500 @enderror">
                        @error('kode_sekolah')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Sekolah -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap sekolah"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Sekolah -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Jenis Sekolah <span class="text-red-500">*</span>
                        </label>
                        <select name="id_jenis_sekolah"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('id_jenis_sekolah') border-red-500 @enderror">
                            <option value="">Pilih Jenis Sekolah</option>
                            @foreach ($jenisSekolah as $jenis)
                                <option value="{{ $jenis->id }}"
                                    {{ old('id_jenis_sekolah') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jenis_sekolah')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bentuk Pendidikan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Bentuk Pendidikan <span class="text-red-500">*</span>
                        </label>
                        <select name="id_bentuk_pendidikan"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('id_bentuk_pendidikan') border-red-500 @enderror">
                            <option value="">Pilih Bentuk Pendidikan</option>
                            @foreach ($bentukPendidikan as $bentuk)
                                <option value="{{ $bentuk->id }}"
                                    {{ old('id_bentuk_pendidikan') == $bentuk->id ? 'selected' : '' }}>
                                    {{ $bentuk->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_bentuk_pendidikan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div x-data="{ radioValue: '{{ old('status') ?? 'aktif' }}' }">
                        <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($statusOptions as $status => $label)
                                <label for="status_{{ $status }}"
                                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                    <div class="relative">
                                        <input type="radio" id="status_{{ $status }}" name="status"
                                            value="{{ $status }}" class="sr-only"
                                            @change="radioValue = '{{ $status }}'"
                                            {{ old('status', 'aktif') == $status ? 'checked' : '' }} />
                                        <div :class="radioValue === '{{ $status }}' ? 'border-brand-500 bg-brand-500' :
                                            'bg-transparent border-gray-300 dark:border-gray-700'"
                                            class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="radioValue === '{{ $status }}' ? 'bg-white' : 'bg-white dark:bg-[#171f2e]'"></span>
                                        </div>
                                    </div>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Kontak -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Telepon -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Telepon
                            </label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="Nomor telepon"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('telepon') border-red-500 @enderror">
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="email@sekolah.com"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Website -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Website
                        </label>
                        <input type="url" name="website" value="{{ old('website') }}"
                            placeholder="https://sekolah.com"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('website') border-red-500 @enderror">
                        @error('website')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Rekening -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Bank -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Bank
                            </label>
                            <input type="text" name="bank_rekening" value="{{ old('bank_rekening') }}" placeholder="Nama bank"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('bank_rekening') border-red-500 @enderror">
                            @error('bank_rekening')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Rekening -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nomor Rekening
                            </label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="No. rekening"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nomor_rekening') border-red-500 @enderror">
                            @error('nomor_rekening')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Atas Nama -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Atas Nama
                            </label>
                            <input type="text" name="rekening_atas_nama" value="{{ old('rekening_atas_nama') }}" placeholder="Nama pemilik rekening"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('rekening_atas_nama') border-red-500 @enderror">
                            @error('rekening_atas_nama')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan tentang sekolah"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Alamat Sekolah Section -->
            <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
                <h2 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Alamat Sekolah
                </h2>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Provinsi -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <select name="id_provinsi" id="provinsi"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('id_provinsi') border-red-500 @enderror">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinsi as $prov)
                                    <option value="{{ $prov->id }}"
                                        {{ old('id_provinsi') == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->nama_provinsi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_provinsi')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kabupaten -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kabupaten/Kota <span class="text-red-500">*</span>
                            </label>
                            <select name="id_kabupaten" id="kabupaten" disabled
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 disabled:opacity-50 disabled:cursor-not-allowed @error('id_kabupaten') border-red-500 @enderror">
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                            @error('id_kabupaten')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kecamatan
                            </label>
                            <input type="text" name="alamat_kecamatan" value="{{ old('alamat_kecamatan') }}"
                                placeholder="Nama kecamatan"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_kecamatan') border-red-500 @enderror">
                            @error('alamat_kecamatan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelurahan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Kelurahan
                            </label>
                            <input type="text" name="alamat_kelurahan" value="{{ old('alamat_kelurahan') }}"
                                placeholder="Nama kelurahan"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_kelurahan') border-red-500 @enderror">
                            @error('alamat_kelurahan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- RT -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    RT
                                </label>
                                <input type="text" name="alamat_rt" value="{{ old('alamat_rt') }}" placeholder="RT"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_rt') border-red-500 @enderror">
                                @error('alamat_rt')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RW -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    RW
                                </label>
                                <input type="text" name="alamat_rw" value="{{ old('alamat_rw') }}" placeholder="RW"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_rw') border-red-500 @enderror">
                                @error('alamat_rw')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Kode Pos
                                </label>
                                <input type="text" name="alamat_kode_pos" value="{{ old('alamat_kode_pos') }}"
                                    placeholder="Kode Pos"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_kode_pos') border-red-500 @enderror">
                                @error('alamat_kode_pos')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Koordinat X (Latitude) -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Koordinat X (Latitude)
                            </label>
                            <input type="text" name="alamat_koordinat_x" value="{{ old('alamat_koordinat_x') }}"
                                placeholder="-0.5417"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_koordinat_x') border-red-500 @enderror">
                            @error('alamat_koordinat_x')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Koordinat Y (Longitude) -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Koordinat Y (Longitude)
                            </label>
                            <input type="text" name="alamat_koordinat_y" value="{{ old('alamat_koordinat_y') }}"
                                placeholder="120.8243"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat_koordinat_y') border-red-500 @enderror">
                            @error('alamat_koordinat_y')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Alamat Lengkap
                            </label>
                            <textarea name="alamat" rows="3" placeholder="Alamat lengkap sekolah"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div
                class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <a href="{{ route('sekolah.index') }}"
                    class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
                    :disabled="isSubmitting" x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
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
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                    Simpan Sekolah
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsi');
            const kabupatenSelect = document.getElementById('kabupaten');

            provinsiSelect.addEventListener('change', function() {
                const provinsiId = this.value;

                // Reset kabupaten select
                kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                kabupatenSelect.disabled = true;

                if (provinsiId) {
                    // Fetch kabupaten data
                    fetch(`{{ route('sekolah.get_kabupaten') }}?id_provinsi=${provinsiId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(kabupaten => {
                                const option = new Option(kabupaten.nama_kabupaten, kabupaten
                                    .id);
                                kabupatenSelect.add(option);
                            });
                            kabupatenSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching kabupaten:', error);
                            kabupatenSelect.disabled = false;
                        });
                }
            });

            // Auto-generate kode sekolah if needed
            const namaInput = document.querySelector('input[name="nama"]');
            const kodeInput = document.querySelector('input[name="kode_sekolah"]');

            namaInput.addEventListener('input', function() {
                if (!kodeInput.value) {
                    // Simple auto-generation logic - you can customize this
                    const words = this.value.split(' ');
                    const initials = words.map(word => word.charAt(0)).join('').toUpperCase();
                    if (initials.length >= 2) {
                        kodeInput.value = 'ALK-' + initials.slice(0, 3);
                    }
                }
            });
        });
    </script>
@endpush
