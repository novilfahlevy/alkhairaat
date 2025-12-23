@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Informasi detail sekolah external
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sekolah-external.index') }}"
                class="flex items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('sekolah-external.edit', $sekolahExternal->id_sekolah_external) }}"
                class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Details Card -->
        <div class="lg:col-span-2">
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <h2 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Informasi Sekolah External
                </h2>

                <div class="space-y-6">
                    <!-- Nama Sekolah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Sekolah
                        </label>
                        <div class="mt-2 rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-900 dark:bg-gray-800 dark:text-white/90">
                            {{ $sekolahExternal->nama_sekolah }}
                        </div>
                    </div>

                    <!-- Kota Sekolah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kota Sekolah
                        </label>
                        <div class="mt-2 rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-900 dark:bg-gray-800 dark:text-white/90">
                            {{ $sekolahExternal->kota_sekolah }}
                        </div>
                    </div>

                    <!-- Jenis Sekolah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Jenis Sekolah
                        </label>
                        <div class="mt-2">
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $sekolahExternal->jenisSekolah?->nama_jenis ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Bentuk Pendidikan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Bentuk Pendidikan
                        </label>
                        <div class="mt-2">
                            <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                {{ $sekolahExternal->bentukPendidikan?->nama ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <form action="{{ route('sekolah-external.destroy', $sekolahExternal->id_sekolah_external) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sekolah external ini? Data yang dihapus tidak dapat dipulihkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="rounded-lg bg-red-100 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50">
                            <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Metadata Card -->
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                <h3 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                    Informasi Sistem
                </h3>

                <div class="space-y-4">
                    <!-- ID -->
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            ID Sekolah External
                        </p>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white/90">
                            {{ $sekolahExternal->id_sekolah_external }}
                        </p>
                    </div>

                    <!-- Created At -->
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Dibuat Pada
                        </p>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white/90">
                            {{ $sekolahExternal->created_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <!-- Updated At -->
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Diperbarui Pada
                        </p>
                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white/90">
                            {{ $sekolahExternal->updated_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
