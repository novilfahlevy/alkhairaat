<x-ui.card class="bg-white dark:bg-gray-900 shadow-lg dark:shadow-gray-800/50">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="h-7 bg-gray-200 dark:bg-gray-700 rounded w-64 animate-pulse"></div>
            <div class="flex items-center gap-2">
                <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-32 animate-pulse"></div>
                <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-24 animate-pulse"></div>
            </div>
        </div>
    </x-slot>

    <!-- Summary Cards Skeleton -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @for ($i = 0; $i < 3; $i++)
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 animate-pulse">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-600 rounded w-16"></div>
                    </div>
                    <div class="h-12 w-12 bg-gray-200 dark:bg-gray-600 rounded-full"></div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Province List Skeleton -->
    <div class="space-y-3">
        @for ($i = 0; $i < 5; $i++)
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 animate-pulse">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded w-1/3"></div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded w-full"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-12"></div>
                        <div class="h-6 bg-gray-200 dark:bg-gray-600 rounded-full w-24"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</x-ui.card>
