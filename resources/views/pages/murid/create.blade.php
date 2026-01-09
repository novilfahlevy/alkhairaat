@extends('layouts.app')

@section('styles')
  {{-- 1. Load CSS Flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

  {{-- 2. CSS Custom (LEBIH SEDIKIT & BERSIH) --}}
  <style>
    /* Styling Dasar Dark Mode Flatpickr */
    .dark .flatpickr-calendar {
      background: #1f2937 !important;
      border: 1px solid #374151 !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
    }

    /* Header (Bulan & Tahun) */
    .dark .flatpickr-current-month {
      color: #fff !important;
    }

    /* Panah Navigasi */
    .dark .flatpickr-month .flatpickr-prev-month,
    .dark .flatpickr-month .flatpickr-next-month {
      fill: #fff !important;
    }

    .dark .flatpickr-month .flatpickr-prev-month:hover svg,
    .dark .flatpickr-month .flatpickr-next-month:hover svg {
      fill: #60a5fa !important;
    }

    /* Input Tahun (Angka) */
    .dark .flatpickr-current-month .numInputWrapper input.numInput.cur-year {
      color: #fff !important;
    }

    /* Hari & Tanggal */
    .dark span.flatpickr-weekday {
      color: #9ca3af !important;
    }

    .dark .flatpickr-day {
      color: #e5e7eb !important;
    }

    .dark .flatpickr-day:hover {
      background: #374151 !important;
      border-color: #374151 !important;
    }

    /* Selected Day */
    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
      background: #2563eb !important;
      border-color: #2563eb !important;
      color: #fff !important;
    }

    /* === HILANGKAN CSS DROPDOWN YANG RUMIT TADI === */
    /* Kita tidak butuh CSS styling dropdown karena kita ubah modenya jadi Static */
  </style>
@endsection

@section('content')
  <div class="container mx-auto px-4 sm:px-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg dark:bg-gray-800 transition-colors duration-200">

      {{-- Header --}}
      <div class="flex justify-between items-center mb-6 border-b pb-4 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Form Tambah Murid</h2>
        <a href="{{ route('murid.index') }}"
          class="text-gray-600 hover:text-gray-800 font-medium dark:text-gray-400 dark:hover:text-white transition">
          &larr; Kembali
        </a>
      </div>

      {{-- Alert Error --}}
      @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition.opacity
          class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900/50 dark:border-red-600 dark:text-red-200"
          role="alert">
          <strong class="font-bold">Terjadi Kesalahan!</strong>
          <ul class="mt-1 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20">
              <title>Close</title>
              <path
                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
          </button>
        </div>
      @endif

      {{-- Form Start --}}
      <form action="{{ route('murid.store') }}" method="POST">
        @csrf

        {{-- ================= DATA PRIBADI ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 mb-4 mt-2 dark:text-gray-200">Data Pribadi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
              NISN <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" required
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                     bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
          <div>
            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">NIK</label>
            <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                     bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
        </div>

        <div class="mb-4">
          <label for="nama" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                   bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Tempat
              Lahir</label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                     bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>

          {{-- === TANGGAL LAHIR (Versi Static - No Dropdown) === --}}
          <div x-data="{
              init() {
                  flatpickr(this.$refs.picker, {
                      dateFormat: 'Y-m-d',
                      altInput: true,
                      altFormat: 'j F Y',
                      locale: 'id',
                      disableMobile: 'true',
          
                      // === KUNCI PERUBAHAN ADA DI SINI ===
                      monthSelectorType: 'static', // Ganti 'dropdown' jadi 'static'
                      // ===================================
          
                      defaultDate: '{{ old('tanggal_lahir') }}',
                      onOpen: (selectedDates, dateStr, instance) => {
                          if (document.documentElement.classList.contains('dark')) {
                              instance.calendarContainer.classList.add('dark');
                          } else {
                              instance.calendarContainer.classList.remove('dark');
                          }
                      }
                  });
              }
          }">
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">
              Tanggal Lahir
            </label>
            <div class="relative">
              <input x-ref="picker" type="text" name="tanggal_lahir" id="tanggal_lahir" placeholder="Pilih Tanggal..."
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                       bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors cursor-pointer">

              <div
                class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500 dark:text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        {{-- Jenis Kelamin --}}
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="jenis_kelamin">
            Jenis Kelamin <span class="text-red-500">*</span>
          </label>
          <select name="jenis_kelamin" id="jenis_kelamin" required
            class="shadow border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                   bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        {{-- ================= KONTAK ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 mb-4 dark:text-gray-200">Kontak</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="kontak_wa_hp" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">No. WhatsApp
              / HP</label>
            <input type="text" name="kontak_wa_hp" id="kontak_wa_hp" value="{{ old('kontak_wa_hp') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
          <div>
            <label for="kontak_email"
              class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Email</label>
            <input type="email" name="kontak_email" id="kontak_email" value="{{ old('kontak_email') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        {{-- ================= DATA ORANG TUA ================= --}}
        <h3 class="text-lg font-semibold text-gray-700 mb-4 dark:text-gray-200">Data Orang Tua</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Nama
              Ayah</label>
            <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
          <div>
            <label for="nomor_hp_ayah" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">No. HP
              Ayah</label>
            <input type="text" name="nomor_hp_ayah" id="nomor_hp_ayah" value="{{ old('nomor_hp_ayah') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Nama
              Ibu</label>
            <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
          <div>
            <label for="nomor_hp_ibu" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">No. HP
              Ibu</label>
            <input type="text" name="nomor_hp_ibu" id="nomor_hp_ibu" value="{{ old('nomor_hp_ibu') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
          </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        {{-- ================= STATUS ================= --}}
        <div class="mb-6">
          <label for="status_alumni" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-300">Status
            Alumni</label>
          <select name="status_alumni" id="status_alumni"
            class="w-full px-4 py-2 cursor-pointer border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white transition-colors">
            <option value="0" {{ old('status_alumni') == '0' ? 'selected' : '' }}>Belum Lulus (Siswa Aktif)
            </option>
            <option value="1" {{ old('status_alumni') == '1' ? 'selected' : '' }}>Sudah Lulus (Alumni)</option>
          </select>
        </div>

        <div class="flex justify-end mt-8">
          <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg shadow transition duration-200 dark:bg-blue-600 dark:hover:bg-blue-500">
            Simpan Data
          </button>
        </div>

      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
@endpush
