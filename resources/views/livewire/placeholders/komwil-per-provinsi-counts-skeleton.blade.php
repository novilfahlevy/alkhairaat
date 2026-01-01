<div class="space-y-4 sm:space-y-6">
    <!-- Distribusi Komwil Skeleton -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Distribusi Komwil</h2>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @for ($i = 0; $i < 4; $i++)
                <div class="bg-gray-200 rounded-lg p-4 border border-gray-300 animate-pulse">
                    <div class="flex items-center justify-between">
                        <div class="h-5 bg-gray-300 rounded w-1/3 animate-pulse"></div>
                        <div class="h-6 bg-gray-300 rounded w-1/4 animate-pulse"></div>
                    </div>
                </div>
            @endfor
        </div>
    </x-ui.card>
</div>
