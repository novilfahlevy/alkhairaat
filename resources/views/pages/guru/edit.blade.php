@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Breadcrumb -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('guru.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Data Guru</a>
                <span>/</span>
                <a href="{{ route('guru.show', $guru) }}" class="hover:text-brand-600 dark:hover:text-brand-400">Detail</a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">Edit</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Edit Guru
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Perbarui informasi guru {{ $guru->nama }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('guru.show', $guru) }}"
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Detail
                    </a>
                    <a href="{{ route('guru.index') }}"
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.update', $guru) }}" method="POST" class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Pribadi</h3>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $guru->nama) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gelar Depan -->
                    <div>
                        <label for="nama_gelar_depan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gelar Depan
                        </label>
                        <input type="text" id="nama_gelar_depan" name="nama_gelar_depan" value="{{ old('nama_gelar_depan', $guru->nama_gelar_depan) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_gelar_depan') border-red-500 @enderror"
                               placeholder="Contoh: Dr., Prof.">
                        @error('nama_gelar_depan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gelar Belakang -->
                    <div>
                        <label for="nama_gelar_belakang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gelar Belakang
                        </label>
                        <input type="text" id="nama_gelar_belakang" name="nama_gelar_belakang" value="{{ old('nama_gelar_belakang', $guru->nama_gelar_belakang) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nama_gelar_belakang') border-red-500 @enderror"
                               placeholder="Contoh: S.Pd., M.Pd.">
                        @error('nama_gelar_belakang')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                       class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 @error('jenis_kelamin') border-red-500 @enderror">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                       class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 @error('jenis_kelamin') border-red-500 @enderror">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                            </label>
                        </div>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik', $guru->nik) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nik') border-red-500 @enderror"
                               placeholder="Masukkan 16 digit NIK">
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tempat Lahir
                        </label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('tempat_lahir') border-red-500 @enderror"
                               placeholder="Masukkan tempat lahir">
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $guru->tanggal_lahir ? $guru->tanggal_lahir->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Status Perkawinan -->
                    <div>
                        <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Perkawinan
                        </label>
                        <select id="status_perkawinan" name="status_perkawinan"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('status_perkawinan') border-red-500 @enderror">
                            <option value="">Pilih Status Perkawinan</option>
                            <option value="lajang" {{ old('status_perkawinan', $guru->status_perkawinan) == 'lajang' ? 'selected' : '' }}>Lajang</option>
                            <option value="menikah" {{ old('status_perkawinan', $guru->status_perkawinan) == 'menikah' ? 'selected' : '' }}>Menikah</option>
                        </select>
                        @error('status_perkawinan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Kepegawaian</h3>

                    <!-- Status Kepegawaian -->
                    <div>
                        <label for="status_kepegawaian" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Kepegawaian
                        </label>
                        <select id="status_kepegawaian" name="status_kepegawaian"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('status_kepegawaian') border-red-500 @enderror">
                            <option value="">Pilih Status Kepegawaian</option>
                            <option value="PNS" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="Non PNS" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'Non PNS' ? 'selected' : '' }}>Non PNS</option>
                            <option value="PPPK" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                        </select>
                        @error('status_kepegawaian')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NPK -->
                    <div>
                        <label for="npk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NPK
                        </label>
                        <input type="text" id="npk" name="npk" value="{{ old('npk', $guru->npk) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('npk') border-red-500 @enderror"
                               placeholder="Masukkan NPK">
                        @error('npk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NUPTK -->
                    <div>
                        <label for="nuptk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NUPTK
                        </label>
                        <input type="text" id="nuptk" name="nuptk" value="{{ old('nuptk', $guru->nuptk) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nuptk') border-red-500 @enderror"
                               placeholder="Masukkan NUPTK">
                        @error('nuptk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Kontak & Keuangan</h3>

                    <!-- Kontak WA/HP -->
                    <div>
                        <label for="kontak_wa_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kontak WA/HP
                        </label>
                        <input type="text" id="kontak_wa_hp" name="kontak_wa_hp" value="{{ old('kontak_wa_hp', $guru->kontak_wa_hp) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('kontak_wa_hp') border-red-500 @enderror"
                               placeholder="Masukkan nomor WA/HP">
                        @error('kontak_wa_hp')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak Email -->
                    <div>
                        <label for="kontak_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email
                        </label>
                        <input type="email" id="kontak_email" name="kontak_email" value="{{ old('kontak_email', $guru->kontak_email) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('kontak_email') border-red-500 @enderror"
                               placeholder="Masukkan alamat email">
                        @error('kontak_email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bank Rekening -->
                    <div>
                        <label for="bank_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bank Rekening
                        </label>
                        <input type="text" id="bank_rekening" name="bank_rekening" value="{{ old('bank_rekening', $guru->bank_rekening) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('bank_rekening') border-red-500 @enderror"
                               placeholder="Masukkan nama bank">
                        @error('bank_rekening')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nomor Rekening
                        </label>
                        <input type="text" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening', $guru->nomor_rekening) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('nomor_rekening') border-red-500 @enderror"
                               placeholder="Masukkan nomor rekening">
                        @error('nomor_rekening')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rekening Atas Nama -->
                    <div>
                        <label for="rekening_atas_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rekening Atas Nama
                        </label>
                        <input type="text" id="rekening_atas_nama" name="rekening_atas_nama" value="{{ old('rekening_atas_nama', $guru->rekening_atas_nama) }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white @error('rekening_atas_nama') border-red-500 @enderror"
                               placeholder="Masukkan nama pemilik rekening">
                        @error('rekening_atas_nama')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Jabatan Section -->
            <div class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700" x-data="schoolAssignments({!! json_encode($guru->jabatanGurus->map(function($jabatan) {
                return [
                    'id_sekolah' => $jabatan->id_sekolah,
                    'jenis_jabatan' => $jabatan->jenis_jabatan,
                    'keterangan_jabatan' => $jabatan->keterangan_jabatan
                ];
            })->toArray()) !!})">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Penugasan Sekolah</h3>

                <div id="school-assignments" class="space-y-6">
                    <!-- School Assignment Template -->
                    <template x-for="(assignment, index) in assignments" :key="index">
                        <div class="relative rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <!-- Remove button (only for additional assignments) -->
                            <button type="button" @click="removeAssignment(index)"
                                    x-show="assignments.length > 1"
                                    class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <!-- Sekolah -->
                                <div>
                                    <label :for="'id_sekolah_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <select :id="'id_sekolah_' + index" :name="'id_sekolah[' + index + ']'"
                                            x-model="assignment.id_sekolah"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                        <option value="">Pilih Sekolah</option>
                                        @foreach($sekolah as $item)
                                            <option value="{{ $item->id }}" :selected="assignment.id_sekolah == '{{ $item->id }}'">
                                                {{ $item->nama }} ({{ $item->kode_sekolah }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Jenis Jabatan -->
                                <div>
                                    <label :for="'jenis_jabatan_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Jabatan <span class="text-red-500">*</span>
                                    </label>
                                    <select :id="'jenis_jabatan_' + index" :name="'jenis_jabatan[' + index + ']'"
                                            x-model="assignment.jenis_jabatan"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                        <option value="">Pilih Jabatan</option>
                                        @foreach(\App\Models\JabatanGuru::JENIS_JABATAN_OPTIONS as $key => $value)
                                            <option value="{{ $key }}" :selected="assignment.jenis_jabatan == '{{ $key }}'">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Keterangan Jabatan -->
                                <div class="lg:col-span-2">
                                    <label :for="'keterangan_jabatan_' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Keterangan Jabatan
                                    </label>
                                    <textarea :id="'keterangan_jabatan_' + index" :name="'keterangan_jabatan[' + index + ']'" rows="3"
                                              x-model="assignment.keterangan_jabatan"
                                              class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                              placeholder="Masukkan keterangan jabatan (misalnya: Mata pelajaran yang diampu)"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Tambah Sekolah Button -->
                <div class="mt-6">
                    <button type="button" @click="addAssignment()"
                            class="inline-flex items-center rounded-lg border border-brand-500 bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Sekolah
                    </button>
                </div>
            </div>

            <script>
                function schoolAssignments(existingAssignments = []) {
                    return {
                        assignments: existingAssignments.length > 0 ? existingAssignments : [
                            {
                                id_sekolah: '',
                                jenis_jabatan: '',
                                keterangan_jabatan: ''
                            }
                        ],
                        addAssignment() {
                            this.assignments.push({
                                id_sekolah: '',
                                jenis_jabatan: '',
                                keterangan_jabatan: ''
                            });
                        },
                        removeAssignment(index) {
                            if (this.assignments.length > 1) {
                                this.assignments.splice(index, 1);
                            }
                        }
                    }
                }
            </script>

            <!-- Form Actions -->
            <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:justify-end">
                <a href="{{ route('guru.show', $guru) }}"
                   class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                    Perbarui Guru
                </button>
            </div>
        </form>
    </div>
@endsection
