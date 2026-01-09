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
                <span class="text-gray-900 dark:text-white">{{ $guru->nama }}</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $guru->full_name }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        NIK: <strong>{{ $guru->nik }}</strong>
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('sekolah.show-guru', $sekolah) }}"
                        class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('sekolah.edit-guru', ['sekolah' => $sekolah->id, 'guru' => $guru->id]) }}"
                        class="flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data
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

        <!-- Data Jabatan -->
        <x-ui.card>
            <x-slot name="header">
                <div class="flex items-center justify-between w-full">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Jabatan di
                        {{ $sekolah->nama }}</h2>
                    <button type="button" onclick="openAddJabatanModal()"
                        class="flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Jabatan
                    </button>
                </div>
            </x-slot>

            <x-ui.table>
                <x-slot name="thead">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Jenis Jabatan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Keterangan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Ditambahkan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Aksi</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($jabatanGuruList as $jabatanGuru)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <span
                                    class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-sm font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ $jabatanGuru->jenis_jabatan_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $jabatanGuru->keterangan_jabatan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $jabatanGuru->created_at->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <!-- Tombol hapus jabatan -->
                                <button type="button" {{ $jabatanGuruList->count() <= 1 ? 'disabled' : '' }}
                                    onclick="openDeleteJabatanModal('{{ $jabatanGuru->id }}', '{{ $jabatanGuru->jenis_jabatan_label }}')"
                                    class="flex items-center rounded-lg border border-red-300 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50 dark:border-red-700/50 dark:text-red-400 dark:hover:bg-red-900/20 disabled:cursor-not-allowed disabled:opacity-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="ml-2">Hapus</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data jabatan
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-ui.table>
        </x-ui.card>

        <!-- Data Guru Section -->
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Data Pribadi -->
            <div class="md:col-span-2">
                <x-ui.card>
                    <x-slot name="header">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Pribadi</h2>
                    </x-slot>

                    <div class="space-y-6">
                        <!-- Nama Lengkap -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nama Lengkap
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->full_name ?? '-' }}
                            </p>
                        </div>

                        <!-- NIK -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                NIK
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->nik ?? '-' }}
                            </p>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jenis Kelamin
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                <span
                                    class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $guru->jenis_kelamin_label ?? '-' }}
                                </span>
                            </p>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tempat Lahir
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->tempat_lahir ?? '-' }}
                            </p>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Lahir
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($guru->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($guru->status === 'aktif')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </p>
                        </div>

                        <!-- Status Perkawinan -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status Perkawinan
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->status_perkawinan_label ?? '-' }}
                            </p>
                        </div>

                        <!-- Status Kepegawaian -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status Kepegawaian
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->status_kepegawaian_label ?? '-' }}
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Data Kontak & Jabatan -->
            <div class="space-y-6">
                <!-- Data Kontak -->
                <x-ui.card>
                    <x-slot name="header">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Kontak</h2>
                    </x-slot>

                    <div class="space-y-6">
                        <!-- WhatsApp / HP -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                WhatsApp / HP
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($guru->kontak_wa_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru->kontak_wa_hp) }}"
                                        target="_blank"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                        {{ $guru->kontak_wa_hp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                @if ($guru->kontak_email)
                                    <a href="mailto:{{ $guru->kontak_email }}"
                                        class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                        {{ $guru->kontak_email }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- NPK -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                NPK
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->npk ?? '-' }}
                            </p>
                        </div>

                        <!-- NUPTK -->
                        <div class="border-b border-gray-200 pb-6 last:border-0 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                NUPTK
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                {{ $guru->nuptk ?? '-' }}
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>

        <!-- Data Rekening -->
        @if ($guru->nomor_rekening || $guru->rekening_atas_nama || $guru->bank_rekening)
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Rekening Bank</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Bank -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Bank
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $guru->bank_rekening ?? '-' }}
                        </p>
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor Rekening
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $guru->nomor_rekening ?? '-' }}
                        </p>
                    </div>

                    <!-- Atas Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Atas Nama
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $guru->rekening_atas_nama ?? '-' }}
                        </p>
                    </div>
                </div>
            </x-ui.card>
        @endif

        <!-- Detail Alamat Asli Section -->
        @if ($alamatAsli)
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Asli</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Provinsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->provinsi ?? '-' }}
                        </p>
                    </div>

                    <!-- Kabupaten -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->kabupaten ?? '-' }}
                        </p>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->kecamatan ?? '-' }}
                        </p>
                    </div>

                    <!-- Kelurahan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->kelurahan ?? '-' }}
                        </p>
                    </div>

                    <!-- RT/RW -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT/RW
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->rt ? 'RT ' . $alamatAsli->rt : '' }}{{ $alamatAsli->rt && $alamatAsli->rw ? '/' : '' }}{{ $alamatAsli->rw ? 'RW ' . $alamatAsli->rw : '-' }}
                        </p>
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->kode_pos ?? '-' }}
                        </p>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatAsli->alamat_lengkap ?? '-' }}
                        </p>
                    </div>

                    <!-- Koordinat -->
                    @if ($alamatAsli->koordinat_x || $alamatAsli->koordinat_y)
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Koordinat
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                Lat: {{ $alamatAsli->koordinat_x ?? '-' }} | Long: {{ $alamatAsli->koordinat_y ?? '-' }}
                            </p>
                        </div>
                    @endif
                </div>
            </x-ui.card>
        @endif

        <!-- Detail Alamat Domisili Section -->
        @if ($alamatDomisili)
            <x-ui.card>
                <x-slot name="header">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Alamat Domisili</h2>
                </x-slot>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Provinsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Provinsi
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->provinsi ?? '-' }}
                        </p>
                    </div>

                    <!-- Kabupaten -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kabupaten/Kota
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->kabupaten ?? '-' }}
                        </p>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kecamatan
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->kecamatan ?? '-' }}
                        </p>
                    </div>

                    <!-- Kelurahan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kelurahan
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->kelurahan ?? '-' }}
                        </p>
                    </div>

                    <!-- RT/RW -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            RT/RW
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->rt ? 'RT ' . $alamatDomisili->rt : '' }}
                            {{ $alamatDomisili->rt && $alamatDomisili->rw ? '/' : '' }}
                            {{ $alamatDomisili->rw ? 'RW ' . $alamatDomisili->rw : '-' }}
                        </p>
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Pos
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->kode_pos ?? '-' }}
                        </p>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <p class="mt-2 text-base text-gray-900 dark:text-white">
                            {{ $alamatDomisili->alamat_lengkap ?? '-' }}
                        </p>
                    </div>

                    <!-- Koordinat -->
                    @if ($alamatDomisili->koordinat_x || $alamatDomisili->koordinat_y)
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Koordinat
                            </label>
                            <p class="mt-2 text-base text-gray-900 dark:text-white">
                                Lat: {{ $alamatDomisili->koordinat_x ?? '-' }} | Long:
                                {{ $alamatDomisili->koordinat_y ?? '-' }}
                            </p>
                        </div>
                    @endif
                </div>
            </x-ui.card>
        @endif
    </div>
