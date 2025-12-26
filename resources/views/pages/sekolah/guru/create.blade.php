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

            <form action="{{ route('sekolah.store-guru', $sekolah) }}" method="POST" x-data="guruForm()"
                x-on:submit="isSubmitting = true">
                @csrf
                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Data Guru
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Kolom Kiri: Input Wajib dan Identitas -->
                        <div class="space-y-6">
                            <!-- Jenis Jabatan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Jabatan <span class="text-red-500">*</span></label>
                                <select name="jenis_jabatan" required onchange="onJenisJabatanChange()"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Jenis Jabatan</option>
                                    @foreach (\App\Models\JabatanGuru::JENIS_JABATAN_OPTIONS as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('jenis_jabatan') == $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_jabatan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama', '') }}" required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- NIK -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="nik" value="{{ old('nik', '') }}" required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                        x-model="nik" x-on:blur="checkNik" inputmode="numeric" pattern="[0-9]*" />
                                    <div x-show="nikChecking" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 animate-spin text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('nik')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <div x-show="nikError" class="mt-1 text-sm text-red-600 dark:text-red-400"
                                    x-html="nikErrorMessage"></div>
                            </div>
                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                    Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    @foreach (\App\Models\Guru::JENIS_KELAMIN_OPTIONS as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('jenis_kelamin') == $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_kelamin')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    <span class="text-red-500">*</span></label>
                                <select name="status" required name="status"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Status</option>
                                    @foreach (\App\Models\Guru::STATUS_OPTIONS as $key => $label)
                                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Status Kepegawaian -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Kepegawaian <span class="text-red-500">*</span></label>
                                <select name="status_kepegawaian" required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Status Kepegawaian</option>
                                    @foreach (\App\Models\Guru::STATUS_KEPEGAWAIAN_OPTIONS as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status_kepegawaian') == $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_kepegawaian')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Keterangan Jabatan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan
                                    Jabatan <span class="text-red-500">*</span></label>
                                <input type="text" name="keterangan_jabatan" value="{{ old('keterangan_jabatan', '') }}"
                                    placeholder="Keterangan Jabatan" required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('keterangan_jabatan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- NPK -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NPK</label>
                                <input type="number" name="npk" value="{{ old('npk', '') }}" placeholder="NPK"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                    inputmode="numeric" pattern="[0-9]*" />
                                @error('npk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- NUPTK -->
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NUPTK</label>
                                <input type="number" name="nuptk" value="{{ old('nuptk', '') }}" placeholder="NUPTK"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                    inputmode="numeric" pattern="[0-9]*" />
                                @error('nuptk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan: Data Opsional -->
                        <div class="space-y-6">
                            <!-- Gelar Depan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gelar
                                    Depan</label>
                                <input type="text" name="nama_gelar_depan" value="{{ old('nama_gelar_depan', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama_gelar_depan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Gelar Belakang -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Gelar
                                    Belakang</label>
                                <input type="text" name="nama_gelar_belakang"
                                    value="{{ old('nama_gelar_belakang', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('nama_gelar_belakang')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Tempat Lahir -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tempat
                                    Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('tempat_lahir')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Tanggal Lahir -->
                            <div>
                                <x-form.date-picker name="tanggal_lahir" label="Tanggal Lahir"
                                    placeholder="Pilih tanggal lahir" mode="single" dateFormat="Y-m-d"
                                    :defaultDate="old('tanggal_lahir')" />
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Status Perkawinan -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                    Perkawinan</label>
                                <select name="status_perkawinan"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="">Pilih Status Perkawinan</option>
                                    @foreach (\App\Models\Guru::STATUS_PERKAWINAN_OPTIONS as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status_perkawinan') == $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_perkawinan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- No. HP/WA -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No.
                                    HP/WA</label>
                                <input type="number" name="kontak_wa_hp" value="{{ old('kontak_wa_hp', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kontak_wa_hp')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                <input type="email" name="kontak_email" value="{{ old('kontak_email', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('kontak_email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Nomor Rekening -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor
                                    Rekening</label>
                                <input type="number" name="nomor_rekening" value="{{ old('nomor_rekening', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                    inputmode="numeric" pattern="[0-9]*" />
                                @error('nomor_rekening')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Rekening Atas Nama -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Rekening
                                    Atas Nama</label>
                                <input type="text" name="rekening_atas_nama"
                                    value="{{ old('rekening_atas_nama', '') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                @error('rekening_atas_nama')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Bank Rekening -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bank
                                    Rekening</label>
                                <input type="text" name="bank_rekening" value="{{ old('bank_rekening', '') }}"
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
                                <!-- Provinsi -->
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Provinsi
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="alamat_provinsi" value="{{ old('alamat_provinsi', '') }}"
                                        placeholder="Provinsi" required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_provinsi')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Kabupaten -->
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten/Kota
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="alamat_kabupaten" value="{{ old('alamat_kabupaten', '') }}"
                                        placeholder="Kabupaten/Kota" required
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_kabupaten')
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
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelurahan/Desa</label>
                                    <input type="text" name="alamat_kelurahan"
                                        value="{{ old('alamat_kelurahan', '') }}" placeholder="Kelurahan/Desa"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                                    @error('alamat_kelurahan')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode
                                        Pos</label>
                                    <input type="number" name="alamat_kode_pos"
                                        value="{{ old('alamat_kode_pos', '') }}" placeholder="Kode Pos"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                        inputmode="numeric" pattern="[0-9]*" />
                                    @error('alamat_kode_pos')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">RT</label>
                                    <input type="number" name="alamat_rt" value="{{ old('alamat_rt', '') }}"
                                        placeholder="RT"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                        inputmode="numeric" pattern="[0-9]*" />
                                    @error('alamat_rt')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">RW</label>
                                    <input type="number" name="alamat_rw" value="{{ old('alamat_rw', '') }}"
                                        placeholder="RW"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                        inputmode="numeric" pattern="[0-9]*" />
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
                            :disabled="isSubmitting || nikExists"
                            x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting || nikExists }">
                            <template x-if="isSubmitting">
                                <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>
                            </template>
                            <template x-if="!isSubmitting">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
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

@push('scripts')
    <script>
        function onJenisJabatanChange() {
            const jenisJabatan = document.querySelector('select[name="jenis_jabatan"]').value;
            const keteranganInput = document.querySelector('input[name="keterangan_jabatan"]');

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH }}';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH }}';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_GURU }}') {
                keteranganInput.placeholder = 'Mata pelajaran yang diampu, contoh: Matematika';
                keteranganInput.value = '';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_STAFF_TU }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_STAFF_TU }}';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA }}';
            }
        }
    </script>

    <script>
        function guruForm() {
            return {
                isSubmitting: false,
                // NIK validation
                nik: '{{ old('nik', '') }}',
                nikChecking: false,
                nikExists: false,
                nikError: false,
                nikErrorMessage: '',

                async checkNik() {
                    if (!this.nik) {
                        this.nikExists = false;
                        this.nikError = false;
                        this.nikErrorMessage = '';
                        return;
                    }

                    this.nikChecking = true;
                    this.nikError = false;
                    this.nikErrorMessage = '';

                    try {
                        const response = await fetch("{{ route('sekolah.check-nik-guru') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                nik: this.nik
                            })
                        });

                        const data = await response.json();

                        if (data.exists) {
                            this.nikExists = true;
                            this.nikError = true;
                            this.nikErrorMessage = data.message;
                        } else {
                            this.nikExists = false;
                            this.nikError = false;
                            this.nikErrorMessage = '';
                        }
                    } catch (error) {
                        console.error('Error checking NIK:', error);
                        this.nikError = true;
                        this.nikErrorMessage = 'Terjadi kesalahan saat memeriksa NIK';
                    } finally {
                        this.nikChecking = false;
                    }
                },

                init() {
                    // Check initial NIK if exists
                    if (this.nik) {
                        this.checkNik();
                    }
                }
            }
        }
    </script>
@endpush
