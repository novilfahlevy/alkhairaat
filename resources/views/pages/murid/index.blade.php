@extends('layouts.app')

@section('content')
  <div class="mx-auto">
    <div class="">
      {{-- Header & Tombol Tambah (TETAP SAMA) --}}
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        {{-- ... kode header tetap sama ... --}}
        <h2 class="text-2xl font-bold leading-tight text-gray-800">Daftar Data Murid</h2>
        <a href="{{ route('murid.create') }}"
          class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded shadow transition duration-200 flex items-center gap-2">
          <i class="fas fa-plus"></i>
          <span>Tambah Murid</span>
        </a>
      </div>

      {{-- Notification (TETAP SAMA) --}}
      @if (session('success'))
        {{-- ... kode alert tetap sama ... --}}
      @endif

      {{-- SEARCH & FILTER --}}
      <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        {{-- Hapus action dan method form karena kita pakai AJAX, tapi biarkan untuk fallback --}}
        <form id="filterForm" action="{{ route('murid.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">

          {{-- Search Input --}}
          <div class="flex-1">
            <label for="search" class="text-xs font-semibold text-gray-600 uppercase">Cari</label>
            <div class="relative mt-1">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
              </span>
              {{-- PERUBAHAN: Tambahkan ID "searchInput" --}}
              <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                placeholder="Cari Nama atau NISN... (Ketik untuk mencari)">
            </div>
          </div>

          {{-- Filter Jenis Kelamin --}}
          <div class="w-full md:w-1/6">
            <label for="jenis_kelamin" class="text-xs font-semibold text-gray-600 uppercase">Gender</label>
            {{-- PERUBAHAN: Tambahkan ID dan onchange --}}
            <select id="genderFilter" name="jenis_kelamin"
              class="w-full mt-1 py-2 px-3 border rounded-lg bg-white focus:outline-none focus:border-blue-500">
              <option value="">Semua</option>
              <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
              <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>

          {{-- Filter Status --}}
          <div class="w-full md:w-1/6">
            <label for="status_alumni" class="text-xs font-semibold text-gray-600 uppercase">Status</label>
            {{-- PERUBAHAN: Tambahkan ID dan onchange --}}
            <select id="statusFilter" name="status_alumni"
              class="w-full mt-1 py-2 px-3 border rounded-lg bg-white focus:outline-none focus:border-blue-500">
              <option value="">Semua</option>
              <option value="0" {{ request('status_alumni') === '0' ? 'selected' : '' }}>Aktif</option>
              <option value="1" {{ request('status_alumni') === '1' ? 'selected' : '' }}>Alumni</option>
            </select>
          </div>
        </form>
      </div>

      {{-- CONTAINER TABEL --}}
      <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
        {{-- PERUBAHAN: Tambahkan ID "tableContainer" --}}
        <div id="tableContainer">
          {{-- Panggil Partial Tabel --}}
          @include('pages.murid._table')
        </div>
      </div>

    </div>
  </div>

  {{-- SCRIPT AJAX --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {

      // 1. Fungsi Inti: Mengambil SEMUA data input saat ini
      function fetch_data(page = 1) {
        // Ambil value dari Search
        const search = $('#searchInput').val();
        // Ambil value dari Filter Gender
        const gender = $('#genderFilter').val();
        // Ambil value dari Filter Status
        const status = $('#statusFilter').val();

        // Efek loading tipis
        $('#tableContainer').addClass('opacity-50');

        // Kirim SEMUA data ke Controller
        $.ajax({
          url: "{{ route('murid.index') }}",
          data: {
            search: search, // Kirim teks pencarian
            jenis_kelamin: gender, // Kirim pilihan gender
            status_alumni: status, // Kirim pilihan status
            page: page
          },
          success: function(data) {
            $('#tableContainer').html(data);
            $('#tableContainer').removeClass('opacity-50');
          }
        });
      }

      // 2. Event Listener: SEARCH (Tanpa Delay / Realtime Instan)
      $('#searchInput').on('keyup', function() {
        fetch_data(); // Panggil fungsi utama
      });

      // 3. Event Listener: FILTER (Gender & Status)
      $('#genderFilter, #statusFilter').on('change', function() {
        fetch_data(); // Panggil fungsi utama
      });

      // 4. Event Listener: PAGINATION
      $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        fetch_data(page); // Panggil fungsi utama dengan halaman tertentu
      });

    });
  </script>
@endsection
