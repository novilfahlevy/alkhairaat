@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola data alumni Perguruan Islam Alkhairaat
            </p>
        </div>
        <div>
            <a href="{{ route('alumni.create') }}"
                class="bg-brand-500 hover:bg-brand-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Alumni
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <x-ui.card>
        <x-slot:header>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Data</h2>
        </x-slot:header>

        <form method="GET" class="mb-6" x-data="{ isSubmitting: false, isClearing: false }" x-on:submit="isSubmitting = true">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Search -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Cari
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama atau NISN alumni"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </div>

                <!-- Status -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status Pekerjaan
                    </label>
                    <select name="status"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Status</option>
                        @foreach ($statusOptions as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') == $key)>
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
                            <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        Filter
                    </button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('alumni.index') }}" x-on:click="isClearing = true"
                            class="border-brand-500 hover:bg-brand-50 dark:hover:bg-brand-900/20 h-11 flex-1 rounded-lg border px-4 text-sm font-medium text-brand-600 transition flex items-center justify-center dark:border-brand-700 dark:text-brand-400"
                            :disabled="isClearing" x-bind:class="{ 'opacity-70 cursor-not-allowed': isClearing }">
                            <template x-if="isClearing">
                                <svg class="mr-2 h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            Hapus Filter
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
        <div class="hidden md:block">
            <x-ui.table>
                <x-slot:thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Nama Alumni
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            NISN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Profesi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Tempat Kerja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Kota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Aksi
                        </th>
                    </tr>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($alumni as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="whitespace-nowrap px-6 py-3 text-sm font-medium text-gray-900 dark:text-white/90">
                                {{ $item->murid?->nama ?? 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->murid?->nisn ?? 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->profesi_sekarang ?? '-' }}
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->nama_tempat_kerja ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->kota_tempat_kerja ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('alumni.show', $item) }}"
                                        class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300 inline-flex items-center rounded p-1 transition"
                                        title="Lihat Detail">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('alumni.edit', $item) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center rounded p-1 transition"
                                        title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button onclick="deleteItem('{{ route('alumni.destroy', $item) }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-flex items-center rounded p-1 transition"
                                        title="Hapus">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data alumni
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>
    </x-ui.card>

    <!-- Mobile Card List -->
    <div class="block md:hidden">
        <div class="space-y-4">
            @forelse($alumni as $item)
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-3 flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $item->murid?->nama ?? 'N/A' }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                NISN: {{ $item->murid?->nisn ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="ml-2 flex items-center space-x-2">
                            <a href="{{ route('alumni.show', $item) }}"
                                class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="mb-3 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Profesi:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->profesi_sekarang ?? '-' }}</span>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Tempat Kerja:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->nama_tempat_kerja ?? '-' }}</p>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Kota:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->kota_tempat_kerja ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($item->kontak || $item->email)
                        <div class="mb-3 border-t border-gray-200 pt-2 dark:border-gray-700">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Kontak:</p>
                            @if ($item->kontak)
                                <p class="text-xs font-medium text-gray-900 dark:text-white">{{ $item->kontak }}</p>
                            @endif
                            @if ($item->email)
                                <p class="truncate text-xs text-gray-600 dark:text-gray-400">{{ $item->email }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="flex gap-2 border-t border-gray-200 pt-3 dark:border-gray-700">
                        <a href="{{ route('alumni.edit', $item) }}"
                            class="flex-1 rounded bg-blue-50 px-2 py-2 text-center text-xs font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30">
                            Edit
                        </a>
                        <button onclick="deleteItem('{{ route('alumni.destroy', $item) }}')"
                            class="flex-1 rounded bg-red-50 px-2 py-2 text-center text-xs font-medium text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                            Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data alumni
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <x-pagination :paginator="$alumni" />

    @push('scripts')
        <script>
            function deleteItem(url) {
                if (confirm('Apakah Anda yakin ingin menghapus data alumni ini?')) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        </script>
    @endpush
@endsection
