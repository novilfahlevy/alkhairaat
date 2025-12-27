@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60" x-data="muridExistingData()" @init="init()">
        <!-- Tab Navigation -->
        @include('pages.sekolah.murid.tambah-murid-tabs', compact('sekolah'))

        <!-- Search and Filters -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <form class="space-y-4" @submit.prevent="">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4 md:gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-3">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Cari Murid
                        </label>
                        <div class="relative">
                            <input type="text" x-model="search" @input="currentPage = 1; fetchMurid(1)"
                                placeholder="Cari berdasarkan nama atau NISN..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            <svg class="absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Results Per Page -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Per Halaman
                        </label>
                        <select x-model="perPage" @change="currentPage = 1; fetchMurid(1)"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="10">10</option>
                            <option value="20" selected>20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="rounded-lg bg-white p-8 text-center shadow-md dark:bg-gray-900">
            <div class="flex items-center justify-center">
                <svg class="mr-3 h-6 w-6 animate-spin text-brand-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                <span class="text-gray-600 dark:text-gray-400">Memuat data murid...</span>
            </div>
        </div>

        <!-- Table Container -->
        <div x-show="!isLoading && muridList.length > 0" class="rounded-lg bg-white shadow-md dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                NISN
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                Nama Murid
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                Jenis Kelamin
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                Tanggal Lahir
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900 dark:text-white">
                                Pilih
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="murid in muridList" :key="murid.id">
                            <tr
                                class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                    <span x-text="murid.nisn || '-'"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <span x-text="murid.nama"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span x-text="murid.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span x-text="formatDate(murid.tanggal_lahir)"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" x-model="selectedMuridIds" :value="murid.id"
                                        @change="updateSummary()"
                                        class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 cursor-pointer">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan <span x-text="pagination.from || 0"></span> hingga <span
                        x-text="pagination.to || 0"></span> dari <span x-text="pagination.total"></span> murid
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="fetchMurid(pagination.current_page - 1)"
                        :disabled="pagination.current_page === 1"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-1">
                        <template x-for="page in Array.from({length: pagination.last_page}, (_, i) => i + 1)"
                            :key="page">
                            <button type="button" @click="fetchMurid(page)"
                                :class="page === pagination.current_page ?
                                    'px-3 py-2 rounded-lg bg-brand-500 text-white text-sm font-medium' :
                                    'px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800'"
                                x-text="page">
                            </button>
                        </template>
                    </div>
                    <button type="button" @click="fetchMurid(pagination.current_page + 1)"
                        :disabled="pagination.current_page === pagination.last_page"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="!isLoading && muridList.length === 0 && !hasError"
            class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center dark:border-gray-700 dark:bg-gray-800">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada murid ditemukan</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Semua murid yang ada sudah terdaftar di sekolah ini, atau tidak ada murid yang sesuai dengan pencarian Anda.
            </p>
        </div>

        <!-- Error State -->
        <div x-show="hasError" class="rounded-lg bg-red-50 p-6 dark:bg-red-900/20">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400">Terjadi kesalahan</h3>
                    <p x-text="errorMessage" class="mt-2 text-sm text-red-700 dark:text-red-300"></p>
                </div>
            </div>
        </div>

        <!-- Selected Murid Summary and Submit Form -->
        <div x-show="selectedMuridIds.length > 0" class="rounded-lg bg-brand-50 p-6 dark:bg-brand-900/20">
            <form method="POST" action="{{ route('sekolah.store-existing-murid', ['sekolah' => $sekolah->id]) }}"
                @submit="isSubmitting = true">
                @csrf

                <!-- Hidden input for selected murid IDs -->
                <input type="hidden" name="murid_ids" x-model="selectedMuridIdsJson">
                <input type="hidden" name="kelas" x-model="kelas">

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-brand-900 dark:text-brand-400">
                                <span x-text="selectedMuridIds.length"></span> murid dipilih
                            </h3>
                            <p class="text-sm text-brand-700 dark:text-brand-300 mt-1">
                                Klik tombol "Simpan" untuk menambahkan murid ke sekolah ini.
                            </p>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-4">
                        <!-- Tahun Masuk Input -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-brand-900 dark:text-brand-400">
                                Tahun Masuk
                            </label>
                            <input type="number" name="tahun_masuk" x-model.number="tahunMasuk" min="1990"
                                :max="currentYear" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                placeholder="Contoh: 2024">
                        </div>

                        <!-- Kelas Input -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-brand-900 dark:text-brand-400">
                                Kelas
                            </label>
                            <input type="text" x-model="kelas"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                placeholder="Contoh: 1A, X-1">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-2.5 text-sm font-medium text-white transition"
                            :disabled="isSubmitting" :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                            <template x-if="!isSubmitting">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </template>
                            <template x-if="isSubmitting">
                                <svg class="mr-2 h-4 w-4 animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </template>
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Murid'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function muridExistingData() {
            return {
                search: '',
                perPage: '20',
                currentPage: 1,
                isLoading: false,
                hasError: false,
                errorMessage: '',
                muridList: [],
                selectedMuridIds: [],
                isSubmitting: false,
                tahunMasuk: new Date().getFullYear(),
                kelas: '',
                statusKelulusan: '',
                currentYear: new Date().getFullYear(),
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 20,
                    total: 0,
                    from: 0,
                    to: 0,
                },
                apiUrl: '{{ route('sekolah.get-existing-murid', ['sekolah' => $sekolah->id]) }}',

                get selectedMuridIdsJson() {
                    return JSON.stringify(this.selectedMuridIds);
                },

                async fetchMurid(page = 1) {
                    this.currentPage = page;
                    this.isLoading = true;
                    this.hasError = false;
                    this.errorMessage = '';

                    try {
                        const url = new URL(this.apiUrl, window.location.origin);
                        url.searchParams.append('search', this.search);
                        url.searchParams.append('per_page', this.perPage);
                        url.searchParams.append('page', page);

                        const response = await fetch(url.toString());

                        if (!response.ok) {
                            throw new Error('Gagal mengambil data murid');
                        }

                        const result = await response.json();
                        this.muridList = result.data;
                        this.pagination = result.pagination;
                    } catch (error) {
                        this.hasError = true;
                        this.errorMessage = error.message;
                    } finally {
                        this.isLoading = false;
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return '-';

                    try {
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    } catch (error) {
                        return '-';
                    }
                },

                updateSummary() {
                    // This is automatically handled by Alpine.js with x-model
                },

                init() {
                    this.fetchMurid(1);
                }
            }
        }
    </script>
@endpush
