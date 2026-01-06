@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Informasi lengkap data alumni
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('alumni.edit', $alumni) }}"
                class="bg-blue-500 hover:bg-blue-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <a href="{{ route('alumni.index') }}"
                class="bg-gray-500 hover:bg-gray-600 flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Detail Card -->
        <div class="lg:col-span-2">
            <x-ui.card>
                <x-slot:header>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Data Alumni
                    </h2>
                </x-slot:header>

                <div class="space-y-6">
                    <!-- ID & Timestamp Section -->
                    <div class="grid grid-cols-2 gap-4 border-b border-gray-200 pb-6 dark:border-gray-700">
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">ID Alumni</label>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $alumni->id }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Terdaftar
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Identitas Section -->
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Identitas Murid
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Lengkap</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->nama ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">NISN</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->nisn ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">NIK</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->nik ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Jenis Kelamin</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->jenis_kelamin_label ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Tempat Lahir</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->tempat_lahir ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Lahir</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->tanggal_lahir?->format('d/m/Y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak Section -->
                    <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Kontak
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">WhatsApp / HP</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->kontak_wa_hp ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->murid?->kontak_email ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pekerjaan Section -->
                    <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Informasi Pekerjaan
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Profesi Sekarang</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->profesi_sekarang ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Tempat Kerja</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->nama_tempat_kerja ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Kota Tempat Kerja</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->kota_tempat_kerja ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Riwayat Pekerjaan</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $alumni->riwayat_pekerjaan ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Timestamp Section -->
                    <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Dibuat Pada</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Diupdate Pada</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $alumni->updated_at?->format('d/m/Y H:i') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Actions -->
            <x-ui.card class="mb-6">
                <x-slot:header>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aksi</h2>
                </x-slot:header>

                <div class="space-y-2">
                    <a href="{{ route('alumni.edit', $alumni) }}"
                        class="block rounded-lg bg-blue-50 px-4 py-2.5 text-center text-sm font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30">
                        Edit Data
                    </a>
                    <button onclick="deleteItem('{{ route('alumni.destroy', $alumni) }}')"
                        class="block w-full rounded-lg bg-red-50 px-4 py-2.5 text-center text-sm font-medium text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                        Hapus
                    </button>
                </div>
            </x-ui.card>

            <!-- Info Card -->
            <x-ui.card>
                <x-slot:header>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi</h2>
                </x-slot:header>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <span class="text-gray-600 dark:text-gray-400">Total Field</span>
                        <span class="font-semibold text-gray-900 dark:text-white">4 kolom</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <span class="text-gray-600 dark:text-gray-400">Murid</span>
                        <a href="{{ route('dashboard') }}"
                            class="font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
        <script>
            function deleteItem(url) {
                if (confirm('Apakah Anda yakin ingin menghapus data alumni ini?')) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        window.location.href = '{{ route("alumni.index") }}';
                    });
                }
            }
        </script>
    @endpush
@endsection
