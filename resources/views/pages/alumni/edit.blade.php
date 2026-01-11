@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Perbarui informasi pekerjaan alumni
            </p>
        </div>
        <a href="{{ route('alumni.show', $alumni) }}"
            class="bg-gray-500 hover:bg-gray-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <x-ui.card>
        <x-slot:header>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Form Edit Alumni</h2>
        </x-slot:header>

        <form action="{{ route('alumni.update', $alumni) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Identitas Murid (Read-only) -->
            <div class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                <h3 class="mb-6 text-base font-semibold text-gray-900 dark:text-white">
                    Identitas Murid
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Lengkap
                        </label>
                        <input type="text" value="{{ $alumni->murid?->nama ?? '-' }}" disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            NISN
                        </label>
                        <input type="text" value="{{ $alumni->murid?->nisn ?? '-' }}" disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            NIK
                        </label>
                        <input type="text" value="{{ $alumni->murid?->nik ?? '-' }}" disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Jenis Kelamin
                        </label>
                        <input type="text" value="{{ $alumni->murid?->jenis_kelamin_label ?? '-' }}" disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>
                </div>
            </div>

            <!-- Informasi Pekerjaan (Editable) -->
            <div>
                <h3 class="mb-6 text-base font-semibold text-gray-900 dark:text-white">
                    Informasi Pekerjaan
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Profesi Sekarang
                        </label>
                        <input type="text" name="profesi_sekarang" value="{{ old('profesi_sekarang', $alumni->profesi_sekarang) }}"
                            placeholder="Contoh: Software Engineer, Guru, dll"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('profesi_sekarang') border-red-500 dark:border-red-500 @enderror">
                        @error('profesi_sekarang')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Tempat Kerja
                        </label>
                        <input type="text" name="nama_tempat_kerja" value="{{ old('nama_tempat_kerja', $alumni->nama_tempat_kerja) }}"
                            placeholder="Contoh: PT Teknologi Maju, SMA Alkhairaat, dll"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_tempat_kerja') border-red-500 dark:border-red-500 @enderror">
                        @error('nama_tempat_kerja')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kota Tempat Kerja
                        </label>
                        <input type="text" name="kota_tempat_kerja" value="{{ old('kota_tempat_kerja', $alumni->kota_tempat_kerja) }}"
                            placeholder="Contoh: Jakarta, Bandung, Surabaya, dll"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kota_tempat_kerja') border-red-500 dark:border-red-500 @enderror">
                        @error('kota_tempat_kerja')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Riwayat Pekerjaan
                    </label>
                    <textarea name="riwayat_pekerjaan" rows="4"
                        placeholder="Contoh: 2023-sekarang: Software Engineer di PT Teknologi Maju, 2022-2023: Programmer di Startup ABC"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('riwayat_pekerjaan') border-red-500 dark:border-red-500 @enderror">{{ old('riwayat_pekerjaan', $alumni->riwayat_pekerjaan) }}</textarea>
                    @error('riwayat_pekerjaan')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informasi Domisili (Read-only) -->
            <div class="mb-8 border-t border-gray-200 py-8 dark:border-gray-700">
                <h3 class="mb-6 text-base font-semibold text-gray-900 dark:text-white">
                    Informasi Domisili
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Provinsi
                        </label>
                        <input type="text" value="{{ $alumni->provinsi?->nama_provinsi ?? '-' }}" disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kabupaten/Kota
                        </label>
                        <input type="text" value="{{ $alumni->kabupaten?->nama_kabupaten ?? '-' }}" disabled
                            class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex gap-3 border-t border-gray-200 pt-8 dark:border-gray-700">
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-6 py-2.5 text-sm font-medium text-white transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('alumni.show', $alumni) }}"
                    class="border-brand-500 hover:bg-brand-50 dark:hover:bg-brand-900/20 rounded-lg border px-6 py-2.5 text-sm font-medium text-brand-600 transition dark:border-brand-700 dark:text-brand-400">
                    Batal
                </a>
            </div>
        </form>
    </x-ui.card>
@endsection
