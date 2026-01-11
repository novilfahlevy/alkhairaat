<x-ui.card class="bg-white dark:bg-gray-900 shadow-lg dark:shadow-gray-800/50">
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Persebaran Alumni per Provinsi</h2>
            <p class="text-sm text-gray-700">Data akan muncul setelah alumni melakukan validasi data mereka.</p>
        </div>
    </x-slot>

    @if($provinsis->isEmpty())
        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="text-lg font-medium">Belum ada data persebaran alumni</p>
            <p class="text-sm mt-1">Data akan muncul setelah alumni melakukan validasi data mereka</p>
        </div>
    @else
        <!-- Province List -->
        <div class="space-y-3">
            @foreach($provinsis as $index => $provinsi)
                @php
                    $percentage = $summary['total_alumni'] > 0 
                        ? ($provinsi['total_alumni'] / $summary['total_alumni']) * 100 
                        : 0;
                    $isUnknown = is_null($provinsi['id']);
                @endphp
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <!-- Rank Badge -->
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full 
                                @if($index === 0) bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300
                                @elseif($index === 1) bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                @elseif($index === 2) bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                @endif font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200 truncate {{ $isUnknown ? 'italic' : '' }}">
                                    {{ $provinsi['nama'] }}
                                </h3>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="bg-purple-600 dark:bg-purple-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-sm">
                                {{ number_format($provinsi['total_alumni']) }} alumni
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-ui.card>