@endsection

@push('modals')
    <!-- Modal Tambah Jabatan -->
    <div id="addJabatanModal" x-data="{
        showAddModal: false,
        openModal() {
            this.showAddModal = true;
        },
        closeModal() {
            this.showAddModal = false;
            document.querySelector('#addJabatanModal form').reset();
        }
    }" x-cloak>
        <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false"
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">

            <!-- Backdrop -->
            <div @click="closeModal()" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            </div>

            <!-- Modal Content -->
            <div @click.stop class="relative w-full max-w-md rounded-3xl bg-white dark:bg-gray-900"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Close Button -->
                <button @click="closeModal()"
                    class="absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fillRule="evenodd" clipRule="evenodd"
                            d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                            fill="currentColor" />
                    </svg>
                </button>

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Jabatan</h3>
                </div>

                <!-- Form untuk tambah jabatan -->
                <form action="{{ route('sekolah.add-jabatan-guru', ['sekolah' => $sekolah->id, 'guru' => $guru->id]) }}"
                    method="POST" class="space-y-4 p-6">
                    @csrf

                    <!-- Jenis Jabatan -->
                    <div>
                        <label for="jenis_jabatan_modal"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_jabatan_modal" name="jenis_jabatan" required
                            onchange="onJenisJabatanChangeModal()"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Pilih Jenis Jabatan</option>
                            @foreach (\App\Models\JabatanGuru::JENIS_JABATAN_OPTIONS as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('jenis_jabatan')
                            <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Keterangan Jabatan -->
                    <div>
                        <label for="keterangan_jabatan_modal"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan Jabatan
                        </label>
                        <input type="text" id="keterangan_jabatan_modal" name="keterangan_jabatan"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                            placeholder="Mata pelajaran atau deskripsi lainnya" />
                        @error('keterangan_jabatan')
                            <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                Batal
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Jabatan -->
    <div id="deleteJabatanModal" x-data="{
        showDeleteModal: false,
        jabatanId: '',
        jabatanType: '',
        confirmationText: '',
        countdown: 10,
        countdownActive: false,
        startCountdown() {
            this.countdownActive = true;
            this.countdown = 10;
            const interval = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    this.countdownActive = false;
                    clearInterval(interval);
                }
            }, 1000);
        },
        deleteJabatan() {
            // Create a form dynamically and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('sekolah.delete-jabatan-guru', ['sekolah' => $sekolah, 'jabatanGuru' => ':id']) }}'.replace(':id', this.jabatanId);
    
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
    
            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
    
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    }" @keydown.escape.window="showDeleteModal = false" x-cloak>
        <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false"
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">

            <!-- Backdrop -->
            <div @click="showDeleteModal = false" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            </div>

            <!-- Modal Content -->
            <div @click.stop class="relative w-full max-w-lg rounded-3xl bg-white dark:bg-gray-900"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Close Button -->
                <button @click="showDeleteModal = false"
                    class="absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fillRule="evenodd" clipRule="evenodd"
                            d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                            fill="currentColor" />
                    </svg>
                </button>

                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="mb-6 flex items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Konfirmasi Penghapusan Jabatan
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Tindakan ini tidak dapat dibatalkan
                            </p>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="mb-6 rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                        <div class="mb-2 flex items-start">
                            <svg class="mr-2 mt-0.5 h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-red-800 dark:text-red-200">
                                <p class="font-semibold">Peringatan: Data jabatan akan dihapus!</p>
                                <p class="mt-1">Menghapus jabatan "<strong x-text="jabatanType"></strong>" akan
                                    menghapus data jabatan ini secara permanen.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ketik "<strong x-text="jabatanType"></strong>" untuk konfirmasi:
                        </label>
                        <input x-model="confirmationText" type="text"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            placeholder="Masukkan jenis jabatan">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button @click="showDeleteModal = false; confirmationText = ''"
                            class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button @click="deleteJabatan()" :disabled="confirmationText !== jabatanType || countdownActive"
                            :class="(confirmationText === jabatanType && !countdownActive) ?
                            'bg-red-600 hover:bg-red-700 text-white' :
                            'bg-gray-300 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500'"
                            class="flex-1 rounded-lg px-4 py-2 text-sm font-medium transition">
                            <span x-show="!countdownActive">Hapus Jabatan</span>
                            <span x-show="countdownActive">Tunggu <span x-text="countdown"></span> detik</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        function openAddJabatanModal() {
            const modal = document.getElementById('addJabatanModal');
            const modalData = Alpine.$data(modal);
            modalData.openModal();
        }

        function closeAddJabatanModal() {
            const modal = document.getElementById('addJabatanModal');
            const modalData = Alpine.$data(modal);
            modalData.closeModal();
        }

        function openDeleteJabatanModal(jabatanId, jabatanType) {
            const modal = document.getElementById('deleteJabatanModal');
            const modalData = Alpine.$data(modal);

            modalData.jabatanId = jabatanId;
            modalData.jabatanType = jabatanType;
            modalData.confirmationText = '';
            modalData.showDeleteModal = true;
            modalData.startCountdown();
        }

        function onJenisJabatanChangeModal() {
            const jenisJabatan = document.getElementById('jenis_jabatan_modal').value;
            const keteranganInput = document.getElementById('keterangan_jabatan_modal');

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH }}';
            } else if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH }}';
            } else if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_GURU }}') {
                keteranganInput.placeholder = 'Mata pelajaran yang diampu, contoh: Matematika';
                keteranganInput.value = '';
            } else if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_STAFF_TU }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_STAFF_TU }}';
            } else if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA }}';
            }
        }

        // Add CSS for x-cloak
        const style = document.createElement('style');
        style.innerHTML = '[x-cloak] { display: none !important; }';
        document.head.appendChild(style);
    </script>
@endpush
