<div class="space-y-4 sm:space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
        <!-- Total Komwil -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-indigo-200">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-indigo-600 uppercase tracking-wide truncate">Total Komwil
                    </p>
                    <p class="text-2xl sm:text-3xl font-bold text-indigo-800 mt-1">
                        {{ number_format($totalKomwil) }}</p>
                </div>
                <i class="fas fa-users-gear text-3xl sm:text-4xl text-indigo-300 flex-shrink-0"></i>
            </div>
        </div>

        <!-- Total Provinsi -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-blue-200">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-blue-600 uppercase tracking-wide truncate">Provinsi Terdaftar
                    </p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-800 mt-1">
                        {{ number_format($totalProvinsi) }}</p>
                </div>
                <i class="fas fa-map text-3xl sm:text-4xl text-blue-300 flex-shrink-0"></i>
            </div>
        </div>
    </div>

    <!-- Detail Komwil Per Provinsi -->
    {{-- <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Statistik Komwil per Provinsi</h2>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gray-50">
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold text-gray-700">No</th>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold text-gray-700">Provinsi</th>
                        <th class="px-3 sm:px-4 py-3 text-center font-semibold text-gray-700">Jumlah Komwil</th>
                        <th class="px-3 sm:px-4 py-3 text-center font-semibold text-gray-700">Jumlah Kabupaten</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($komwilData as $index => $data)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-3 sm:px-4 py-3 text-gray-600 font-medium">{{ $index + 1 }}</td>
                            <td class="px-3 sm:px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-pin text-indigo-600"></i>
                                    <span class="text-gray-800 font-medium">{{ $data['nama'] }}</span>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center bg-indigo-100 text-indigo-800 font-bold px-3 py-1 rounded-full text-sm">
                                    {{ number_format($data['komwil_count']) }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded-full text-sm">
                                    {{ number_format($data['kabupaten_count']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 sm:px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                <p>Tidak ada data Komwil tersedia</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card> --}}

    <!-- Distribusi Komwil Chart Info -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Distribusi Komwil</h2>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($komwilData as $data)
                @if($data['komwil_count'] > 0)
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-gray-700">{{ $data['nama'] }}</h3>
                            <span class="bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ number_format($data['komwil_count']) }} Komwil
                            </span>
                        </div>
                        {{-- <p class="text-xs text-gray-500 mt-2">
                            {{ number_format($data['kabupaten_count']) }} kabupaten
                        </p> --}}
                    </div>
                @else
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-red-700">{{ $data['nama'] }}</h3>
                            <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                Belum Ada Komwil
                            </span>
                        </div>
                        {{-- <p class="text-xs text-red-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ number_format($data['kabupaten_count']) }} kabupaten belum memiliki Komwil
                        </p> --}}
                    </div>
                @endif
            @empty
                <div class="col-span-2 text-center py-8">
                    <p class="text-gray-500">Tidak ada data untuk ditampilkan</p>
                </div>
            @endforelse
        </div>
    </x-ui.card>
</div>
