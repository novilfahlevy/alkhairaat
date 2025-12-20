@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola data kabupaten/kota di Indonesia
            </p>
        </div>
        <div>
            <a href="{{ route('kabupaten.create') }}"
                class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kabupaten
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 mb-6">
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nama Kabupaten
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Provinsi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Jumlah Sekolah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($kabupaten as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->kode_kabupaten }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->nama_kabupaten }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->provinsi->nama_provinsi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $item->sekolah->count() }} sekolah
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('kabupaten.show', $item) }}"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('kabupaten.edit', $item) }}"
                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('kabupaten.destroy', $item) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kabupaten ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data kabupaten yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="block md:hidden">
            <div class="space-y-4">
                @forelse($kabupaten as $item)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-3 flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->nama_kabupaten }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Kode: {{ $item->kode_kabupaten }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-3 space-y-1">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Provinsi: <span class="font-medium">{{ $item->provinsi->nama_provinsi ?? '-' }}</span>
                            </p>
                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $item->sekolah->count() }} sekolah
                            </span>
                        </div>

                        @canany(['manage_kabupaten', 'access_kabupaten'])
                            <div class="flex items-center gap-2">
                                <a href="{{ route('kabupaten.show', $item) }}"
                                    class="flex-1 rounded-lg border border-brand-600 py-2 text-center text-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-400 dark:text-brand-400 dark:hover:bg-brand-900/20 transition">
                                    Lihat
                                </a>
                                <a href="{{ route('kabupaten.edit', $item) }}"
                                    class="flex-1 rounded-lg border border-blue-600 py-2 text-center text-sm font-medium text-blue-600 hover:bg-blue-50 dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-900/20 transition">
                                    Edit
                                </a>
                                <form action="{{ route('kabupaten.destroy', $item) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kabupaten ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full rounded-lg border border-red-600 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-400 dark:text-red-400 dark:hover:bg-red-900/20 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endcanany
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data kabupaten yang ditemukan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($kabupaten->hasPages())
        <div class="mt-6">
            {{ $kabupaten->links() }}
        </div>
    @endif
@endsection
