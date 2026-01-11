@extends('layouts.app')

@section('content')
    <div x-data="{
        showDetailModal: false,
        selectedValidasi: null,
        showApproveConfirm: false,
        approveValidasiId: null,
        approveLoading: false,
    
        openDetail(data) {
            this.selectedValidasi = data;
            this.showDetailModal = true;
        },
        closeDetail() {
            this.showDetailModal = false;
            this.selectedValidasi = null;
        },
        confirmApprove(id) {
            this.approveValidasiId = id;
            this.showApproveConfirm = true;
        },
        submitApprove() {
            if (!this.approveValidasiId) return;
            this.approveLoading = true;
            document.getElementById(`approve-form-${this.approveValidasiId}`).submit();
        }
    }" @keydown.escape.window="showDetailModal = false; showApproveConfirm = false;" class="pb-50">

        <!-- Page Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                    {{ $title }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola validasi data alumni dari formulir online
                </p>
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
                            Cari Nama atau NIK
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama atau NIK alumni"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status
                        </label>
                        <select name="status"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Sudah Disetujui</option>
                        </select>
                    </div>

                    <!-- Filter & Clear Button -->
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="bg-brand-500 hover:bg-brand-600 h-11 flex-1 rounded-lg px-4 text-sm font-medium text-white transition flex items-center justify-center"
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
                            Filter
                        </button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('validasi-alumni.index') }}" x-on:click="isClearing = true"
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
            <div class="hidden md:block">
                <x-ui.table>
                    <x-slot:thead>
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Nama / NIK
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Profesi / Tempat Kerja
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Tanggal Mengisi
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            </th>
                        </tr>
                    </x-slot:thead>
                    <x-slot:tbody>
                        @forelse($validasi as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->murid?->nama ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->murid?->nik ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $item->profesi_sekarang ?? '-' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->nama_tempat_kerja ? (strlen($item->nama_tempat_kerja) > 30 ? substr($item->nama_tempat_kerja, 0, 30) . '...' : $item->nama_tempat_kerja) : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->is_accepted)
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Disetujui
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-start space-x-2">
                                        <button type="button"
                                            @click="openDetail({
                                                id: {{ $item->id }},
                                                nama: '{{ addslashes($item->murid?->nama ?? '') }}',
                                                nik: '{{ $item->murid?->nik ?? '' }}',
                                                nisn: '{{ $item->murid?->nisn ?? '' }}',
                                                profesi_sekarang: '{{ addslashes($item->profesi_sekarang ?? '') }}',
                                                nama_tempat_kerja: '{{ addslashes($item->nama_tempat_kerja ?? '') }}',
                                                kota_tempat_kerja: '{{ addslashes($item->kota_tempat_kerja ?? '') }}',
                                                riwayat_pekerjaan: '{{ addslashes($item->riwayat_pekerjaan ?? '') }}',
                                                kontak_wa: '{{ $item->kontak_wa ?? '' }}',
                                                kontak_email: '{{ $item->kontak_email ?? '' }}',
                                                alamat_sekarang: '{{ addslashes($item->update_alamat_sekarang ?? '') }}',
                                                provinsi: {{ $item->provinsi ? "{ id: {$item->provinsi->id}, nama_provinsi: '{$item->provinsi->nama_provinsi}' }" : 'null' }},
                                                kabupaten: {{ $item->kabupaten ? "{ id: {$item->kabupaten->id}, nama_kabupaten: '{$item->kabupaten->nama_kabupaten}' }" : 'null' }},
                                                is_accepted: {{ $item->is_accepted ? 'true' : 'false' }}
                                            })"
                                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20">
                                            Lihat
                                        </button>

                                        @if (!$item->is_accepted)
                                            <form id="approve-form-{{ $item->id }}"
                                                action="{{ route('validasi-alumni.approve', $item) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                            </form>
                                            <button type="button" @click="confirmApprove({{ $item->id }})"
                                                class="hover:text-green-700 dark:hover:text-green-300 flex-1 rounded-md bg-green-50 px-3 py-2 text-center text-sm font-medium text-green-600 dark:bg-green-900/20 dark:text-green-400">
                                                Setujui
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada data validasi alumni yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-slot:tbody>
                </x-ui.table>
            </div>
        </x-ui.card>

        <!-- Mobile Card List -->
        <div class="block md:hidden mt-4">
            <div class="space-y-4">
                @forelse($validasi as $item)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-3 flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->murid?->nama ?? 'N/A' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    NIK: {{ $item->murid?->nik ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="ml-2 flex items-center space-x-2">
                                @if ($item->is_accepted)
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Disetujui
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        Menunggu
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 space-y-2">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                <div class="font-medium">{{ $item->profesi_sekarang ?? '-' }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $item->nama_tempat_kerja ?? '-' }}</div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Daftar: {{ $item->created_at->format('d M Y') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button"
                                @click="openDetail({
                                    id: {{ $item->id }},
                                    nama: '{{ addslashes($item->murid?->nama ?? '') }}',
                                    nik: '{{ $item->murid?->nik ?? '' }}',
                                    nisn: '{{ $item->murid?->nisn ?? '' }}',
                                    profesi_sekarang: '{{ addslashes($item->profesi_sekarang ?? '') }}',
                                    nama_tempat_kerja: '{{ addslashes($item->nama_tempat_kerja ?? '') }}',
                                    kota_tempat_kerja: '{{ addslashes($item->kota_tempat_kerja ?? '') }}',
                                    riwayat_pekerjaan: '{{ addslashes($item->riwayat_pekerjaan ?? '') }}',
                                    kontak_wa: '{{ $item->kontak_wa ?? '' }}',
                                    kontak_email: '{{ $item->kontak_email ?? '' }}',
                                    alamat_sekarang: '{{ addslashes($item->update_alamat_sekarang ?? '') }}',
                                    provinsi: {{ $item->provinsi ? "{ id: {$item->provinsi->id}, nama_provinsi: '{$item->provinsi->nama_provinsi}' }" : 'null' }},
                                    kabupaten: {{ $item->kabupaten ? "{ id: {$item->kabupaten->id}, nama_kabupaten: '{$item->kabupaten->nama_kabupaten}' }" : 'null' }},
                                    is_accepted: {{ $item->is_accepted ? 'true' : 'false' }}
                                })"
                                class="flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="text-sm">Lihat</span>
                            </button>

                            @if (!$item->is_accepted)
                                <form id="approve-form-{{ $item->id }}"
                                    action="{{ route('validasi-alumni.approve', $item) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                </form>
                                <button type="button" @click="confirmApprove({{ $item->id }})"
                                    class="flex items-center text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm">Setujui</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data validasi alumni yang ditemukan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <x-pagination :paginator="$validasi" />

        <!-- Detail Modal -->
        <div x-show="showDetailModal" x-cloak @keydown.escape.window="closeDetail()"
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">
            <!-- Backdrop -->
            <div @click="closeDetail()" class="absolute inset-0 bg-gray-900/50 transition-opacity" x-transition.opacity>
            </div>

            <!-- Modal Content -->
            <div @click.stop class="relative w-full max-w-2xl rounded-3xl bg-white dark:bg-gray-900" x-transition.scale>
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Detail Validasi Alumni
                    </h3>
                    <button @click="closeDetail()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6">
                    <div class="space-y-6" x-show="selectedValidasi">
                        <!-- Data Pribadi -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">Data Pribadi</h4>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="selectedValidasi?.nama">
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">NIK</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="selectedValidasi?.nik">
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">NISN</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white" x-text="selectedValidasi?.nisn">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pekerjaan -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">Data Pekerjaan</h4>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Profesi
                                        Sekarang</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.profesi_sekarang || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama Tempat
                                        Kerja</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.nama_tempat_kerja || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Kota Tempat
                                        Kerja</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.kota_tempat_kerja || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Riwayat
                                        Pekerjaan</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.riwayat_pekerjaan || '-'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Data Kontak -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">Data Kontak</h4>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">WhatsApp</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.kontak_wa || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.kontak_email || '-'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Data Alamat -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">Alamat Sekarang</h4>
                            <p class="text-sm text-gray-900 dark:text-white"
                                x-text="selectedValidasi?.alamat_sekarang || '-'"></p>
                        </div>

                        <!-- Data Domisili -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">Informasi Domisili</h4>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Provinsi</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.provinsi?.nama_provinsi || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400">Kabupaten/Kota</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white"
                                        x-text="selectedValidasi?.kabupaten?.nama_kabupaten || '-'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                    <button @click="closeDetail()"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                        Tutup
                    </button>
                    <button @click="confirmApprove(selectedValidasi?.id); closeDetail()"
                        x-show="selectedValidasi && !selectedValidasi.is_accepted"
                        class="bg-green-500 hover:bg-green-600 flex-1 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        Setujui
                    </button>
                </div>
            </div>
        </div>

        <!-- Approve Confirmation Modal -->
        <div x-show="showApproveConfirm" x-cloak @keydown.escape.window="showApproveConfirm = false"
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">
            <!-- Backdrop -->
            <div @click="showApproveConfirm = false" class="absolute inset-0 bg-gray-900/50 transition-opacity"
                x-transition.opacity>
            </div>

            <!-- Modal Content -->
            <div @click.stop class="relative w-full max-w-sm rounded-3xl bg-white dark:bg-gray-900" x-transition.scale>
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Konfirmasi Persetujuan
                    </h3>
                    <button @click="showApproveConfirm = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Apakah Anda yakin ingin menyetujui validasi alumni ini? Data akan diperbarui ke sistem dan tidak
                        dapat dibatalkan.
                    </p>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                    <button @click="showApproveConfirm = false"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </button>
                    <button @click="submitApprove()" :disabled="approveLoading"
                        x-bind:class="approveLoading ? 'opacity-70 cursor-not-allowed' : 'hover:bg-green-600'"
                        class="bg-green-500 flex-1 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        <template x-if="approveLoading">
                            <span>Memproses...</span>
                        </template>
                        <template x-if="!approveLoading">
                            <span>Ya, Setujui</span>
                        </template>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection