@extends('layouts.app')

@section('content')
    <div class="space-y-6 pb-60">
        @include('pages.sekolah.guru.tambah-guru-tabs', compact('sekolah'))
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Tambah Guru Baru</h1>
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    <p class="font-medium mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('sekolah.store-guru', $sekolah) }}" method="POST" x-data="{ isSubmitting: false }" x-on:submit="isSubmitting = true">
                @csrf
                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Data Guru
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama', '') }}"
                                    placeholder="Nama lengkap guru"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 {{ $errors->has('nama') ? 'border-red-500' : '' }}"
                                    required />
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gelar
                                    Depan</label>
                                <input type="text" name="nama_gelar_depan" value="{{ old('nama_gelar_depan', '') }}"
                                    placeholder="Gelar depan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama_gelar_depan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gelar
                                    Belakang</label>
                                <input type="text" name="nama_gelar_belakang"
                                    value="{{ old('nama_gelar_belakang', '') }}" placeholder="Gelar belakang"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama_gelar_belakang')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik', '') }}"
                                    placeholder="Nomor Induk Kependudukan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nik')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white {{ $errors->has('jenis_kelamin') ? 'border-red-500' : '' }}"
                                    required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" @if (old('jenis_kelamin') == 'L') selected @endif>Laki-laki
                                    </option>
                                    <option value="P" @if (old('jenis_kelamin') == 'P') selected @endif>Perempuan
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tempat
                                    Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', '') }}"
                                    placeholder="Kota/Kabupaten lahir"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('tempat_lahir')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-form.date-picker name="tanggal_lahir" label="Tanggal Lahir" placeholder="Pilih tanggal lahir" mode="single" dateFormat="Y-m-d" :defaultDate="old('tanggal_lahir')" />
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Perkawinan</label>
                                <select name="status_perkawinan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Status</option>
                                    <option value="lajang" @if (old('status_perkawinan') == 'lajang') selected @endif>Lajang</option>
                                    <option value="menikah" @if (old('status_perkawinan') == 'menikah') selected @endif>Menikah
                                    </option>
                                </select>
                                @error('status_perkawinan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Kepegawaian</label>
                                <select name="status_kepegawaian"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Status</option>
                                    <option value="PNS" @if (old('status_kepegawaian') == 'PNS') selected @endif>PNS</option>
                                    <option value="Non PNS" @if (old('status_kepegawaian') == 'Non PNS') selected @endif>Non PNS
                                    </option>
                                    <option value="PPPK" @if (old('status_kepegawaian') == 'PPPK') selected @endif>PPPK</option>
                                </select>
                                @error('status_kepegawaian')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NPK</label>
                                <input type="text" name="npk" value="{{ old('npk', '') }}" placeholder="NPK"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('npk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NUPTK</label>
                                <input type="text" name="nuptk" value="{{ old('nuptk', '') }}" placeholder="NUPTK"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nuptk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No.
                                    HP/WA</label>
                                <input type="text" name="kontak_wa_hp" value="{{ old('kontak_wa_hp', '') }}"
                                    placeholder="Nomor HP/WA"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kontak_wa_hp')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                <input type="email" name="kontak_email" value="{{ old('kontak_email', '') }}"
                                    placeholder="Email"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kontak_email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor
                                    Rekening</label>
                                <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', '') }}"
                                    placeholder="Nomor Rekening"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nomor_rekening')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Rekening
                                    Atas Nama</label>
                                <input type="text" name="rekening_atas_nama"
                                    value="{{ old('rekening_atas_nama', '') }}" placeholder="Rekening Atas Nama"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('rekening_atas_nama')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bank</label>
                                <input type="text" name="bank_rekening" value="{{ old('bank_rekening', '') }}"
                                    placeholder="Bank"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('bank_rekening')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Alamat Section -->
                    <div class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-4">Alamat Domisili</h4>
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Provinsi</label>
                                    <input type="text" name="alamat_provinsi"
                                        value="{{ old('alamat_provinsi', '') }}" placeholder="Provinsi"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_provinsi')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                    <input type="text" name="alamat_kecamatan"
                                        value="{{ old('alamat_kecamatan', '') }}" placeholder="Kecamatan"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_kecamatan')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">RT</label>
                                    <input type="text" name="alamat_rt" value="{{ old('alamat_rt', '') }}"
                                        placeholder="RT"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_rt')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode
                                        Pos</label>
                                    <input type="text" name="alamat_kode_pos"
                                        value="{{ old('alamat_kode_pos', '') }}" placeholder="Kode Pos"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_kode_pos')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten/Kota</label>
                                    <input type="text" name="alamat_kabupaten"
                                        value="{{ old('alamat_kabupaten', '') }}" placeholder="Kabupaten/Kota"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_kabupaten')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelurahan/Desa</label>
                                    <input type="text" name="alamat_kelurahan"
                                        value="{{ old('alamat_kelurahan', '') }}" placeholder="Kelurahan/Desa"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_kelurahan')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">RW</label>
                                    <input type="text" name="alamat_rw" value="{{ old('alamat_rw', '') }}"
                                        placeholder="RW"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_rw')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat
                                        Lengkap</label>
                                    <input type="text" name="alamat_lengkap" value="{{ old('alamat_lengkap', '') }}"
                                        placeholder="Alamat lengkap"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_lengkap')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Buttons -->
                    <div
                        class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                        <a href="{{ route('sekolah.show', $sekolah) }}"
                            class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Batal</a>
                        <button type="submit"
                            class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
                            :disabled="isSubmitting"
                            x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                            <template x-if="isSubmitting">
                                <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </template>
                            <template x-if="!isSubmitting">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <span>Simpan Guru</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
