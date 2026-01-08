@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Tab Navigation -->
        @include('pages.sekolah.murid.tambah-murid-tabs', compact('sekolah'))

        <!-- Form Card -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    <p class="font-medium mb-2">
                        <svg class="inline-block w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        Terjadi kesalahan:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-1">
                        @if ($errors->has('file'))
                            <li class="font-medium">{{ $errors->first('file') }}</li>
                            <ul class="list-circle list-inside ml-4 mt-1 text-xs opacity-90 space-y-1">
                                @if ($errors->first('file') === 'File harus diunggah.')
                                    <li>Pastikan Anda memilih file terlebih dahulu</li>
                                @elseif (str_contains($errors->first('file'), 'Format file'))
                                    <li>Format yang didukung: <code class="bg-red-200 px-1 rounded">.xlsx</code>, <code
                                            class="bg-red-200 px-1 rounded">.xls</code>, atau <code
                                            class="bg-red-200 px-1 rounded">.csv</code></li>
                                @elseif (str_contains($errors->first('file'), 'file'))
                                    <li>Input harus berupa file yang valid (bukan folder atau shortcut)</li>
                                @endif
                            </ul>
                        @else
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="mb-6 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Information Card -->
            <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/30 dark:bg-blue-900/20">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        <span class="font-medium">Catatan:</span> Unggah file Excel atau CSV berisi data murid yang ingin
                        ditambahkan. File akan diproses secara otomatis oleh sistem.
                    </p>
                    <a href="{{ route('sekolah.download-template') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-lg border border-blue-300 bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200 dark:border-blue-800 dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60 transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Template
                    </a>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('sekolah.store-murid-file', $sekolah) }}" method="POST" enctype="multipart/form-data"
                id="fileUploadForm" x-data="{
                    isUploading: false,
                    fileName: '',
                    handleDrop(e) {
                        this.isUploading = false;
                        const files = Array.from(e.dataTransfer.files);
                        if (files.length > 0) {
                            this.$refs.fileInput.files = e.dataTransfer.files;
                            this.fileName = files[0].name;
                        }
                    },
                    handleFileSelect(e) {
                        if (e.target.files.length > 0) {
                            this.fileName = e.target.files[0].name;
                        }
                    }
                }" @submit="isUploading = true">
                @csrf

                <!-- File Input -->
                <div class="space-y-2">
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Pilih File
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-10 dark:border-gray-600 dark:bg-gray-800 transition-colors"
                        @drop.prevent="handleDrop($event)"
                        @dragover.prevent="$el.classList.add('border-brand-500', 'bg-blue-50', 'dark:bg-blue-900/20')"
                        @dragleave.prevent="$el.classList.remove('border-brand-500', 'bg-blue-50', 'dark:bg-blue-900/20')">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <template x-if="!fileName">
                                <div>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <button type="button" @click.prevent="$refs.fileInput.click()"
                                            class="font-medium text-brand-600 hover:text-brand-500 dark:text-brand-400">
                                            Klik di sini
                                        </button>
                                        untuk memilih file atau seret file di sini
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Format: Excel (.xlsx, .xls) atau CSV (.csv)
                                    </p>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div>
                                    <svg class="mx-auto h-12 w-12 text-green-500 mb-2" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white"
                                        x-text="`File dipilih: ${fileName}`"></p>
                                    <button type="button" @click.prevent="$refs.fileInput.click()"
                                        class="text-xs text-brand-600 hover:text-brand-500 dark:text-brand-400 mt-2">
                                        Ganti file
                                    </button>
                                </div>
                            </template>
                        </div>
                        <input type="file" id="file" name="file" x-ref="fileInput" accept=".xlsx,.xls,.csv"
                            @change="handleFileSelect($event)" class="hidden" required>
                    </div>

                    <!-- File Validation Errors -->
                    @error('file')
                        <div class="mt-3 rounded-lg bg-red-50 p-3 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ $message }}</p>
                                    <ul class="mt-2 ml-0 text-xs text-red-700 dark:text-red-300 space-y-1">
                                        @if (str_contains($message, 'diunggah'))
                                            <li>✓ Pilih file Excel atau CSV dari komputer Anda</li>
                                            <li>✓ Seret dan lepas file ke area upload</li>
                                        @elseif (str_contains($message, 'Format'))
                                            <li>✓ Format yang didukung: <strong>.xlsx, .xls, .csv</strong></li>
                                            <li>✓ Pastikan file Anda berformat yang benar</li>
                                        @elseif (str_contains($message, 'Input'))
                                            <li>✓ Pilih file yang valid dari komputer Anda</li>
                                            <li>✓ Jangan seret folder atau shortcut</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @enderror

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Ukuran file maksimal: 5MB • Hanya satu file per upload
                    </p>
                </div>

                <!-- Uploaded Files Section -->
                @if ($uploadedFiles->count() > 0)
                    <div class="mt-6 space-y-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">File yang Sudah Diunggah</h3>
                        <div class="space-y-2">
                            @foreach ($uploadedFiles as $uploadedFile)
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex items-start gap-3 min-w-0 flex-1">
                                        <!-- File Icon -->
                                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>

                                        <!-- File Info -->
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $uploadedFile->file_original_name ?: basename($uploadedFile->file_path) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $uploadedFile->created_at->translatedFormat('d M Y H:i') }}
                                                @if ($uploadedFile->processed_rows !== null)
                                                    • {{ $uploadedFile->processed_rows }} berhasil
                                                    @if ($uploadedFile->error_count > 0)
                                                        • {{ $uploadedFile->error_count }} error
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if ($uploadedFile->is_finished === null)
                                            <span
                                                class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                <svg class="mr-1 h-2 w-2 animate-pulse" fill="currentColor"
                                                    viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Menunggu Proses
                                            </span>
                                        @elseif ($uploadedFile->is_finished === true)
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Berhasil
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Gagal
                                            </span>

                                            <!-- Error Details Button -->
                                            @if ($uploadedFile->has_errors && $uploadedFile->error_count > 0)
                                                <button type="button"
                                                    class="inline-flex items-center rounded border border-red-300 bg-red-50 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30"
                                                    onclick="toggleErrorDetails('error-details-{{ $uploadedFile->id }}')">
                                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    Lihat Error
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- Error Details (Hidden by default) -->
                                @if ($uploadedFile->has_errors && $uploadedFile->error_count > 0)
                                    <div id="error-details-{{ $uploadedFile->id }}"
                                        class="hidden mt-2 rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                                        <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Detail Error:
                                        </p>
                                        <div class="max-h-40 overflow-y-auto">
                                            <table class="min-w-full text-xs">
                                                <thead class="bg-red-100 dark:bg-red-900/30">
                                                    <tr>
                                                        <th class="px-2 py-1 text-left text-red-800 dark:text-red-200">
                                                            Baris</th>
                                                        <th class="px-2 py-1 text-left text-red-800 dark:text-red-200">NISN
                                                        </th>
                                                        <th class="px-2 py-1 text-left text-red-800 dark:text-red-200">Nama
                                                        </th>
                                                        <th class="px-2 py-1 text-left text-red-800 dark:text-red-200">
                                                            Error</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($uploadedFile->error_details_array as $error)
                                                        <tr class="border-b border-red-200 dark:border-red-800">
                                                            <td class="px-2 py-1 text-red-700 dark:text-red-300">
                                                                {{ $error['row'] ?? '-' }}</td>
                                                            <td class="px-2 py-1 text-red-700 dark:text-red-300">
                                                                {{ $error['nisn'] ?? '-' }}</td>
                                                            <td class="px-2 py-1 text-red-700 dark:text-red-300">
                                                                {{ $error['nama'] ?? '-' }}</td>
                                                            <td class="px-2 py-1 text-red-700 dark:text-red-300">
                                                                {{ $error['error'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Submit Buttons -->
                <div
                    class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
                        :disabled="isUploading" x-bind:class="{ 'opacity-70 cursor-not-allowed': isUploading }">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            x-show="!isUploading">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span x-show="!isUploading">Unggah File</span>
                        <span x-show="isUploading" class="flex items-center">
                            <svg class="mr-2 h-5 w-5 animate-spin" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Sedang Mengunggah...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Template Information Card -->
<div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
    <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Format File Template</h2>

    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        File Anda harus memiliki kolom-kolom berikut (sesuai urutan). Kolom dengan status "Wajib" harus diisi. Kolom
        dengan status "Opsional" dapat
        dikosongkan jika tidak ada data dan diisi di lain waktu.
    </p>

    <!-- Data Pribadi Section -->
    <h3 class="mb-2 text-md font-semibold text-gray-700 dark:text-gray-300">Data Pribadi</h3>
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Kolom
                    </th>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Deskripsi
                    </th>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        NISN
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor Induk Siswa Nasional (10 digit)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                            Wajib
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nama
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nama lengkap murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                            Wajib
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Jenis Kelamin
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        L (Laki-laki) atau P (Perempuan)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                            Wajib
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        NIK
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor Induk Kependudukan (16 digit)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tempat Lahir
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tempat lahir murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tanggal Lahir
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tanggal lahir (format: DD/MM/YYYY atau YYYY-MM-DD)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Kontak WA/HP
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor WhatsApp/HP murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Email
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Email murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Data Sekolah & Pendidikan Section -->
    <h3 class="mb-2 text-md font-semibold text-gray-700 dark:text-gray-300">Data Sekolah & Pendidikan</h3>
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Kolom
                    </th>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Deskripsi
                    </th>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Kelas
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Kelas murid (contoh: X A, 1 B, dll)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun Masuk
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun masuk murid (format: YYYY)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-orange-100 px-2 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                            Wajib
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun Keluar
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun keluar murid (format: YYYY)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Status Kelulusan
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Status kelulusan murid di sekolah ini (nilainya berupa: "Lulus", "Tidak Lulus", atau "Belum Lulus")
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun Mutasi Masuk
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun mutasi masuk (format: YYYY)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Alasan Mutasi Masuk
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Alasan mutasi masuk
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun Mutasi Keluar
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Tahun mutasi keluar (format: YYYY)
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Alasan Mutasi Keluar
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Alasan mutasi keluar
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Data Orang Tua Section -->
    <h3 class="mb-2 text-md font-semibold text-gray-700 dark:text-gray-300">Data Orang Tua</h3>
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Kolom
                    </th>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Deskripsi
                    </th>
                    <th
                        class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nama Ayah
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nama lengkap ayah murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor HP Ayah
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor HP/WA ayah murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nama Ibu
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nama lengkap ibu murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
                <tr>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor HP Ibu
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        Nomor HP/WA ibu murid
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                        <span
                            class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Opsional
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Data Alamat Section -->
    <h3 class="mb-2 text-md font-semibold text-gray-700 dark:text-gray-300">Data Alamat Asli/Domisili/Ayah/Ibu</h3>

    <div class="mb-4">
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th
                            class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kolom
                        </th>
                        <th
                            class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Deskripsi
                        </th>
                        <th
                            class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Provinsi Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Provinsi alamat asli murid
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kabupaten Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kabupaten/Kota alamat asli murid
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kecamatan Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kecamatan alamat asli murid
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kelurahan Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kelurahan alamat asli murid
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            RT Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Nomor RT alamat asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            RW Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Nomor RW alamat asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kode Pos Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Kode pos alamat asli (5 digit)
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Alamat Lengkap Asli
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Alamat lengkap asli (contoh: Jl. Pendidikan No. 123)
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Latitude Asli (Koordinat X)
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Koordinat latitude alamat asli (cth: -7.2575)
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Longitude Asli (Koordinat Y)
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            Koordinat longitude alamat asli (cth: 110.4324)
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-sm text-gray-700 dark:border-gray-700 dark:text-gray-300">
                            <span
                                class="inline-block rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Opsional
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
            <b>Catatan: </b>Kolom untuk alamat domisili, alamat ayah, dan alamat ibu memiliki kolom yang sama dengan
            Alamat Asli, tetapi dengan awalan Domisili/Ayah/Ibu.
        </p>
    </div>
</div>
    </div>

    <script>
        function toggleErrorDetails(id) {
            const element = document.getElementById(id);
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }
    </script>
@endsection
