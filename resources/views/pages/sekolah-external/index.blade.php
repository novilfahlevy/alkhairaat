@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola data sekolah external yang belum terdaftar di sistem Alkhairaat
            </p>
        </div>
        <div>
            <a href="{{ route('sekolah-external.create') }}"
                class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Sekolah External
            </a>
        </div>
    </div>

    <!-- Filters & Desktop Table Card -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 mb-6">
        <!-- Filters -->
        <form method="GET" class="mb-6" x-data="{ isSubmitting: false, isClearing: false }" x-on:submit="isSubmitting = true">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- Search -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Cari
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama atau kota sekolah"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </div>

                <!-- Jenis Sekolah -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Jenis Sekolah
                    </label>
                    <select name="jenis_sekolah"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Jenis Sekolah</option>
                        @foreach ($jenisSekolahOptions as $key => $label)
                            <option value="{{ $key }}" @selected(request('jenis_sekolah') == $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bentuk Pendidikan -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Bentuk Pendidikan
                    </label>
                    <select name="bentuk_pendidikan"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Bentuk Pendidikan</option>
                        @foreach ($bentukPendidikanOptions as $key => $label)
                            <option value="{{ $key }}" @selected(request('bentuk_pendidikan') == $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter & Clear Button -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 h-11 flex-1 rounded-lg px-4 text-sm font-medium text-white transition flex items-center justify-center"
                        :disabled="isSubmitting" x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                        <template x-if="isSubmitting">
                            <svg class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        Filter
                    </button>
                    @if (request('search') || request('jenis_sekolah') || request('bentuk_pendidikan'))
                        <a href="{{ route('sekolah-external.index') }}"
                            x-on:click="isClearing = true"
                            class="border-gray-300 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 h-11 flex-1 rounded-lg border px-4 text-sm font-medium text-gray-700 transition flex items-center justify-center dark:text-gray-300"
                            :disabled="isClearing" x-bind:class="{ 'opacity-70 cursor-not-allowed': isClearing }">
                            <template x-if="isClearing">
                                <svg class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            Bersihkan Filter
                        </a>
                    @endif
                </div>
            </div>
        </form>

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

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Sekolah
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Kota
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Jenis Sekolah
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Bentuk Pendidikan
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($sekolahExternal as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->nama_sekolah }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->kota_sekolah }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $jenisSekolahOptions[$item->jenis_sekolah] ?? 'Tidak diketahui' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ $bentukPendidikanOptions[$item->bentuk_pendidikan] ?? 'Tidak diketahui' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-start space-x-2">
                                    <a href="{{ route('sekolah-external.show', $item->id) }}"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20">
                                        Lihat
                                    </a>
                                    <a href="{{ route('sekolah-external.edit', $item->id) }}"
                                        class="text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 flex-1 rounded-md bg-amber-50 px-3 py-2 text-center text-sm font-medium dark:bg-amber-900/20">
                                        Edit
                                    </a>
                                    <form action="{{ route('sekolah-external.destroy', $item->id) }}"
                                        method="POST" class="flex-1"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 w-full rounded-md bg-red-50 px-3 py-2 text-sm font-medium dark:bg-red-900/20">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data sekolah external yang ditemukan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="block md:hidden">
        <div class="space-y-4">
            @forelse($sekolahExternal as $item)
                <div
                    class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-3 flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-white">
                                {{ $item->nama_sekolah }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->kota_sekolah }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-3 space-y-2">
                        <div class="flex items-center">
                            <span
                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $jenisSekolahOptions[$item->jenis_sekolah] ?? 'Tidak diketahui' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span
                                class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                {{ $bentukPendidikanOptions[$item->bentuk_pendidikan] ?? 'Tidak diketahui' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-start space-x-2">
                        <a href="{{ route('sekolah-external.show', $item->id) }}"
                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20">
                            Lihat
                        </a>
                        <a href="{{ route('sekolah-external.edit', $item->id) }}"
                            class="text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 flex-1 rounded-md bg-amber-50 px-3 py-2 text-center text-sm font-medium dark:bg-amber-900/20">
                            Edit
                        </a>
                        <form action="{{ route('sekolah-external.destroy', $item->id) }}" method="POST"
                            class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 w-full rounded-md bg-red-50 px-3 py-2 text-sm font-medium dark:bg-red-900/20">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div
                    class="rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data sekolah external yang ditemukan.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if ($sekolahExternal->hasPages())
        <div class="mt-6">
            {{ $sekolahExternal->withQueryString()->links() }}
        </div>
    @endif
@endsection
