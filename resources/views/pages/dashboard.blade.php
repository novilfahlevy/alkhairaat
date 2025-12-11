@extends('layouts.app')

@section('content')
  <div>
    <x-dashboard.counts :provinsiCount="$provinsiCount" :kabupatenCount="$kabupatenCount" :lembagaCount="$lembagaCount" />
  </div>
@endsection
