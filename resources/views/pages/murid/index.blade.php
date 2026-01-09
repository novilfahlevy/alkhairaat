@extends('layouts.app')

@section('content')
  {{-- Alpine Component untuk Data Murid --}}
  <div x-data="studentData()" x-init="init()" class="container mx-auto">

    {{-- Header & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
      <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-white text-center sm:text-left">
        Daftar Data Murid
      </h2>

      <a href="{{ route('murid.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded shadow transition duration-200 flex items-center gap-2 w-full sm:w-auto justify-center">
        <i class="fas fa-plus"></i>
        <span>Tambah Murid</span>
      </a>
    </div>

    {{-- Notification --}}
    @if (session('success'))
      <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms
        class="fixed top-5 right-5 z-50 flex items-center backdrop-blur-[2px] w-full max-w-xs p-4 text-green-700 bg-green-100/80 border border-green-400 rounded-lg shadow-lg dark:bg-gray-800/50 dark:text-green-400 dark:border-green-800"
        role="alert">
        <div class="ml-3 text-sm font-medium">
          {{ session('success') }}
        </div>
        <button type="button" @click="show = false"
          class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700">
          <span class="sr-only">Close</span>
          <i class="fas fa-times w-5 h-5 flex items-center justify-center"></i>
        </button>
      </div>
    @endif

    {{-- SEARCH & FILTER --}}
    <div
      class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-200">
      <div class="flex flex-col md:flex-row gap-4">
        {{-- Search Input --}}
        <div class="flex-1">
          <label for="search" class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Cari</label>
          <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
            </span>
            <input type="text" x-model.debounce.500ms="search" placeholder="Cari Nama atau NISN..."
              class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 
                     bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 
                     text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 transition-colors duration-200">
          </div>
        </div>

        {{-- Filters --}}
        <div class="grid grid-cols-2 md:flex md:w-auto gap-4">
          {{-- Filter Gender --}}
          <div class="w-full md:w-40">
            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Gender</label>
            <select x-model="gender"
              class="w-full mt-1 py-2 px-3 cursor-pointer border rounded-lg focus:outline-none focus:border-blue-500
                     bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 transition-colors duration-200">
              <option value="">Semua</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          {{-- Filter Status --}}
          <div class="w-full md:w-40">
            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Status</label>
            <select x-model="status"
              class="w-full mt-1 cursor-pointer py-2 px-3 border rounded-lg focus:outline-none focus:border-blue-500
                     bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 transition-colors duration-200">
              <option value="">Semua</option>
              <option value="0">Aktif</option>
              <option value="1">Alumni</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    {{-- CONTAINER DATA --}}
    {{-- PERBAIKAN DI SINI: Hapus .prevent --}}
    <div id="tableContainer" x-html="html" :class="{ 'opacity-50 pointer-events-none': isLoading }"
      @click="handlePagination($event)" class="w-full transition-opacity duration-200">
      @include('pages.murid._table')
    </div>

  </div>

  <script>
    function studentData() {
      return {
        search: '{{ request('search') }}',
        gender: '{{ request('jenis_kelamin') }}',
        status: '{{ request('status_alumni') }}',
        html: '',
        isLoading: false,

        init() {
          this.html = document.getElementById('tableContainer').innerHTML;
          this.$watch('search', () => this.fetchData());
          this.$watch('gender', () => this.fetchData());
          this.$watch('status', () => this.fetchData());
        },

        fetchData(page = 1) {
          this.isLoading = true;
          const params = new URLSearchParams({
            search: this.search,
            jenis_kelamin: this.gender,
            status_alumni: this.status,
            page: page
          });

          fetch(`{{ route('murid.index') }}?${params.toString()}`, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(response => response.text())
            .then(data => {
              this.html = data;
              this.isLoading = false;
            })
            .catch(error => {
              console.error('Error:', error);
              this.isLoading = false;
            });
        },

        // PERBAIKAN LOGIKA DI SINI
        handlePagination(event) {
          // Cari elemen anchor (<a>) terdekat yang diklik
          const link = event.target.closest('a');

          // Cek apakah link tersebut valid
          if (!link) return;

          // Cek apakah link tersebut adalah link pagination
          // Biasanya pagination Laravel memiliki URL dengan parameter ?page=X
          const isPagination = link.href.includes('page=');

          // Jika link pagination, maka kita cegah reload dan gunakan fetch
          if (isPagination) {
            event.preventDefault(); // Stop Browser Reload HANYA untuk pagination

            const url = new URL(link.href);
            const page = url.searchParams.get('page');

            if (page) {
              this.fetchData(page);
            }
          }
          // Jika BUKAN pagination (tombol edit/hapus), biarkan browser bekerja normal.
        }
      }
    }
  </script>
@endsection
