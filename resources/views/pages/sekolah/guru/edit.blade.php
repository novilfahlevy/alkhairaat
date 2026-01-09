@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Breadcrumb -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('sekolah.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    Sekolah
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show', $sekolah) }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    {{ $sekolah->nama }}
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show-guru', $sekolah) }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    Data Guru
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show-detail-guru', ['sekolah' => $sekolah, 'guru' => $guru]) }}"
                    class="hover:text-brand-600 dark:hover:text-brand-400">
                    {{ $guru->nama }}
                </a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">Edit Data</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Edit Data Guru
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Edit data pribadi, jabatan, dan alamat {{ $guru->nama }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('sekolah.show-detail-guru', ['sekolah' => $sekolah, 'guru' => $guru]) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

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

        <form action="{{ route('sekolah.update-guru', ['sekolah' => $sekolah->id, 'guru' => $guru->id]) }}" method="POST"
            class="space-y-6" x-data="{ isSubmitting: false }" x-on:submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <!-- Data Pribadi -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Pribadi</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                  <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $guru->nama) }}" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nama lengkap guru" />
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nik" name="nik" value="{{ old('nik', $guru->nik) }}" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor Identitas Kartu" />
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            @foreach ($jenisKelaminOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('jenis_kelamin', $guru->jenis_kelamin) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Status</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status', $guru->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Kepegawaian -->
                    <div>
                        <label for="status_kepegawaian"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status Kepegawaian <span class="text-red-500">*</span>
                        </label>
                        <select id="status_kepegawaian" name="status_kepegawaian"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Status Kepegawaian</option>
                            @foreach ($statusKepegawaianOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status_kepegawaian', $guru->status_kepegawaian) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_kepegawaian')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Perkawinan -->
                    <div>
                        <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status Perkawinan
                        </label>
                        <select id="status_perkawinan" name="status_perkawinan"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Status Perkawinan</option>
                            @foreach ($statusPerkawinanOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status_perkawinan', $guru->status_perkawinan) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_perkawinan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gelar Depan -->
                    <div>
                        <label for="nama_gelar_depan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Gelar Depan
                        </label>
                        <input type="text" id="nama_gelar_depan" name="nama_gelar_depan"
                            value="{{ old('nama_gelar_depan', $guru->nama_gelar_depan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Contoh: Dr., Ir." />
                        @error('nama_gelar_depan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gelar Belakang -->
                    <div>
                        <label for="nama_gelar_belakang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Gelar Belakang
                        </label>
                        <input type="text" id="nama_gelar_belakang" name="nama_gelar_belakang"
                            value="{{ old('nama_gelar_belakang', $guru->nama_gelar_belakang) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Contoh: S.Pd., M.Pd." />
                        @error('nama_gelar_belakang')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tempat Lahir
                        </label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tempat lahir" />
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        @php $defaultDate = old('tanggal_lahir', $guru->tanggal_lahir?->format('Y-m-d')); @endphp
                        <x-form.date-picker id="tanggal_lahir" name="tanggal_lahir" label="Tanggal Lahir"
                            placeholder="Pilih tanggal lahir" :defaultDate="$defaultDate" />
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NPK -->
                    <div>
                        <label for="npk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NPK
                        </label>
                        <input type="text" id="npk" name="npk" value="{{ old('npk', $guru->npk) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor Pokok Guru" />
                        @error('npk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NUPTK -->
                    <div>
                        <label for="nuptk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NUPTK
                        </label>
                        <input type="text" id="nuptk" name="nuptk" value="{{ old('nuptk', $guru->nuptk) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan" />
                        @error('nuptk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp / HP -->
                    <div>
                        <label for="kontak_wa_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            WhatsApp / HP
                        </label>
                        <input type="tel" id="kontak_wa_hp" name="kontak_wa_hp"
                            value="{{ old('kontak_wa_hp', $guru->kontak_wa_hp) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor WhatsApp/HP" />
                        @error('kontak_wa_hp')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="kontak_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" id="kontak_email" name="kontak_email"
                            value="{{ old('kontak_email', $guru->kontak_email) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Email guru" />
                        @error('kontak_email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Data Rekening Bank -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Rekening Bank</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Bank -->
                    <div>
                        <label for="bank_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Bank
                        </label>
                        <input type="text" id="bank_rekening" name="bank_rekening"
                            value="{{ old('bank_rekening', $guru->bank_rekening) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nama bank" />
                        @error('bank_rekening')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor Rekening
                        </label>
                        <input type="number" id="nomor_rekening" name="nomor_rekening"
                            value="{{ old('nomor_rekening', $guru->nomor_rekening) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nomor rekening" />
                        @error('nomor_rekening')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rekening Atas Nama -->
                    <div class="md:col-span-2">
                        <label for="rekening_atas_nama"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Rekening Atas Nama
                        </label>
                        <input type="text" id="rekening_atas_nama" name="rekening_atas_nama"
                            value="{{ old('rekening_atas_nama', $guru->rekening_atas_nama) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Nama pemilik rekening" />
                        @error('rekening_atas_nama')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Alamat Asli -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Asli</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="alamat_asli_provinsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <input type="text" id="alamat_asli_provinsi" name="alamat_asli_provinsi"
                            value="{{ old('alamat_asli_provinsi', $alamatAsli?->provinsi) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_asli_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_asli_kabupaten" name="alamat_asli_kabupaten"
                            value="{{ old('alamat_asli_kabupaten', $alamatAsli?->kabupaten) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_asli_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_asli_kecamatan" name="alamat_asli_kecamatan"
                            value="{{ old('alamat_asli_kecamatan', $alamatAsli?->kecamatan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_asli_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_asli_kelurahan" name="alamat_asli_kelurahan"
                            value="{{ old('alamat_asli_kelurahan', $alamatAsli?->kelurahan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_asli_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="number" id="alamat_asli_rt" name="alamat_asli_rt"
                            value="{{ old('alamat_asli_rt', $alamatAsli?->rt) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_asli_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="number" id="alamat_asli_rw" name="alamat_asli_rw"
                            value="{{ old('alamat_asli_rw', $alamatAsli?->rw) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_asli_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="number" id="alamat_asli_kode_pos" name="alamat_asli_kode_pos"
                            value="{{ old('alamat_asli_kode_pos', $alamatAsli?->kode_pos) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_asli_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_asli_lengkap" name="alamat_asli_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_asli_lengkap', $alamatAsli?->alamat_lengkap) }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_asli_koordinat_x" name="alamat_asli_koordinat_x"
                            value="{{ old('alamat_asli_koordinat_x', $alamatAsli?->koordinat_x) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_asli_koordinat_y" name="alamat_asli_koordinat_y"
                            value="{{ old('alamat_asli_koordinat_y', $alamatAsli?->koordinat_y) }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Alamat Domisili -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Domisili</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="alamat_domisili_provinsi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <input type="text" id="alamat_domisili_provinsi" name="alamat_domisili_provinsi"
                            value="{{ old('alamat_domisili_provinsi', $alamatDomisili?->provinsi) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_domisili_kabupaten" name="alamat_domisili_kabupaten"
                            value="{{ old('alamat_domisili_kabupaten', $alamatDomisili?->kabupaten) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_domisili_kecamatan" name="alamat_domisili_kecamatan"
                            value="{{ old('alamat_domisili_kecamatan', $alamatDomisili?->kecamatan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_domisili_kelurahan" name="alamat_domisili_kelurahan"
                            value="{{ old('alamat_domisili_kelurahan', $alamatDomisili?->kelurahan) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_domisili_rt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="number" id="alamat_domisili_rt" name="alamat_domisili_rt"
                            value="{{ old('alamat_domisili_rt', $alamatDomisili?->rt) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_domisili_rw"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="number" id="alamat_domisili_rw" name="alamat_domisili_rw"
                            value="{{ old('alamat_domisili_rw', $alamatDomisili?->rw) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="number" id="alamat_domisili_kode_pos" name="alamat_domisili_kode_pos"
                            value="{{ old('alamat_domisili_kode_pos', $alamatDomisili?->kode_pos) }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kode Pos" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="alamat_domisili_lengkap"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat_domisili_lengkap" name="alamat_domisili_lengkap" rows="2"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Alamat lengkap">{{ old('alamat_domisili_lengkap', $alamatDomisili?->alamat_lengkap) }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_domisili_koordinat_x" name="alamat_domisili_koordinat_x"
                            value="{{ old('alamat_domisili_koordinat_x', $alamatDomisili?->koordinat_x) }}"
                            step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_domisili_koordinat_y" name="alamat_domisili_koordinat_y"
                            value="{{ old('alamat_domisili_koordinat_y', $alamatDomisili?->koordinat_y) }}"
                            step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Longitude" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Action Buttons - Sticky -->
            <div
                class="fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900 mb-0">
                <div class="w-full flex justify-end gap-3">
                    <a href="{{ route('sekolah.show-detail-guru', ['sekolah' => $sekolah->id, 'guru' => $guru->id]) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600"
                        :disalbed="isSubmitting">
                        <template x-if="isSubmitting">
                            <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!isSubmitting">
                            <i class="fas fa-check mr-3"></i>
                        </template>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- Spacer untuk fixed buttons -->
            <div class="h-24"></div>
        </form>
    </div>
@endsection