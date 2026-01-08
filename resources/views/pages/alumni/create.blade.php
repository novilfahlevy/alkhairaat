@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('alumni.index') }}"
                class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-title-md font-semibold text-gray-800 dark:text-white/90">
                    {{ $title }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tambahkan data alumni baru secara manual atau impor dari file
                </p>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <x-ui.card>
        <x-slot:header>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Impor Alumni dari File</h2>
        </x-slot:header>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-400">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Warning Message -->
        @if (session('warning'))
            <div class="mb-6 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p>{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/30 dark:text-red-400">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Warning Message -->
        <div class="mb-6 rounded-lg bg-blue-50 p-4 text-sm text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
            <div class="mb-3 flex items-start justify-between gap-4">
                <div>
                    <p class="font-semibold mb-2">Untuk impor bulk, siapkan file Excel atau CSV dengan kolom berikut:</p>
                    <ul class="list-inside list-disc space-y-1">
                        <li><strong>NIK</strong> (required) - NIK murid (tanpa spasi, format: 12345678901234)</li>
                        <li><strong>Profesi Sekarang</strong> - Profesi alumni saat ini (Contoh: Guru, Dokter, Wiraswasta)</li>
                        <li><strong>Nama Tempat Kerja</strong> - Nama instansi/perusahaan/tempat usaha</li>
                        <li><strong>Kota Tempat Kerja</strong> - Kota tempat bekerja</li>
                        <li><strong>Riwayat Pekerjaan</strong> - Riwayat pekerjaan alumni</li>
                        <li><strong>Kontak WhatsApp</strong> - Nomor WhatsApp (format: 628xxxxxxxxx)</li>
                        <li><strong>Kontak Email</strong> - Alamat email alumni</li>
                        <li><strong>Alamat Sekarang</strong> - Alamat domisili/tinggal saat ini</li>
                    </ul>
                </div>
                <a href="{{ route('alumni.download-template') }}"
                    class="flex-shrink-0 inline-flex items-center gap-2 rounded-lg bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 transition dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60 whitespace-nowrap">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Template
                </a>
            </div>
        </div>

        @if (session('import_errors'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                <h3 class="mb-3 font-semibold text-red-800 dark:text-red-400">Error pada baris berikut:</h3>
                <div class="max-h-96 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-red-200 dark:border-red-800">
                                <th class="px-3 py-2 text-left">Baris</th>
                                <th class="px-3 py-2 text-left">Error</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-200 dark:divide-red-800">
                            @foreach (session('import_errors') as $error)
                                <tr>
                                    <td class="px-3 py-2 font-medium text-red-700 dark:text-red-400">
                                        {{ $error['row'] }}
                                    </td>
                                    <td class="px-3 py-2 text-red-600 dark:text-red-300">
                                        {{ $error['error'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('alumni.storeFile') }}" enctype="multipart/form-data" class="space-y-6"
            x-data="fileUploadForm" @submit="handleSubmit">
            @csrf

            <!-- File Upload -->
            <div class="space-y-3" x-data="fileUploader">
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Pilih File <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <label class="block cursor-pointer">
                        <div class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center transition dark:border-gray-700 dark:bg-gray-900/50 hover:border-brand-500 hover:bg-brand-50 dark:hover:bg-brand-900/20"
                            x-bind:class="{ '!border-brand-500': isDragging, '!bg-brand-50': isDragging, 'dark:!bg-brand-900/20': isDragging }"
                            @dragover.prevent="handleDragOver" @dragleave.prevent="handleDragLeave"
                            @drop.prevent="handleDrop">

                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33A3 3 0 0116.5 19.5H6.75z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Drag and drop file di sini atau <span
                                    class="font-semibold text-brand-600 dark:text-brand-400">klik untuk memilih</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                Format: Excel (.xlsx, .xls) atau CSV
                            </p>
                        </div>

                        <input type="file" name="file" class="hidden" accept=".xlsx,.xls,.csv" required
                            @change="handleFileChange">
                    </label>
                </div>
                @error('file')
                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror


                <!-- Selected File Display -->
                <div x-show="fileName" x-cloak class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex-1">
                            <p class="font-medium text-green-800 dark:text-green-400">File dipilih:</p>
                            <p class="text-sm text-green-700 dark:text-green-300" x-text="fileName"></p>
                        </div>
                        <button type="button" @click="clearFile"
                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                <a href="{{ route('alumni.index') }}"
                    class="border-gray-300 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-900 h-11 flex-1 rounded-lg border px-4 text-sm font-medium text-gray-700 transition dark:text-gray-300 flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" :disabled="uploading"
                    :class="{ 'opacity-70 cursor-not-allowed': uploading }"
                    class="bg-brand-500 hover:bg-brand-600 h-11 flex-1 rounded-lg px-4 text-sm font-medium text-white transition flex items-center justify-center disabled:opacity-70">
                    <span x-show="!uploading" class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Impor File
                    </span>
                    <span x-show="uploading" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </x-ui.card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUploadForm', () => ({
                uploading: false,

                handleSubmit(event) {
                    // Set uploading state after a small delay to ensure form submits
                    setTimeout(() => {
                        this.uploading = true;
                    }, 10);
                }
            }));

            Alpine.data('fileUploader', () => ({
                fileName: '',
                isDragging: false,

                handleDragOver() {
                    this.isDragging = true;
                },

                handleDragLeave() {
                    this.isDragging = false;
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const input = this.$el.querySelector('input[type=file]');
                    input.files = event.dataTransfer.files;
                    this.fileName = input.files[0]?.name || '';
                },

                handleFileChange(event) {
                    this.fileName = event.target.files[0]?.name || '';
                },

                clearFile() {
                    this.fileName = '';
                    this.$el.closest('[x-data]').querySelector('input[type=file]').value = '';
                }
            }));
        });
    </script>
@endpush