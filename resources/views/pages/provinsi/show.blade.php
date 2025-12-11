@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Detail informasi provinsi {{ $provinsi->nama_provinsi }}
            </p>
        </div>
        @can('manage_provinsi')
            <div class="flex items-center gap-2">
                <a href="{{ route('provinsi.edit', $provinsi) }}"
                    class="bg-blue-500 hover:bg-blue-600 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                    Edit
                </a>
                <a href="{{ route('provinsi.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 transition">
                    Kembali
                </a>
            </div>
        @endcan
    </div>

    <!-- Detail Card -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 max-w-2xl mb-6">
        <div class="space-y-6">
            <!-- Kode Provinsi -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Kode Provinsi
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    {{ $provinsi->kode_provinsi }}
                </p>
            </div>

            <!-- Nama Provinsi -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama Provinsi
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    {{ $provinsi->nama_provinsi }}
                </p>
            </div>

            <!-- Jumlah Kabupaten -->
            <div>
                <label class="mb-2.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jumlah Kabupaten
                </label>
                <p class="text-sm text-gray-800 dark:text-white/90">
                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $provinsi->kabupaten->count() }} kabupaten
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Kabupaten List -->
    @if ($provinsi->kabupaten->count() > 0)
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                Daftar Kabupaten
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Nama Kabupaten
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                        @foreach ($provinsi->kabupaten as $kabupaten)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $kabupaten->kode_kabupaten }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $kabupaten->nama_kabupaten }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
