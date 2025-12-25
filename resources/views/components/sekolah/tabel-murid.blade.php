@props(['murid', 'sekolah'])

<!-- Murid Table Section -->
<div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Murid</h2>
    </div>

    <!-- Search and Per-Page Controls -->
    <div class="mb-6 space-y-3 sm:space-y-4">
        <!-- Search Form -->
        <form method="GET" class="flex flex-col gap-2 sm:flex-row">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama, NISN, atau NIK murid..."
                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
            <button type="submit"
                class="bg-brand-500 hover:bg-brand-600 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition sm:px-6">
                Cari
            </button>
            @if (request('search'))
                <a href="{{ route('sekolah.show', $sekolah) }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Desktop Table View -->
    @if ($murid->count() > 0)
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nama
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            NISN
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            NIK
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Jenis Kelamin
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Tahun Masuk
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kelas
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status Kelulusan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @foreach ($murid as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->nama }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->nisn }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->nik ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->jenis_kelamin_label }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->pivot->tahun_masuk }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->pivot->kelas ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($item->pivot->status_kelulusan === 'ya')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Lulus
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        Belum Lulus
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block space-y-4 md:hidden">
            @foreach ($murid as $item)
                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-3 flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->nama }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">NISN: {{ $item->nisn }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">NIK:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->nik ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jenis Kelamin:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->jenis_kelamin_label }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tahun Masuk:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->pivot->tahun_masuk }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kelas:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->pivot->kelas ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span>
                                @if ($item->pivot->status_kelulusan === 'ya')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Lulus
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        Belum Lulus
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($murid->hasPages())
            <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                <!-- Mobile Layout -->
                <div class="flex flex-col gap-3 sm:hidden">
                    <!-- Page Info -->
                    <div class="text-center text-sm font-medium text-gray-700 dark:text-gray-400">
                        Halaman <span class="font-semibold">{{ $murid->currentPage() }}</span> dari <span
                            class="font-semibold">{{ $murid->lastPage() }}</span>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex gap-2">
                        @if ($murid->onFirstPage())
                            <button disabled
                                class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Prev
                            </button>
                        @else
                            <a href="{{ $murid->previousPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Prev
                            </a>
                        @endif

                        @if ($murid->hasMorePages())
                            <a href="{{ $murid->nextPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                Next
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <button disabled
                                class="flex-1 flex items-center justify-center gap-1 rounded-lg border border-gray-300 px-3 py-2.5 text-xs font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                Next
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Per-Page Selector -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <label for="per_page"
                            class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-400">Per
                            Halaman:</label>
                        <form method="GET" class="flex-1 sm:flex-none">
                            <input type="hidden" name="search" value="{{ request('search') }}">
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
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
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
                            <label for="per_page"
                                class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-400">Per
                                Halaman:</label>
                            <form method="GET" class="flex-1 sm:flex-none">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <select name="per_page" id="per_page" onchange="this.form.submit()"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white sm:w-auto">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                        10</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                    </option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                    </option>
                                    <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500
                                    </option>
                                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>
                                        Semua</option>
                                </select>
                            </form>
                        </div>

                        <!-- Previous Button -->
                        @if ($murid->onFirstPage())
                            <button disabled
                                class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                <span>Sebelumnya</span>
                            </button>
                        @else
                            <a href="{{ $murid->previousPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                                class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                <span>Sebelumnya</span>
                            </a>
                        @endif
                    </div>

                    <!-- Page Info -->
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                        Halaman <span class="font-semibold">{{ $murid->currentPage() }}</span> dari <span
                            class="font-semibold">{{ $murid->lastPage() }}</span>
                    </span>

                    <!-- Next Button -->
                    @if ($murid->hasMorePages())
                        <a href="{{ $murid->nextPageUrl() }}&{{ http_build_query(request()->only('search', 'per_page')) }}"
                            class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            <span>Selanjutnya</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <button disabled
                            class="flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:text-gray-500">
                            <span>Selanjutnya</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div
            class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                @if (request('search'))
                    Tidak ada murid yang ditemukan dengan pencarian "<strong>{{ request('search') }}</strong>".
                @else
                    Belum ada data murid di sekolah ini.
                @endif
            </p>
        </div>
    @endif
</div>
