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
                        Perbarui informasi sekolah {{ $sekolah->nama }}
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
        <form action="{{ route('sekolah.update', $sekolah) }}" method="POST" id="sekolahForm" enctype="multipart/form-data"
            x-data="{ isSubmitting: false }" x-on:submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Kode Sekolah -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kode Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_sekolah" value="{{ old('kode_sekolah', $sekolah->kode_sekolah) }}"
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
                        <input type="text" name="nama" value="{{ old('nama', $sekolah->nama) }}"
                            placeholder="Nama lengkap sekolah"
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
                        <select name="jenis_sekolah"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('jenis_sekolah') border-red-500 @enderror">
                            <option value="">Pilih Jenis Sekolah</option>
                            @foreach ($jenisSekolahOptions as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('jenis_sekolah', $sekolah->jenis_sekolah) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_sekolah')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bentuk Pendidikan -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Bentuk Pendidikan <span class="text-red-500">*</span>
                        </label>
                        <select name="bentuk_pendidikan"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('bentuk_pendidikan') border-red-500 @enderror">
                            <option value="">Pilih Bentuk Pendidikan</option>
                            @foreach ($bentukPendidikanOptions as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('bentuk_pendidikan', $sekolah->bentuk_pendidikan) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('bentuk_pendidikan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div x-data="{ radioValue: '{{ old('status') ?? $sekolah->status }}' }">
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
                                            {{ old('status') == $status || $sekolah->status == $status ? 'checked' : '' }} />
                                        <div :class="radioValue === '{{ $status }}' ? 'border-brand-500 bg-brand-500' :
                                            'bg-transparent border-gray-300 dark:border-gray-700'"
                                            class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                                            <span class="h-2 w-2 rounded-full"
                                                :class="radioValue === '{{ $status }}' ? 'bg-white' :
                                                    'bg-white dark:bg-[#171f2e]'"></span>
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
                            <input type="text" name="telepon" value="{{ old('telepon', $sekolah->telepon) }}"
                                placeholder="Nomor telepon"
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
                            <input type="email" name="email" value="{{ old('email', $sekolah->email) }}"
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
                        <input type="url" name="website" value="{{ old('website', $sekolah->website) }}"
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
                            <input type="text" name="bank_rekening"
                                value="{{ old('bank_rekening', $sekolah->bank_rekening) }}" placeholder="Nama bank"
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
                            <input type="text" name="nomor_rekening"
                                value="{{ old('nomor_rekening', $sekolah->nomor_rekening) }}" placeholder="No. rekening"
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
                            <input type="text" name="rekening_atas_nama"
                                value="{{ old('rekening_atas_nama', $sekolah->rekening_atas_nama) }}"
                                placeholder="Nama pemilik rekening"
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
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $sekolah->keterangan) }}</textarea>
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
                                        {{ old('id_provinsi', $sekolah->kabupaten?->id_provinsi) == $prov->id ? 'selected' : '' }}>
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
                            <select name="id_kabupaten" id="kabupaten"
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('id_kabupaten') border-red-500 @enderror">
                                <option value="">Pilih Kabupaten/Kota</option>
                                @foreach ($kabupaten as $kab)
                                    <option value="{{ $kab->id }}"
                                        {{ old('id_kabupaten', $sekolah->id_kabupaten) == $kab->id ? 'selected' : '' }}>
                                        {{ $kab->nama_kabupaten }}
                                    </option>
                                @endforeach
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
                            <input type="text" name="alamat_kecamatan"
                                value="{{ old('alamat_kecamatan', $sekolah->alamatList()->first()?->kecamatan ?? '') }}"
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
                            <input type="text" name="alamat_kelurahan"
                                value="{{ old('alamat_kelurahan', $sekolah->alamatList()->first()?->kelurahan ?? '') }}"
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
                                <input type="text" name="alamat_rt"
                                    value="{{ old('alamat_rt', $sekolah->alamatList()->first()?->rt ?? '') }}"
                                    placeholder="RT"
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
                                <input type="text" name="alamat_rw"
                                    value="{{ old('alamat_rw', $sekolah->alamatList()->first()?->rw ?? '') }}"
                                    placeholder="RW"
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
                                <input type="text" name="alamat_kode_pos"
                                    value="{{ old('alamat_kode_pos', $sekolah->alamatList()->first()?->kode_pos ?? '') }}"
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
                            <input type="text" name="alamat_koordinat_x"
                                value="{{ old('alamat_koordinat_x', $sekolah->alamatList()->first()?->koordinat_x ?? '') }}"
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
                            <input type="text" name="alamat_koordinat_y"
                                value="{{ old('alamat_koordinat_y', $sekolah->alamatList()->first()?->koordinat_y ?? '') }}"
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
                                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('alamat') border-red-500 @enderror">{{ old('alamat', $sekolah->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Galeri Sekolah Section -->
            <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
                <h2 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Galeri Sekolah
                </h2>

                <!-- Existing Gallery -->
                @if ($sekolah->galeri->count() > 0)
                    <div class="mb-8">
                        <h3 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Foto Sekolah Saat Ini
                        </h3>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                            @foreach ($sekolah->galeri as $galeri)
                                <div class="relative group rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800"
                                    x-data="{ isMarkedForDelete: false }">
                                    <img src="{{ asset('storage/' . $galeri->image_path) }}" alt="Galeri"
                                        class="w-full h-40 object-cover">
                                    <input type="hidden" name="existing_galeri_ids[]" value="{{ $galeri->id }}">

                                    <div
                                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button"
                                            @click="isMarkedForDelete = !isMarkedForDelete; $el.closest('.bg-black\\/50').querySelector('input[name*=deleted_galeri]').value = isMarkedForDelete ? '{{ $galeri->id }}' : ''"
                                            class="bg-red-500 hover:bg-red-600 text-white rounded-lg px-4 py-2 text-sm font-medium transition">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                        <input type="hidden" name="deleted_galeri_ids[]" value="">
                                    </div>

                                    <div x-show="isMarkedForDelete" x-cloak
                                        x-on:click="isMarkedForDelete = false; $el.closest('.bg-red-500\\/80').previousElementSibling.querySelector('input[name*=deleted_galeri]').value = ''"
                                        class="absolute inset-0 bg-red-500/80 flex items-center justify-center cursor-pointer">
                                        <span class="text-white text-sm font-semibold">Batalkan</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload New Gallery -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tambah Foto Baru
                    </label>
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Tambahkan foto sekolah tambahan (halaman depan, gapura/gerbang, halaman belakang, dll)
                    </p>

                    <!-- Dropzone -->
                    <div x-data="{
                        isDragging: false,
                        files: [],
                        fileInput: null,
                        handleDrop(e) {
                            this.isDragging = false;
                            const droppedFiles = Array.from(e.dataTransfer.files);
                            this.handleFiles(droppedFiles);
                        },
                        handleFiles(selectedFiles) {
                            const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];
                            const validFiles = selectedFiles.filter(file => validTypes.includes(file.type) && file.size <= 5120000);
                    
                            if (validFiles.length > 0) {
                                this.files = [...this.files, ...validFiles];
                                this.updateFileInput();
                            }
                        },
                        updateFileInput() {
                            const dataTransfer = new DataTransfer();
                            this.files.forEach(file => dataTransfer.items.add(file));
                            this.fileInput.files = dataTransfer.files;
                        },
                        removeFile(index) {
                            this.files.splice(index, 1);
                            this.updateFileInput();
                        }
                    }"
                        class="transition border border-gray-300 border-dashed cursor-pointer dark:hover:border-brand-500 dark:border-gray-700 rounded-xl hover:border-brand-500">
                        <div @drop.prevent="handleDrop($event)" @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false" @click="$refs.galeriFileInput.click()"
                            :class="isDragging
                                ?
                                'border-brand-500 bg-gray-100 dark:bg-gray-800' :
                                'border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'"
                            class="dropzone rounded-xl border-dashed border-gray-300 p-7 lg:p-10 transition-colors cursor-pointer">
                            <!-- Hidden File Input -->
                            <input x-ref="galeriFileInput" type="file" name="galeri_files[]"
                                @change="handleFiles(Array.from($event.target.files)); $event.target.value = ''"
                                accept="image/png,image/jpeg,image/webp,image/gif" multiple class="hidden" @click.stop />

                            <div class="flex flex-col items-center m-0">
                                <!-- Icon Container -->
                                <div class="mb-[22px] flex justify-center">
                                    <div
                                        class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                        <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Text Content -->
                                <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                                    <span x-show="!isDragging">Drag & Drop Foto Sekolah</span>
                                    <span x-show="isDragging" x-cloak>Drop Foto di Sini</span>
                                </h4>

                                <span
                                    class="text-center mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                                    Drag and drop foto PNG, JPG, GIF, atau WebP di sini atau klik untuk browse
                                </span>

                                <span class="font-medium underline text-theme-sm text-brand-500">
                                    Browse File
                                </span>
                            </div>
                        </div>

                        <!-- File Preview List -->
                        <div x-show="files.length > 0" class="mt-4 p-4 border-t border-gray-200 dark:border-gray-700"
                            x-cloak>
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Foto baru yang dipilih:
                            </h5>
                            <ul class="space-y-2">
                                <template x-for="(file, index) in files" :key="index">
                                    <li
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center gap-3 flex-1">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <div class="flex-1">
                                                <span class="text-sm text-gray-700 dark:text-gray-300"
                                                    x-text="file.name"></span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2"
                                                    x-text="'(' + (file.size / 1024).toFixed(2) + ' KB)'"></span>
                                            </div>
                                        </div>
                                        <button @click.stop="removeFile(index)" type="button"
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 ml-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    @error('galeri_files')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
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
                    Perbarui Sekolah
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

                // Get current kabupaten value to preserve selection if possible
                const currentKabupatenId = kabupatenSelect.value;

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
                                if (kabupaten.id == currentKabupatenId) {
                                    option.selected = true;
                                }
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

            // Trigger change event on page load if provinsi is selected
            if (provinsiSelect.value) {
                provinsiSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
