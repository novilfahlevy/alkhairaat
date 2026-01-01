@extends('layouts.app')

@section('content')
  <div class="space-y-6 pb-60">
    <livewire:sekolah-counts-widget />
    <livewire:murid-counts-widget />
    <livewire:murid-guru-counts-widget />
    <livewire:komwil-per-provinsi-counts-widget />
  </div>
@endsection
