@extends('layouts.app')

@section('content')
  <div class="space-y-6 pb-60">
    <!-- Tombol Cetak PDF -->
    <div class="flex justify-end mb-4">
      <a href="{{ route('dashboard.export-pdf') }}"
        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-md transition-colors duration-150 dark:bg-emerald-500 dark:hover:bg-emerald-600">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Cetak PDF
      </a>
    </div>

    <livewire:sekolah-counts-widget lazy />
    <livewire:murid-alumni-counts-by-province-widget lazy />
    {{-- <livewire:alumni-per-provinsi-widget lazy /> --}}
    <livewire:murid-guru-counts-widget lazy />
    {{-- <livewire:komwil-per-provinsi-counts-widget lazy /> --}}
  </div>
@endsection
