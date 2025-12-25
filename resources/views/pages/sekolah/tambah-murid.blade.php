@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Page Header Card -->
        <div
            class="flex flex-col gap-y-4 md:flex-row md:justify-between md:items-center rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div>
                <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                    Tambah Murid
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sekolah: <span class="font-medium">{{ $sekolah->nama }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('sekolah.show', $sekolah) }}"
                    class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    <p class="font-medium mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $muridDefault = old('murid', [
                    [
                        'nama' => '',
                        'nisn' => '',
                        'nik' => '',
                        'tempat_lahir' => '',
                        'tanggal_lahir' => '',
                        'jenis_kelamin' => 'L',
                        'nama_ayah' => '',
                        'nomor_hp_ayah' => '',
                        'nama_ibu' => '',
                        'nomor_hp_ibu' => '',
                        'kontak_wa_hp' => '',
                        'kontak_email' => '',
                        'tahun_masuk' => (int) date('Y'),
                        'kelas' => '',
                        'status_kelulusan' => 'tidak',
                    ],
                ]);
            @endphp

            <!-- Form -->
            <form action="{{ route('sekolah.store-murid', $sekolah) }}" method="POST" id="muridForm"
                x-data="{
                    muridRows: [],
                    isSubmitting: false
                }" x-init="muridRows = {{ Js::from($muridDefault) }}" x-on:submit="isSubmitting = true"
                enctype="multipart/form-data">
                @csrf

                <div
                    class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/30 dark:bg-blue-900/20">
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        <span class="font-medium">Catatan:</span> Anda dapat menambahkan lebih dari satu murid sekaligus.
                        Klik tombol
                        "Tambah Baris" untuk menambah form tambahan.
                    </p>
                </div>

                <!-- Form Rows Container -->
                <div class="space-y-6" id="muridRows">
                    <template x-for="(row, index) in muridRows" :key="index">
                        <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                            <!-- Row Header -->
                            <div class="mb-6 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                    Data Murid <span x-text="index + 1"></span>
                                </h3>
                                <button type="button" @click="muridRows.splice(index, 1)" x-show="muridRows.length > 1"
                                    class="flex items-center rounded-lg border border-red-300 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <!-- Nama -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Nama <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="`murid[${index}][nama]`" x-model="row.nama"
                                            placeholder="Nama lengkap murid"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                            required />
                                    </div>

                                    <!-- NISN -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            NISN <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="`murid[${index}][nisn]`" x-model="row.nisn"
                                            placeholder="Nomor Induk Siswa Nasional"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                            required />
                                    </div>

                                    <!-- NIK -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            NIK
                                        </label>
                                        <input type="text" :name="`murid[${index}][nik]`" x-model="row.nik"
                                            placeholder="Nomor Induk Kependudukan"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Jenis Kelamin <span class="text-red-500">*</span>
                                        </label>
                                        <select :name="`murid[${index}][jenis_kelamin]`" x-model="row.jenis_kelamin"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            required>
                                            @foreach ($jenisKelaminOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tempat Lahir -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Tempat Lahir
                                        </label>
                                        <input type="text" :name="`murid[${index}][tempat_lahir]`"
                                            x-model="row.tempat_lahir" placeholder="Tempat lahir"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Tanggal Lahir
                                        </label>
                                        <div x-data="{
                                            flatpickrInstance: null,
                                            init() {
                                                this.$nextTick(() => {
                                                    this.flatpickrInstance = flatpickr(this.$refs.dateInput, {
                                                        mode: 'single',
                                                        static: true,
                                                        monthSelectorType: 'static',
                                                        dateFormat: 'Y-m-d',
                                                        defaultDate: row.tanggal_lahir || null,
                                                        onChange: (selectedDates, dateStr) => {
                                                            row.tanggal_lahir = dateStr;
                                                        }
                                                    });
                                                });
                                            },
                                            destroy() {
                                                if (this.flatpickrInstance) {
                                                    this.flatpickrInstance.destroy();
                                                    this.flatpickrInstance = null;
                                                }
                                            }
                                        }" x-init="init()" x-destroy="destroy()"
                                            class="relative custom-datepicker">
                                            <input x-ref="dateInput" type="text" :name="`murid[${index}][tanggal_lahir]`"
                                                x-model="row.tanggal_lahir" :id="`tanggal_lahir_${index}`"
                                                placeholder="Pilih tanggal lahir" autocomplete="off"
                                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                            <span
                                                class="absolute text-gray-500 -translate-y-1/2 pointer-events-none right-3 top-1/2 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                                    viewBox="0 0 24 24" fill="none" class="size-5">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Tahun Masuk -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Tahun Masuk <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" :name="`murid[${index}][tahun_masuk]`"
                                            x-model="row.tahun_masuk" placeholder="Tahun masuk"
                                            :min="new Date().getFullYear() - 20" :max="new Date().getFullYear() + 1"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                            required />
                                    </div>

                                    <!-- Kelas -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Kelas
                                        </label>
                                        <input type="text" :name="`murid[${index}][kelas]`" x-model="row.kelas"
                                            placeholder="Contoh: X A, 1 A, dll"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Nama Ayah -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Nama Ayah
                                        </label>
                                        <input type="text" :name="`murid[${index}][nama_ayah]`"
                                            x-model="row.nama_ayah" placeholder="Nama ayah"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Nomor HP Ayah -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Nomor HP Ayah
                                        </label>
                                        <input type="tel" :name="`murid[${index}][nomor_hp_ayah]`"
                                            x-model="row.nomor_hp_ayah"
                                            @input="row.nomor_hp_ayah = row.nomor_hp_ayah.replace(/[^0-9]/g, '')"
                                            placeholder="Nomor HP"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Nama Ibu -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Nama Ibu
                                        </label>
                                        <input type="text" :name="`murid[${index}][nama_ibu]`" x-model="row.nama_ibu"
                                            placeholder="Nama ibu"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Nomor HP Ibu -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Nomor HP Ibu
                                        </label>
                                        <input type="tel" :name="`murid[${index}][nomor_hp_ibu]`"
                                            x-model="row.nomor_hp_ibu"
                                            @input="row.nomor_hp_ibu = row.nomor_hp_ibu.replace(/[^0-9]/g, '')"
                                            placeholder="Nomor HP"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>
                                </div>

                                <!-- Full Width Section -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Kontak WA/HP -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Kontak WA/HP
                                        </label>
                                        <input type="tel" :name="`murid[${index}][kontak_wa_hp]`"
                                            x-model="row.kontak_wa_hp"
                                            @input="row.kontak_wa_hp = row.kontak_wa_hp.replace(/[^0-9]/g, '')"
                                            placeholder="Nomor kontak WhatsApp/HP"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>

                                    <!-- Kontak Email -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Kontak Email
                                        </label>
                                        <input type="email" :name="`murid[${index}][kontak_email]`"
                                            x-model="row.kontak_email" placeholder="Email"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Add Row Button -->
                <div class="mt-6">
                    <button type="button"
                        @click="muridRows.push({nama: '', nisn: '', nik: '', tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: 'L', nama_ayah: '', nomor_hp_ayah: '', nama_ibu: '', nomor_hp_ibu: '', kontak_wa_hp: '', kontak_email: '', tahun_masuk: new Date().getFullYear(), kelas: '', status_kelulusan: 'tidak'})"
                        class="flex items-center rounded-lg border border-brand-500 px-4 py-2.5 text-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-500 dark:text-brand-400 dark:hover:bg-brand-900/20">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Baris
                    </button>
                </div>

                <!-- Submit Buttons -->
                <div
                    class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <a href="{{ route('sekolah.show', $sekolah) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
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
                        <template x-if="!isSubmitting">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </template>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Murid'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validate that NISN values are unique
            const form = document.getElementById('muridForm');
            form.addEventListener('submit', function(e) {
                const nisnInputs = document.querySelectorAll('input[name*="[nisn]"]');
                const nisnValues = Array.from(nisnInputs).map(input => input.value.trim());

                // Check for empty values
                const emptyNisn = nisnValues.some(v => v === '');
                if (emptyNisn) {
                    e.preventDefault();
                    alert('NISN tidak boleh kosong pada semua baris');
                    return false;
                }

                // Check for duplicates
                const uniqueNisn = new Set(nisnValues);
                if (uniqueNisn.size !== nisnValues.length) {
                    e.preventDefault();
                    alert('NISN tidak boleh duplikat');
                    return false;
                }
            });
        });
    </script>
@endpush
