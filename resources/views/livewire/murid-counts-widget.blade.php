<x-ui.card>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Jumlah Murid per Provinsi dan Kabupaten</h2>
    </x-slot name="header">

    <div class="space-y-4">
        @forelse($provinsis as $provinsi)
            <div x-data="{ open: {{ $loop->index == 0 ? 'true' : 'false' }} }" class="border rounded-lg overflow-hidden">
                <!-- Header Provinsi (Clickable) -->
                <button @click="open = !open"
                    class="w-full bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 transition px-4 py-4 flex items-center justify-between border-l-4 border-blue-500">
                    <div class="flex items-center gap-3">
                        <!-- Icon Collapse/Expand -->
                        <svg x-show="open" class="w-5 h-5 text-blue-600 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                        <svg x-show="!open" class="w-5 h-5 text-blue-600 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700">{{ $provinsi['nama'] }}</h3>
                    </div>
                    <span class="bg-blue-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                        {{ number_format($provinsi['total_murid']) }} murid
                    </span>
                </button>

                <!-- Content Kabupaten (Collapsible) -->
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="bg-white p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @forelse($provinsi['kabupatens'] as $kabupaten)
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-md p-4 hover:shadow-md transition border border-gray-200">
                                <p class="text-sm font-medium text-gray-600">{{ $kabupaten['nama'] }}</p>
                                <p class="flex items-center gap-x-2 text-gray-800 mt-2">
                                    <span
                                        class="text-2xl font-bold text-blue-600">{{ number_format($kabupaten['murid_count']) }}</span>
                                    <span class="text-xs text-gray-500">murid</span>
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 col-span-full py-4 text-center">Tidak ada kabupaten</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Tidak ada data murid tersedia</p>
            </div>
        @endforelse
    </div>
</x-ui.card>
