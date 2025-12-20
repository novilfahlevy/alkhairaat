@extends('layouts.app')

@section('content')
  <div>
    <x-dashboard.counts :provinsiCount="$provinsiCount" :kabupatenCount="$kabupatenCount" :sekolahCount="$sekolahCount" :sekolahCount="$sekolahCount" />
  </div>
@endsection
