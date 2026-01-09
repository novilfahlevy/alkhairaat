@extends('layouts.app')

@section('content')
  <div class="space-y-6 pb-60">
    <livewire:sekolah-counts-widget lazy />
    <livewire:murid-counts-widget lazy />
    <livewire:murid-guru-counts-widget lazy />
    {{-- <livewire:komwil-per-provinsi-counts-widget lazy /> --}}
  </div>
@endsection
