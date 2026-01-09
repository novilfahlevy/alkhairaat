<div class="space-y-4 sm:space-y-6 mb-6">
    <!-- Statistik Status Sekolah Skeleton -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Statistik Sekolah</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            @for ($i = 0; $i < 3; $i++)
                <div class="bg-gray-200 rounded-lg p-3 sm:p-4 lg:p-6 border border-gray-300 animate-pulse">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex-1">
                            <div class="h-4 bg-gray-300 rounded w-1/2 mb-2 animate-pulse"></div>
                            <div class="h-8 bg-gray-300 rounded w-2/3 animate-pulse"></div>
                        </div>
                        <div class="w-12 h-12 bg-gray-300 rounded-lg animate-pulse"></div>
                    </div>
                </div>
            @endfor
        </div>
    </x-ui.card>

    <!-- Statistik Jenis Sekolah Skeleton -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Statistik Jenis Sekolah</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-3 lg:gap-4">
            @for ($i = 0; $i < 5; $i++)
                <div class="bg-gray-200 rounded-lg p-3 sm:p-4 lg:p-6 border border-gray-300 animate-pulse">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex-1">
                            <div class="h-4 bg-gray-300 rounded w-1/2 mb-2 animate-pulse"></div>
                            <div class="h-8 bg-gray-300 rounded w-2/3 animate-pulse"></div>
                        </div>
                        <div class="w-12 h-12 bg-gray-300 rounded-lg animate-pulse"></div>
                    </div>
                </div>
            @endfor
        </div>
    </x-ui.card>

    <!-- Statistik Bentuk Pendidikan Skeleton -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Statistik Bentuk Pendidikan</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            @for ($i = 0; $i < 2; $i++)
                <div class="bg-gray-200 rounded-lg p-3 sm:p-4 lg:p-6 border border-gray-300 animate-pulse">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex-1">
                            <div class="h-4 bg-gray-300 rounded w-1/2 mb-2 animate-pulse"></div>
                            <div class="h-8 bg-gray-300 rounded w-2/3 animate-pulse"></div>
                        </div>
                        <div class="w-12 h-12 bg-gray-300 rounded-lg animate-pulse"></div>
                    </div>
                </div>
            @endfor
        </div>
    </x-ui.card>
</div>
