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
                <span class="text-gray-900 dark:text-white">Detail</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Detail Guru
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi lengkap guru {{ $guru->nama }}
                    </p>
                </div>
                <div class="flex gap-2">
                    @if(auth()->user()->isSuperuser() || auth()->user()->isKomisariatWilayah() || (auth()->user()->isSekolah() && $guru->jabatanGurus->where('id_sekolah', auth()->user()->sekolah->id)->count() > 0))
                        <a href="{{ route('guru.edit', $guru) }}"
                           class="inline-flex items-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Guru
                        </a>
                        <form action="{{ route('guru.destroy', $guru) }}" method="POST" class="inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini? Semua data terkait akan dihapus secara permanen.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Guru
                            </button>
                        </form>
                    @endif
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

        <!-- Guru Information -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Informasi Pribadi</h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $guru->nama_gelar_depan ? $guru->nama_gelar_depan . ' ' : '' }}
                                {{ $guru->nama }}
                                {{ $guru->nama_gelar_belakang ? ', ' . $guru->nama_gelar_belakang : '' }}
                            </dd>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->jenis_kelamin_label }}</dd>
                        </div>

                        <!-- NIK -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NIK</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->nik ?? '-' }}</dd>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempat Lahir</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->tempat_lahir ?? '-' }}</dd>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d M Y') : '-' }}</dd>
                        </div>

                        <!-- Status Perkawinan -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Perkawinan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->status_perkawinan_label ?? '-' }}</dd>
                        </div>

                        <!-- Status -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                    {{ $guru->status === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $guru->status_label }}
                                </span>
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Informasi Kepegawaian</h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Status Kepegawaian -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Kepegawaian</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->status_kepegawaian_label ?? '-' }}</dd>
                        </div>

                        <!-- NPK -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NPK</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->npk ?? '-' }}</dd>
                        </div>

                        <!-- NUPTK -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NUPTK</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->nuptk ?? '-' }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Contact & Financial Information -->
                <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Kontak & Keuangan</h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Kontak WA/HP -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak WA/HP</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->kontak_wa_hp ?? '-' }}</dd>
                        </div>

                        <!-- Email -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->kontak_email ?? '-' }}</dd>
                        </div>

                        <!-- Bank Rekening -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank Rekening</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->bank_rekening ?? '-' }}</dd>
                        </div>

                        <!-- Nomor Rekening -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Rekening</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->nomor_rekening ?? '-' }}</dd>
                        </div>

                        <!-- Rekening Atas Nama -->
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rekening Atas Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $guru->rekening_atas_nama ?? '-' }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- School Assignment -->
                <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Penugasan Sekolah</h3>

                    @if($guru->jabatanGurus->count() > 0)
                        <div class="space-y-4">
                            @foreach($guru->jabatanGurus as $jabatan)
                                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $jabatan->sekolah->nama }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $jabatan->sekolah->kode_sekolah }}
                                            </p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                                <span class="font-medium">Jabatan:</span> {{ $jabatan->jenis_jabatan_label }}
                                            </p>
                                            @if($jabatan->keterangan_jabatan)
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                                    <span class="font-medium">Keterangan:</span> {{ $jabatan->keterangan_jabatan }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada penugasan sekolah</p>
                    @endif
                </div>

                <!-- Addresses -->
                @if($guru->alamatList->count() > 0)
                    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Alamat</h3>

                        <div class="space-y-4">
                            @foreach($guru->alamatList as $alamat)
                                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                        {{ ucfirst($alamat->jenis) }}
                                    </h4>
                                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                        <p>{{ $alamat->alamat_lengkap }}</p>
                                        <p>{{ $alamat->kelurahan }}, {{ $alamat->kecamatan }}</p>
                                        <p>{{ $alamat->kabupaten }}, {{ $alamat->provinsi }}</p>
                                        <p>Kode Pos: {{ $alamat->kode_pos }}</p>
                                        @if($alamat->rt && $alamat->rw)
                                            <p>RT/RW: {{ $alamat->rt }}/{{ $alamat->rw }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Informasi Sistem</h3>

                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $guru->created_at->format('d M Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $guru->updated_at->format('d M Y H:i') }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
