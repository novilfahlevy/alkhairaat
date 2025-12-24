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
                        Perbarui informasi sekolah external
                    </p>
                </div>
                <a href="{{ route('sekolah-external.index') }}"
                    class="flex items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('sekolah-external.update', $sekolahExternal->id) }}" method="POST" x-data="{ isSubmitting: false }"
            x-on:submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Jenis Sekolah -->
                <div>
                    <label for="jenis_sekolah" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Jenis Sekolah <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_sekolah" name="jenis_sekolah"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('jenis_sekolah') border-red-500 @enderror">
                        <option value="">Pilih Jenis Sekolah</option>
                        @foreach ($jenisSekolahOptions as $key => $label)
                            <option value="{{ $key }}" @selected(old('jenis_sekolah', $sekolahExternal->jenis_sekolah) == $key)>
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
                    <label for="bentuk_pendidikan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Bentuk Pendidikan <span class="text-red-500">*</span>
                    </label>
                    <select id="bentuk_pendidikan" name="bentuk_pendidikan"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('bentuk_pendidikan') border-red-500 @enderror">
                        <option value="">Pilih Bentuk Pendidikan</option>
                        @foreach ($bentukPendidikanOptions as $key => $label)
                            <option value="{{ $key }}" @selected(old('bentuk_pendidikan', $sekolahExternal->bentuk_pendidikan) == $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('bentuk_pendidikan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Sekolah -->
                <div>
                    <label for="nama_sekolah" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolahExternal->nama_sekolah) }}"
                        placeholder="Masukkan nama sekolah"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_sekolah') border-red-500 @enderror">
                    @error('nama_sekolah')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota Sekolah -->
                <div>
                    <label for="kota_sekolah" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Kota Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="kota_sekolah" name="kota_sekolah" value="{{ old('kota_sekolah', $sekolahExternal->kota_sekolah) }}"
                        placeholder="Masukkan nama kota"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kota_sekolah') border-red-500 @enderror">
                    @error('kota_sekolah')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <a href="{{ route('sekolah-external.index') }}"
                    class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
                    :disabled="isSubmitting" x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                    <template x-if="isSubmitting">
                        <svg class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <template x-if="!isSubmitting">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </template>
                    Perbarui
                </button>
            </div>
        </form>
    </div>
@endsection
