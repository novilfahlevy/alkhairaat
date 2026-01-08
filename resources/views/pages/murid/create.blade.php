@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">
      <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Form Tambah Murid</h2>
        <a href="{{ route('murid.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
          &larr; Kembali
        </a>
      </div>

      {{-- Menampilkan Error Validasi --}}
      @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <strong class="font-bold">Terjadi Kesalahan!</strong>
          <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('murid.store') }}" method="POST">
        @csrf

        {{-- Data Pribadi --}}
        <h3 class="text-lg font-semibold text-gray-700 mb-4 mt-2">Data Pribadi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN <span
                class="text-red-500">*</span></label>
            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" required
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
            <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="mb-4">
          <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
              class="text-red-500">*</span></label>
          <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
              class="text-red-500">*</span></label>
          <div class="flex space-x-4 mt-2">
            <label class="inline-flex items-center cursor-pointer">
              <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}
                class="form-radio text-blue-600 h-5 w-5">
              <span class="ml-2 text-gray-700">Laki-laki</span>
            </label>
            <label class="inline-flex items-center cursor-pointer">
              <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}
                class="form-radio text-pink-600 h-5 w-5">
              <span class="ml-2 text-gray-700">Perempuan</span>
            </label>
          </div>
        </div>

        <hr class="my-6 border-gray-200">

        {{-- Kontak --}}
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Kontak</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="kontak_wa_hp" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / HP</label>
            <input type="text" name="kontak_wa_hp" id="kontak_wa_hp" value="{{ old('kontak_wa_hp') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label for="kontak_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="kontak_email" id="kontak_email" value="{{ old('kontak_email') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <hr class="my-6 border-gray-200">

        {{-- Data Orang Tua --}}
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Data Orang Tua</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
            <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label for="nomor_hp_ayah" class="block text-sm font-medium text-gray-700 mb-1">No. HP Ayah</label>
            <input type="text" name="nomor_hp_ayah" id="nomor_hp_ayah" value="{{ old('nomor_hp_ayah') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
            <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <div>
            <label for="nomor_hp_ibu" class="block text-sm font-medium text-gray-700 mb-1">No. HP Ibu</label>
            <input type="text" name="nomor_hp_ibu" id="nomor_hp_ibu" value="{{ old('nomor_hp_ibu') }}"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <hr class="my-6 border-gray-200">

        {{-- Status --}}
        <div class="mb-6">
          <label for="status_alumni" class="block text-sm font-medium text-gray-700 mb-1">Status Alumni</label>
          <select name="status_alumni" id="status_alumni"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="0" {{ old('status_alumni') == '0' ? 'selected' : '' }}>Belum Lulus (Siswa Aktif)
            </option>
            <option value="1" {{ old('status_alumni') == '1' ? 'selected' : '' }}>Sudah Lulus (Alumni)</option>
          </select>
        </div>

        <div class="flex justify-end mt-8">
          <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
            Simpan Data
          </button>
        </div>

      </form>
    </div>
  </div>
@endsection
