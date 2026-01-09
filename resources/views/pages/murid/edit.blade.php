@extends('layouts.app')

@section('styles')
  {{-- 1. Load CSS Flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

  {{-- 2. CSS Custom (Versi Bersih & Static) --}}
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
  </style>
@endsection

@section('content')
  <div
    class="mx-auto px-4 sm:px-8 bg-white shadow-md dark:shadow-none rounded-lg dark:bg-black transition-colors duration-200">
    <div class="py-4 pb-20">

      {{-- Header --}}
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-white">Edit Data Murid</h2>

        {{-- Tombol Kembali --}}
        <a href="{{ route('murid.index') }}"
          class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-2 transition dark:text-gray-400 dark:hover:text-white">
          <span>&larr; Kembali</span>
        </a>
      </div>

      {{-- Card Container --}}
      <div class="">

        <form action="{{ route('murid.update', $murid->id) }}" method="POST">
          @csrf
          @method('PUT')

          {{-- Alert Error --}}
          @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition.opacity
              class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900/50 dark:border-red-600 dark:text-red-200"
              role="alert">
              <strong class="font-bold">Ada kesalahan input!</strong>
              <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button @click="show = false" type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20">
                  <title>Close</title>
                  <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
              </button>
            </div>
          @endif

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ================= BAGIAN 1: IDENTITAS DIRI ================= --}}
            <div class="md:col-span-2">
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mt-4 dark:text-gray-200 dark:border-gray-700">
                Identitas Diri</h3>
            </div>

            {{-- NISN --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nisn">
                NISN <span class="text-red-500">*</span>
              </label>
              <input type="number" name="nisn" id="nisn" value="{{ old('nisn', $murid->nisn) }}" required
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            </div>

            {{-- NIK --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nik">
                NIK
              </label>
              <input type="number" name="nik" id="nik" value="{{ old('nik', $murid->nik) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            </div>

            {{-- Nama Lengkap --}}
            <div class="md:col-span-2">
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nama">
                Nama Lengkap <span class="text-red-500">*</span>
              </label>
              <input type="text" name="nama" id="nama" value="{{ old('nama', $murid->nama) }}" required
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            </div>

            {{-- Jenis Kelamin --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="jenis_kelamin">
                Jenis Kelamin <span class="text-red-500">*</span>
              </label>
              <select name="jenis_kelamin" id="jenis_kelamin" required
                class="shadow border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
                <option value="L" {{ old('jenis_kelamin', $murid->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                  Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin', $murid->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                  Perempuan</option>
              </select>
            </div>

            {{-- Status Murid --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="status_alumni">
                Status <span class="text-red-500">*</span>
              </label>
              <select name="status_alumni" id="status_alumni" required
                class="shadow border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
                <option value="0" {{ old('status_alumni', $murid->status_alumni) == 0 ? 'selected' : '' }}>Aktif
                </option>
                <option value="1" {{ old('status_alumni', $murid->status_alumni) == 1 ? 'selected' : '' }}>Alumni
                </option>
              </select>
            </div>

            {{-- Tempat Lahir --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="tempat_lahir">
                Tempat Lahir
              </label>
              <input type="text" name="tempat_lahir" id="tempat_lahir" required
                value="{{ old('tempat_lahir', $murid->tempat_lahir) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            </div>

            {{-- TANGGAL LAHIR (FLATPICKR - STATIC MODE) --}}
            <div x-data="{
                init() {
                    flatpickr(this.$refs.picker, {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'j F Y',
                        locale: 'id',
                        disableMobile: 'true',
            
                        // === MODE STATIC (Tampilan Bersih) ===
                        monthSelectorType: 'static',
            
                        // === PENTING: Load Data Lama Disini ===
                        defaultDate: '{{ old('tanggal_lahir', $murid->tanggal_lahir) }}',
            
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
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="tanggal_lahir">
                Tanggal Lahir
              </label>
              <div class="relative">
                <input x-ref="picker" type="text" name="tanggal_lahir" id="tanggal_lahir" required
                  placeholder="Pilih Tanggal..."
                  class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                        bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white cursor-pointer">

                {{-- Icon Kalender --}}
                <div
                  class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500 dark:text-gray-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
              </div>
            </div>

            {{-- ================= BAGIAN 2: KONTAK SISWA ================= --}}
            <div class="md:col-span-2 mt-4">
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mt-4 dark:text-gray-200 dark:border-gray-700">
                Informasi Kontak Siswa</h3>
            </div>

            {{-- No HP / WA --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="kontak_wa_hp">
                No. HP / WhatsApp Siswa
              </label>
              <input type="text" name="kontak_wa_hp" id="kontak_wa_hp"
                value="{{ old('kontak_wa_hp', $murid->kontak_wa_hp) }}" placeholder="Contoh: 08123456789"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>

            {{-- Email --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="kontak_email">
                Email Siswa
              </label>
              <input type="email" name="kontak_email" id="kontak_email"
                value="{{ old('kontak_email', $murid->kontak_email) }}" placeholder="Contoh: murid@sekolah.sch.id"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>

            {{-- ================= BAGIAN 3: DATA ORANG TUA ================= --}}
            <div class="md:col-span-2 mt-4">
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mt-4 dark:text-gray-200 dark:border-gray-700">
                Data Orang Tua</h3>
            </div>

            {{-- Nama Ayah --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nama_ayah">
                Nama Ayah
              </label>
              <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah', $murid->nama_ayah) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            </div>

            {{-- No HP Ayah --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nomor_hp_ayah">
                Nomor HP Ayah
              </label>
              <input type="text" name="nomor_hp_ayah" id="nomor_hp_ayah"
                value="{{ old('nomor_hp_ayah', $murid->nomor_hp_ayah) }}" placeholder="Contoh: 081xxxxxxx"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>

            {{-- Nama Ibu --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nama_ibu">
                Nama Ibu
              </label>
              <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu', $murid->nama_ibu) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white">
            </div>

            {{-- No HP Ibu --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="nomor_hp_ibu">
                Nomor HP Ibu
              </label>
              <input type="text" name="nomor_hp_ibu" id="nomor_hp_ibu"
                value="{{ old('nomor_hp_ibu', $murid->nomor_hp_ibu) }}" placeholder="Contoh: 081xxxxxxx"
                class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-300
                      bg-white dark:bg-black border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>

          </div>

          <div class="flex items-center justify-end mt-16 gap-4">
            <a href="{{ route('murid.index') }}"
              class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline transition 
                    dark:bg-gray-600 dark:hover:bg-gray-500">
              Batal
            </a>

            <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline transition flex items-center gap-2
                    dark:bg-blue-600 dark:hover:bg-blue-500">
              <i class="fas fa-save"></i>
              <span>Simpan Perubahan</span>
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  {{-- Load Javascript Flatpickr Library Saja --}}
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
@endpush
