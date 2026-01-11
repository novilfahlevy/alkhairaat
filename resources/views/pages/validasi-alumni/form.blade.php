@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
            <!-- Form -->
            <div class="flex w-full flex-1 flex-col lg:w-1/2 overflow-y-scroll py-12 px-6 sm:px-12 lg:px-16">
                <div>
                    <div class="mb-5 sm:mb-8">
                        <div class="mb-10 lg:hidden">
                            <img src="/images/logo_alkhairaat.png" alt="Logo Alkhairaat" width="150" height="150"
                                class="drop-shadow-lg mx-auto" />
                        </div>
                        <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                            Perbarui Data Alumni
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Silakan isi form berikut untuk memperbarui data alumni Anda.<br>
                            Data ini akan digunakan untuk database nasional alumni Alkhairaat.
                        </p>
                    </div>
                    <div>
                        {{-- Success/Error Messages --}}
                        @if (session('success'))
                            <div
                                class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div
                                class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('validasi-alumni.store') }}" method="POST" x-data="nikSearchForm()">
                            @csrf
                            <div class="space-y-5">
                                <!-- NIK -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        NIK <span class="text-error-500">*</span>
                                    </label>
                                    <input type="text" id="nik" name="nik" placeholder="Masukkan NIK Anda"
                                        x-model="nik" x-on:input="nik = nik.replace(/[^0-9]/g, '')"
                                        x-on:keyup.debounce.500ms="searchNik" autocomplete="off" inputmode="numeric"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nik') border-red-500 @enderror" />

                                    <template x-if="loading">
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Mencari NIK...
                                        </p>
                                    </template>

                                    <template x-if="nikFound === true">
                                        <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                            NIK ditemukan: <strong x-text="muridNama"></strong>
                                        </p>
                                    </template>

                                    <template x-if="nikFound === false && nik.length > 0 && !loading">
                                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">
                                            NIK tidak ditemukan di database.
                                        </p>
                                    </template>

                                    @error('nik')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror

                                    <input type="hidden" name="murid_id" :value="muridId">
                                </div>
                                <!-- Profesi Sekarang -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Profesi Sekarang
                                    </label>
                                    <input type="text" name="profesi_sekarang"
                                        placeholder="Contoh: Guru, Dokter, Wiraswasta, dst"
                                        value="{{ old('profesi_sekarang') }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('profesi_sekarang') border-red-500 @enderror" />
                                    @error('profesi_sekarang')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Nama Tempat Kerja -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Nama Tempat Kerja
                                    </label>
                                    <input type="text" name="nama_tempat_kerja"
                                        placeholder="Nama instansi/perusahaan/tempat usaha"
                                        value="{{ old('nama_tempat_kerja') }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nama_tempat_kerja') border-red-500 @enderror" />
                                    @error('nama_tempat_kerja')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Kota Tempat Kerja -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Kota Tempat Kerja
                                    </label>
                                    <input type="text" name="kota_tempat_kerja" placeholder="Kota lokasi kerja/sekarang"
                                        value="{{ old('kota_tempat_kerja') }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kota_tempat_kerja') border-red-500 @enderror" />
                                    @error('kota_tempat_kerja')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Riwayat Pekerjaan -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Riwayat Pekerjaan
                                    </label>
                                    <input type="text" name="riwayat_pekerjaan"
                                        placeholder="Pekerjaan sebelumnya (opsional)"
                                        value="{{ old('riwayat_pekerjaan') }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('riwayat_pekerjaan') border-red-500 @enderror" />
                                    @error('riwayat_pekerjaan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Kontak WA -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Kontak WhatsApp
                                    </label>
                                    <input type="tel" name="kontak_wa" placeholder="Nomor WhatsApp aktif"
                                        value="{{ old('kontak_wa') }}" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kontak_wa') border-red-500 @enderror" />
                                    @error('kontak_wa')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Kontak Email -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Kontak Email
                                    </label>
                                    <input type="email" name="kontak_email" placeholder="Email aktif"
                                        value="{{ old('kontak_email') }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('kontak_email') border-red-500 @enderror" />
                                    @error('kontak_email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Alamat Sekarang -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Alamat Sekarang
                                    </label>
                                    <input type="text" name="update_alamat_sekarang"
                                        placeholder="Alamat domisili sekarang" value="{{ old('update_alamat_sekarang') }}"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('update_alamat_sekarang') border-red-500 @enderror" />
                                    @error('update_alamat_sekarang')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Provinsi Domisili -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Provinsi Domisili Sekarang
                                    </label>
                                    <select name="id_provinsi" id="id_provinsi"
                                        x-on:change="loadKabupaten($event.target.value)"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('id_provinsi') border-red-500 @enderror">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach ($provinsiList as $provinsi)
                                            <option value="{{ $provinsi->id }}" {{ old('id_provinsi') == $provinsi->id ? 'selected' : '' }}>
                                                {{ $provinsi->nama_provinsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_provinsi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Kabupaten Domisili -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Kabupaten/Kota Domisili Sekarang
                                    </label>
                                    <select name="id_kabupaten" id="id_kabupaten"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('id_kabupaten') border-red-500 @enderror"
                                        :disabled="kabupatenList.length === 0">
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                        <template x-for="kab in kabupatenList" :key="kab.id">
                                            <option :value="kab.id" x-text="kab.nama_kabupaten" :selected="kab.id == oldKabupatenId"></option>
                                        </template>
                                    </select>
                                    @error('id_kabupaten')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Button -->
                                <div>
                                    <button type="submit" :disabled="nikFound !== true"
                                        x-bind:class="nikFound !== true ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-600'"
                                        class="bg-brand-500 shadow-theme-xs flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                        Simpan Data Alumni
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Footer Credit (Mobile) -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                        CSR by <a href="https://dumeg.id" target="_blank" rel="noopener noreferrer"
                            class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 transition-colors underline">
                            <img src="{{ asset('images/logo_dumeg.png') }}" class="w-28 mx-auto inline-block"
                                alt="Dumeg Logo">
                        </a>
                    </p>
                </div>
            </div>

            <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2 dark:bg-white/5">
                <div class="z-1 flex items-center justify-center">
                    <x-common.common-grid-shape />
                    <div class="flex max-w-xs flex-col items-center gap-6">
                        <img src="/images/logo_alkhairaat.png" alt="Logo Alkhairaat" width="200" height="200"
                            class="drop-shadow-lg" />
                        <p class="text-center text-gray-400 dark:text-white/60">
                            Sistem Database<br>
                            Perguruan Islam Alkhairaat
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function nikSearchForm() {
            return {
                nik: `{{ old('nik') }}`,
                nikFound: null,
                muridNama: '',
                muridId: '',
                loading: false,
                kabupatenList: [],
                oldKabupatenId: `{{ old('id_kabupaten') }}`,

                init() {
                    // Load kabupaten if provinsi was selected (old value)
                    const oldProvinsiId = `{{ old('id_provinsi') }}`;
                    if (oldProvinsiId) {
                        this.loadKabupaten(oldProvinsiId);
                    }
                },

                searchNik() {
                    // Reset jika NIK kosong atau terlalu pendek
                    if (!this.nik || this.nik.length < 8) {
                        this.nikFound = null;
                        this.muridNama = '';
                        this.muridId = '';
                        return;
                    }

                    this.loading = true;

                    fetch(`/api/cari-nik-murid?nik=${encodeURIComponent(this.nik)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.loading = false;

                            if (data.found) {
                                this.nikFound = true;
                                this.muridNama = data.nama;
                                this.muridId = data.id;
                            } else {
                                this.nikFound = false;
                                this.muridNama = '';
                                this.muridId = '';
                            }
                        })
                        .catch(error => {
                            console.error('Error searching NIK:', error);
                            this.loading = false;
                            this.nikFound = false;
                            this.muridNama = '';
                            this.muridId = '';
                        });
                },

                loadKabupaten(provinsiId) {
                    if (!provinsiId) {
                        this.kabupatenList = [];
                        return;
                    }

                    fetch(`/api/kabupaten-by-provinsi?id_provinsi=${encodeURIComponent(provinsiId)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.kabupatenList = data.kabupaten || [];
                        })
                        .catch(error => {
                            console.error('Error loading kabupaten:', error);
                            this.kabupatenList = [];
                        });
                }
            }
        }
    </script>
@endpush
