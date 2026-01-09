@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        @include('pages.sekolah.guru.tambah-guru-tabs', compact('sekolah'))

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

        <form action="{{ route('sekolah.store-guru', $sekolah) }}" method="POST"
            class="space-y-6" x-data="guruForm()" x-on:submit="isSubmitting = true">
            @csrf

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
                        <input type="text" id="nama" name="nama" value="{{ old('nama', '') }}" required
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
                        <input type="text" id="nik" name="nik" value="{{ old('nik', '') }}" required
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
                            @foreach (\App\Models\Guru::JENIS_KELAMIN_OPTIONS as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('jenis_kelamin') === $value ? 'selected' : '' }}>
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
                            @foreach (\App\Models\Guru::STATUS_OPTIONS as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status') === $value ? 'selected' : '' }}>
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
                        <label for="status_kepegawaian" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status Kepegawaian <span class="text-red-500">*</span>
                        </label>
                        <select id="status_kepegawaian" name="status_kepegawaian"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Status Kepegawaian</option>
                            @foreach (\App\Models\Guru::STATUS_KEPEGAWAIAN_OPTIONS as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status_kepegawaian') === $value ? 'selected' : '' }}>
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
                            @foreach (\App\Models\Guru::STATUS_PERKAWINAN_OPTIONS as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status_perkawinan') === $value ? 'selected' : '' }}>
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
                            value="{{ old('nama_gelar_depan', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Contoh: Dr., Ir." />
                        @error('nama_gelar_depan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gelar Belakang -->
                    <div>
                        <label for="nama_gelar_belakang"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Gelar Belakang
                        </label>
                        <input type="text" id="nama_gelar_belakang" name="nama_gelar_belakang"
                            value="{{ old('nama_gelar_belakang', '') }}"
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
                            value="{{ old('tempat_lahir', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Tempat lahir" />
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        @php $defaultDate = old('tanggal_lahir', null); @endphp
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
                        <input type="text" id="npk" name="npk" value="{{ old('npk', '') }}"
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
                        <input type="text" id="nuptk" name="nuptk" value="{{ old('nuptk', '') }}"
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
                            value="{{ old('kontak_wa_hp', '') }}"
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
                            value="{{ old('kontak_email', '') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Email guru" />
                        @error('kontak_email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-ui.card>

            <!-- Data Jabatan -->
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Jabatan di
                        {{ $sekolah->nama }}</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Jenis Jabatan -->
                    <div>
                        <label for="jenis_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_jabatan" name="jenis_jabatan" required onchange="onJenisJabatanChange()"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Jenis Jabatan</option>
                            @foreach ($jenisJabatanOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('jenis_jabatan') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_jabatan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan Jabatan -->
                    <div>
                        <label for="keterangan_jabatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan Jabatan
                        </label>
                        <input type="text" id="keterangan_jabatan" name="keterangan_jabatan"
                            value="{{ old('keterangan_jabatan') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Mata pelajaran atau deskripsi lainnya" />
                        @error('keterangan_jabatan')
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
                            value="{{ old('bank_rekening', '') }}"
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
                            value="{{ old('nomor_rekening', '') }}"
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
                            value="{{ old('rekening_atas_nama', '') }}"
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
                            value="{{ old('alamat_asli_provinsi') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_asli_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_asli_kabupaten" name="alamat_asli_kabupaten"
                            value="{{ old('alamat_asli_kabupaten') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_asli_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_asli_kecamatan" name="alamat_asli_kecamatan"
                            value="{{ old('alamat_asli_kecamatan') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_asli_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_asli_kelurahan" name="alamat_asli_kelurahan"
                            value="{{ old('alamat_asli_kelurahan') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_asli_rt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="number" id="alamat_asli_rt" name="alamat_asli_rt"
                            value="{{ old('alamat_asli_rt') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_asli_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="number" id="alamat_asli_rw" name="alamat_asli_rw"
                            value="{{ old('alamat_asli_rw') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_asli_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="number" id="alamat_asli_kode_pos" name="alamat_asli_kode_pos"
                            value="{{ old('alamat_asli_kode_pos') }}"
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
                            placeholder="Alamat lengkap">{{ old('alamat_asli_lengkap') }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_asli_koordinat_x" name="alamat_asli_koordinat_x"
                            value="{{ old('alamat_asli_koordinat_x') }}" step="any"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Latitude" />
                    </div>
                    <div>
                        <label for="alamat_asli_koordinat_y"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat Y (Longitude)
                        </label>
                        <input type="number" id="alamat_asli_koordinat_y" name="alamat_asli_koordinat_y"
                            value="{{ old('alamat_asli_koordinat_y') }}" step="any"
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
                            value="{{ old('alamat_domisili_provinsi') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Provinsi" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <input type="text" id="alamat_domisili_kabupaten" name="alamat_domisili_kabupaten"
                            value="{{ old('alamat_domisili_kabupaten') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kabupaten/Kota" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kecamatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <input type="text" id="alamat_domisili_kecamatan" name="alamat_domisili_kecamatan"
                            value="{{ old('alamat_domisili_kecamatan') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kecamatan" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kelurahan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <input type="text" id="alamat_domisili_kelurahan" name="alamat_domisili_kelurahan"
                            value="{{ old('alamat_domisili_kelurahan') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Kelurahan" />
                    </div>
                    <div>
                        <label for="alamat_domisili_rt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT
                        </label>
                        <input type="number" id="alamat_domisili_rt" name="alamat_domisili_rt"
                            value="{{ old('alamat_domisili_rt') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RT" />
                    </div>
                    <div>
                        <label for="alamat_domisili_rw"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RW
                        </label>
                        <input type="number" id="alamat_domisili_rw" name="alamat_domisili_rw"
                            value="{{ old('alamat_domisili_rw') }}"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="RW" />
                    </div>
                    <div>
                        <label for="alamat_domisili_kode_pos"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <input type="number" id="alamat_domisili_kode_pos" name="alamat_domisili_kode_pos"
                            value="{{ old('alamat_domisili_kode_pos') }}"
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
                            placeholder="Alamat lengkap">{{ old('alamat_domisili_lengkap') }}</textarea>
                    </div>
                    <div>
                        <label for="alamat_domisili_koordinat_x"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Koordinat X (Latitude)
                        </label>
                        <input type="number" id="alamat_domisili_koordinat_x" name="alamat_domisili_koordinat_x"
                            value="{{ old('alamat_domisili_koordinat_x') }}"
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
                            value="{{ old('alamat_domisili_koordinat_y') }}"
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
                    <a href="{{ route('sekolah.show-guru', $sekolah) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center justify-center rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white hover:bg-brand-600 transition"
                        :disabled="isSubmitting || nikExists"
                        x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting || nikExists }">
                        <template x-if="isSubmitting">
                            <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
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
                </div>
            </div>

            <!-- Spacer untuk fixed buttons -->
            <div class="h-24"></div>
        </form>
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
@endpush
