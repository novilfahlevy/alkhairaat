<!-- Pagination -->
@if ($paginator->hasPages())
    <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
        <!-- Mobile Layout -->
        <div class="flex flex-col gap-3 sm:hidden">
            <!-- Page Info -->
            <div class="text-center text-sm font-medium text-gray-700 dark:text-gray-400">
                Halaman <span class="font-semibold">{{ $paginator->currentPage() }}</span> dari <span
                    class="font-semibold">{{ $paginator->lastPage() }}</span>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex gap-2">
                @if ($paginator->onFirstPage())
                    <button disabled
                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Sebelumnya
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Sebelumnya
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Selanjutnya
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <button disabled
                        class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                        Selanjutnya
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endif
            </div>

            <!-- Per-Page Selector -->
            <div class="flex items-center gap-2 sm:gap-3">
                <label for="per_page" class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-400">Per
                    Halaman:</label>
                <form method="GET" class="flex-1 sm:flex-none">
                    @foreach(request()->except('per_page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:w-auto">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                        </option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                        </option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                        </option>
                        <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500
                        </option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Desktop Layout -->
        <div class="hidden sm:flex items-center justify-between gap-4">
            <div class="flex items-center gap-x-4">
                <!-- Per-Page Selector -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <label for="per_page_desktop"
                        class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-400">Per
                        Halaman:</label>
                    <form method="GET" class="flex-1 sm:flex-none">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="per_page" id="per_page_desktop" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:w-auto">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                10</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                            </option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                            </option>
                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500
                            </option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                            </option>
                        </select>
                    </form>
                </div>

                <!-- Previous Button -->
                @if ($paginator->onFirstPage())
                    <button disabled
                        class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Sebelumnya</span>
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Sebelumnya</span>
                    </a>
                @endif
            </div>

            <!-- Page Info -->
            <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                Halaman <span class="font-semibold">{{ $paginator->currentPage() }}</span> dari <span
                    class="font-semibold">{{ $paginator->lastPage() }}</span>
            </span>

            <!-- Next Button -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                    class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <span>Selanjutnya</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <button disabled
                    class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                    <span>Selanjutnya</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
@endif