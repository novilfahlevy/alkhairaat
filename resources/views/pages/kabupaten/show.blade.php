@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Detail informasi kabupaten {{ $kabupaten->nama_kabupaten }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('kabupaten.edit', $kabupaten) }}"
                class="bg-blue-500 hover:bg-blue-600 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                Edit
            </a>
            <a href="{{ route('kabupaten.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 max-w-2xl mb-6">
        <div class="space-y-6">
            <!-- Kode Kabupaten -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Kode Kabupaten
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    {{ $kabupaten->kode_kabupaten }}
                </p>
            </div>

            <!-- Nama Kabupaten -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama Kabupaten
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    {{ $kabupaten->nama_kabupaten }}
                </p>
            </div>

            <!-- Provinsi -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Provinsi
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    {{ $kabupaten->provinsi->nama_provinsi ?? '-' }}
                </p>
            </div>

            <!-- Jumlah Sekolah -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jumlah Sekolah
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $kabupaten->sekolah->count() }} sekolah
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Sekolah List -->
    @if ($kabupaten->sekolah->count() > 0)
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                Daftar Sekolah
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Nama Sekolah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Jenjang
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                        @foreach ($kabupaten->sekolah as $sekolah)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $sekolah->kode_sekolah }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $sekolah->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $sekolah->jenjang }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($sekolah->status === 'aktif')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
