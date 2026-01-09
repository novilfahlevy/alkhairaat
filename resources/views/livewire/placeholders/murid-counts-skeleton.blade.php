<x-ui.card>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Jumlah Murid per Provinsi dan Kabupaten</h2>
    </x-slot name="header">

    <div class="space-y-4">
        @for ($i = 0; $i < 3; $i++)
            <div class="border rounded-lg overflow-hidden">
                <!-- Header Skeleton -->
                <div class="w-full bg-gray-200 animate-pulse px-4 py-4 flex flex-col gap-y-3 md:flex-row md:items-center md:justify-between border-l-4 border-blue-500">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-5 h-5 bg-gray-300 rounded animate-pulse"></div>
                        <div class="h-6 bg-gray-300 rounded w-1/3 animate-pulse"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="h-8 bg-gray-300 rounded-full w-24 animate-pulse"></div>
                        <div class="h-8 bg-gray-300 rounded-full w-24 animate-pulse"></div>
                    </div>
                </div>

                <!-- Content Skeleton -->
                <div class="bg-white dark:bg-gray-700 p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @for ($j = 0; $j < 3; $j++)
                            <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-md p-4 animate-pulse">
                                <div class="h-4 bg-gray-300 rounded w-1/2 mb-3 animate-pulse"></div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <div class="h-4 bg-gray-300 rounded w-1/4 animate-pulse"></div>
                                        <div class="h-4 bg-gray-300 rounded w-1/4 animate-pulse"></div>
                                    </div>
                                    <div class="flex justify-between">
                                        <div class="h-4 bg-gray-300 rounded w-1/4 animate-pulse"></div>
                                        <div class="h-4 bg-gray-300 rounded w-1/4 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        @endfor
    </div>
</x-ui.card>
