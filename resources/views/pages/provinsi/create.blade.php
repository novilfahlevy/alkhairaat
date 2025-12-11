@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tambahkan provinsi baru ke dalam sistem
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 max-w-2xl">
        <form action="{{ route('provinsi.store') }}" method="POST">
            @csrf

            <!-- Kode Provinsi -->
            <div class="mb-6">
                <label for="kode_provinsi" class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Kode Provinsi
                </label>
                <input type="text" id="kode_provinsi" name="kode_provinsi" value="{{ old('kode_provinsi') }}"
                    placeholder="Contoh: 11"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 block w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                @error('kode_provinsi')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Provinsi -->
            <div class="mb-6">
                <label for="nama_provinsi" class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama Provinsi
                </label>
                <input type="text" id="nama_provinsi" name="nama_provinsi" value="{{ old('nama_provinsi') }}"
                    placeholder="Contoh: Jawa Barat"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 block w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                @error('nama_provinsi')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 rounded-lg px-6 py-2.5 text-sm font-medium text-white transition">
                    Simpan
                </button>
                <a href="{{ route('provinsi.index') }}"
                    class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
