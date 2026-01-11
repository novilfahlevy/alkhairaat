<x-ui.card class="bg-white dark:bg-gray-900 shadow-lg dark:shadow-gray-800/50">
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Jumlah Murid per Provinsi dan Kabupaten</h2>
    </x-slot>

    <div class="space-y-4">
        @forelse($provinsis as $provinsi)
            <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
                class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm dark:shadow-gray-800/30">
                <!-- Header Provinsi (Clickable) -->
                <!-- TAMBAHKAN type="button" DI SINI -->
                <button type="button" @click="open = !open"
                    class="w-full bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800 dark:hover:to-blue-700 transition px-4 py-4 flex flex-col gap-y-3 md:flex-row md:items-center md:justify-between border-l-4 border-blue-500 dark:border-blue-400">

                    <div class="flex items-center gap-3 flex-1">
                        <!-- Icon Collapse/Expand -->
                        <svg x-show="open"
                            class="w-5 h-5 text-blue-600 dark:text-blue-300 transition-transform duration-200 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                        <svg x-show="!open"
                            class="w-5 h-5 text-blue-600 dark:text-blue-300 transition-transform duration-200 flex-shrink-0"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $provinsi['nama'] }}</h3>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                        <span
                            class="bg-blue-600 dark:bg-blue-500 text-white text-xs sm:text-sm font-bold px-2 sm:px-3 py-1 rounded-full shadow-sm dark:shadow-gray-900/50">
                            {{ number_format($provinsi['total_murid']) }} murid
                        </span>
                        <span
                            class="bg-purple-600 dark:bg-purple-500 text-white text-xs sm:text-sm font-bold px-2 sm:px-3 py-1 rounded-full shadow-sm dark:shadow-gray-900/50">
                            {{ number_format($provinsi['total_alumni']) }} alumni
                        </span>
                    </div>
                </button>

                <!-- Content Kabupaten (Collapsible) -->
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="bg-gray-50 dark:bg-gray-800 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @forelse($provinsi['kabupatens'] as $kabupaten)
                            @php
                                $persenKabAlumni =
                                    $kabupaten['murid_count'] > 0
                                        ? ($kabupaten['alumni_count'] / $kabupaten['murid_count']) * 100
                                        : 0;
                            @endphp
                            <div
                                class="bg-white dark:bg-gray-700 rounded-md p-4 hover:shadow-lg dark:hover:shadow-gray-900/50 transition-all duration-200 border border-gray-200 dark:border-gray-600">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-3">
                                    {{ $kabupaten['nama'] }}</p>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Murid:</span>
                                        <span
                                            class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($kabupaten['murid_count']) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Alumni:</span>
                                        <span
                                            class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ number_format($kabupaten['alumni_count']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p
                                class="text-sm text-gray-500 dark:text-gray-400 col-span-full py-4 text-center bg-gray-50 dark:bg-gray-700 rounded-md">
                                Tidak ada kabupaten</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 text-lg">Tidak ada data murid tersedia</p>
            </div>
        @endforelse
    </div>
</x-ui.card>
