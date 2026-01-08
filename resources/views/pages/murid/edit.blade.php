@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
      {{-- Header --}}
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">Edit Data Murid</h2>
        <a href="{{ route('murid.index') }}"
          class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-2 transition">
          <i class="fas fa-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>

      <div class="bg-white shadow rounded-lg p-6 sm:p-8">
        <form action="{{ route('murid.update', $murid->id) }}" method="POST">
          @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
              <strong class="font-bold">Ada kesalahan input!</strong>
              <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          @csrf
          @method('PUT') {{-- Wajib untuk proses Update --}}

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ================= BAGIAN 1: IDENTITAS DIRI ================= --}}
            <div class="md:col-span-2">
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Identitas Diri</h3>
            </div>

            {{-- NISN --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nisn">
                NISN <span class="text-red-500">*</span>
              </label>
              <input type="number" name="nisn" id="nisn" value="{{ old('nisn', $murid->nisn) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300 @error('nisn') border-red-500 @enderror">
              @error('nisn')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- NIK --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nik">
                NIK
              </label>
              <input type="number" name="nik" id="nik" value="{{ old('nik', $murid->nik) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- Nama Lengkap --}}
            <div class="md:col-span-2">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">
                Nama Lengkap <span class="text-red-500">*</span>
              </label>
              <input type="text" name="nama" id="nama" value="{{ old('nama', $murid->nama) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300 @error('nama') border-red-500 @enderror">
              @error('nama')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
              @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_kelamin">
                Jenis Kelamin <span class="text-red-500">*</span>
              </label>
              <select name="jenis_kelamin" id="jenis_kelamin"
                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                <option value="L" {{ old('jenis_kelamin', $murid->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                  Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin', $murid->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                  Perempuan</option>
              </select>
            </div>

            {{-- Status Murid --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="status_alumni">
                Status <span class="text-red-500">*</span>
              </label>
              <select name="status_alumni" id="status_alumni"
                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                <option value="0" {{ old('status_alumni', $murid->status_alumni) == 0 ? 'selected' : '' }}>Aktif
                </option>
                <option value="1" {{ old('status_alumni', $murid->status_alumni) == 1 ? 'selected' : '' }}>Alumni
                </option>
              </select>
            </div>

            {{-- Tempat Lahir --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="tempat_lahir">
                Tempat Lahir <span class="text-red-500">*</span>
              </label>
              <input type="text" name="tempat_lahir" id="tempat_lahir"
                value="{{ old('tempat_lahir', $murid->tempat_lahir) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- Tanggal Lahir --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal_lahir">
                Tanggal Lahir <span class="text-red-500">*</span>
              </label>
              <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                value="{{ old('tanggal_lahir', $murid->tanggal_lahir ? $murid->tanggal_lahir->format('Y-m-d') : '') }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- ================= BAGIAN 2: KONTAK SISWA ================= --}}
            <div class="md:col-span-2 mt-4">
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Kontak Siswa</h3>
            </div>

            {{-- No HP / WA --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="kontak_wa_hp">
                No. HP / WhatsApp Siswa
              </label>
              <input type="text" name="kontak_wa_hp" id="kontak_wa_hp"
                value="{{ old('kontak_wa_hp', $murid->kontak_wa_hp) }}" placeholder="Contoh: 08123456789"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- Email --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="kontak_email">
                Email Siswa
              </label>
              <input type="email" name="kontak_email" id="kontak_email"
                value="{{ old('kontak_email', $murid->kontak_email) }}" placeholder="Contoh: murid@sekolah.sch.id"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- ================= BAGIAN 3: DATA ORANG TUA ================= --}}
            <div class="md:col-span-2 mt-4">
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Data Orang Tua</h3>
            </div>

            {{-- Nama Ayah --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_ayah">
                Nama Ayah
              </label>
              <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah', $murid->nama_ayah) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- No HP Ayah --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nomor_hp_ayah">
                Nomor HP Ayah
              </label>
              <input type="text" name="nomor_hp_ayah" id="nomor_hp_ayah"
                value="{{ old('nomor_hp_ayah', $murid->nomor_hp_ayah) }}" placeholder="Contoh: 081xxxxxxx"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- Nama Ibu --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_ibu">
                Nama Ibu
              </label>
              <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu', $murid->nama_ibu) }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            {{-- No HP Ibu --}}
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2" for="nomor_hp_ibu">
                Nomor HP Ibu
              </label>
              <input type="text" name="nomor_hp_ibu" id="nomor_hp_ibu"
                value="{{ old('nomor_hp_ibu', $murid->nomor_hp_ibu) }}" placeholder="Contoh: 081xxxxxxx"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

          </div>
          {{-- Akhir Grid --}}

          {{-- Tombol Aksi --}}
          <div class="flex items-center justify-end mt-8 gap-4">
            <a href="{{ route('murid.index') }}"
              class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
              Batal
            </a>
            <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition flex items-center gap-2">
              <i class="fas fa-save"></i>
              <span>Simpan Perubahan</span>
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection
