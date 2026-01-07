@extends('layouts.app')

@section('content')
    <div x-data="{
        showDetailModal: false,
        selectedValidasi: null,
        showApproveConfirm: false,
        approveValidasiId: null,
        approveLoading: false,
        filterLoading: false,
        resetLoading: false,
    
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
        },
        submitFilter() {
            this.filterLoading = true;
        },
        submitReset() {
            this.resetLoading = true;
        }
    }" @keydown.escape.window="showDetailModal = false; showApproveConfirm = false;">

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

        <!-- Filter Card -->
        <div class="mb-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Filter Data</h2>

            <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3" @submit="submitFilter()">
                <!-- Search -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Cari Nama atau NIK
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIK alumni"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>

                <!-- Status -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Status
                    </label>
                    <select name="status"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan
                        </option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Sudah Disetujui
                        </option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 h-11 flex-1 rounded-lg px-4 text-sm font-medium text-white transition flex items-center justify-center"
                        :disabled="filterLoading" x-bind:class="{ 'opacity-70 cursor-not-allowed': filterLoading }">
                        <template x-if="filterLoading">
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
                        <a href="{{ route('validasi-alumni.index') }}" @click="submitReset()"
                            class="border-brand-500 text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-950/20 h-11 flex-1 rounded-lg border px-4 text-sm font-medium transition flex items-center justify-center"
                            :disabled="resetLoading" x-bind:class="{ 'opacity-70 cursor-not-allowed': resetLoading }">
                            <template x-if="resetLoading">
                                <svg class="mr-2 h-4 w-4 animate-spin text-brand-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

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
        <div class="hidden rounded-lg bg-white shadow-md dark:bg-gray-900 md:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 dark:border-gray-700">
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 dark:text-white">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 dark:text-white">NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 dark:text-white">Profesi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 dark:text-white">Tempat Kerja
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 dark:text-white">Tanggal
                                Mengisi Data</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900 dark:text-white">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-900 dark:text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($validasi as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <span class="font-medium">{{ $item->murid?->nama ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->murid?->nik ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->profesi_sekarang ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->nama_tempat_kerja ? substr($item->nama_tempat_kerja, 0, 20) . (strlen($item->nama_tempat_kerja) > 20 ? '...' : '') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if ($item->is_accepted)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Disetujui
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Menunggu Persetujuan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                            @click="openDetail({
                                            id: {{ $item->id }},
                                            nama: '{{ $item->murid?->nama }}',
                                            nik: '{{ $item->murid?->nik }}',
                                            nisn: '{{ $item->murid?->nisn }}',
                                            profesi_sekarang: '{{ $item->profesi_sekarang }}',
                                            nama_tempat_kerja: '{{ $item->nama_tempat_kerja }}',
                                            kota_tempat_kerja: '{{ $item->kota_tempat_kerja }}',
                                            riwayat_pekerjaan: '{{ $item->riwayat_pekerjaan }}',
                                            kontak_wa: '{{ $item->kontak_wa }}',
                                            kontak_email: '{{ $item->kontak_email }}',
                                            alamat_sekarang: '{{ $item->update_alamat_sekarang }}',
                                            is_accepted: {{ $item->is_accepted ? 'true' : 'false' }}
                                        })"
                                            class="bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400 hover:bg-brand-200 dark:hover:bg-brand-900/50 rounded-lg px-3 py-1.5 text-sm font-medium transition">
                                            Lihat Lengkap
                                        </button>

                                        @if (!$item->is_accepted)
                                            <form id="approve-form-{{ $item->id }}"
                                                action="{{ route('validasi-alumni.approve', $item) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                            </form>
                                            <button type="button" @click="confirmApprove({{ $item->id }})"
                                                class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg px-3 py-1.5 text-sm font-medium transition">
                                                Setujui
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data validasi alumni
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card List -->
        <div class="block space-y-4 md:hidden">
            @forelse($validasi as $item)
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-3 flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $item->murid?->nama ?? 'N/A' }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                NIK: {{ $item->murid?->nik ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-3 space-y-1 text-sm">
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Profesi:</span> {{ $item->profesi_sekarang ?? '-' }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Tempat Kerja:</span> {{ $item->nama_tempat_kerja ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            Daftar: {{ $item->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <button type="button"
                            @click="openDetail({
                            id: {{ $item->id }},
                            nama: '{{ $item->murid?->nama }}',
                            nik: '{{ $item->murid?->nik }}',
                            nisn: '{{ $item->murid?->nisn }}',
                            profesi_sekarang: '{{ $item->profesi_sekarang }}',
                            nama_tempat_kerja: '{{ $item->nama_tempat_kerja }}',
                            kota_tempat_kerja: '{{ $item->kota_tempat_kerja }}',
                            riwayat_pekerjaan: '{{ $item->riwayat_pekerjaan }}',
                            kontak_wa: '{{ $item->kontak_wa }}',
                            kontak_email: '{{ $item->kontak_email }}',
                            alamat_sekarang: '{{ $item->update_alamat_sekarang }}'
                        })"
                            class="bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-400 hover:bg-brand-200 dark:hover:bg-brand-900/50 rounded-lg px-3 py-1.5 text-sm font-medium transition">
                            Lihat Lengkap
                        </button>
                        <form id="approve-form-{{ $item->id }}"
                            action="{{ route('validasi-alumni.approve', $item) }}" method="POST"
                            style="display: none;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                        </form>
                        @if (!$item->is_accepted)
                            <button type="button" @click="confirmApprove({{ $item->id }})"
                                class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 flex-1 rounded-lg px-3 py-2 text-xs font-medium transition">
                                Setujui
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data validasi alumni
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $validasi->links() }}
        </div>

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
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                    <button @click="closeDetail()"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                        Tutup
                    </button>
                    <button @click="confirmApprove(selectedValidasi?.id); closeDetail()" x-show="selectedValidasi && !selectedValidasi.is_accepted"
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

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </div>
@endsection
