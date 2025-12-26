@props(['guru', 'sekolah'])

<!-- Guru Table Section -->
<div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Guru dan Jabatannya</h2>
    </div>

    <!-- Search and Per-Page Controls -->
    <div class="mb-6 space-y-3 sm:space-y-4">
        <form method="GET" class="flex flex-col gap-2 sm:flex-row">
            <input type="hidden" name="per_page_guru" value="{{ request('per_page_guru', 10) }}">
            <input type="text" name="search_guru" value="{{ request('search_guru') }}"
                placeholder="Cari nama, NIK, atau NUPTK guru..."
                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition sm:px-6">
                Cari
            </button>
            @if (request('search_guru'))
                <a href="{{ route('sekolah.show', $sekolah) }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if ($guru->count() > 0)
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nama</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            NIK</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status Kepegawaian</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            NPK</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            NUPTK</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Jabatan</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            No. HP/WA</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @foreach ($guru as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->nama }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nik ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->status_kepegawaian ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->npk ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->nuptk ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 flex gap-2 items-center">
                                <div class="space-y-2">
                                    <span
                                        class="inline-block rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700 dark:bg-brand-500/20 dark:text-brand-400">
                                        {{ $item->pivot?->jenis_jabatan ?? '-' }}
                                    </span>
                                    @if (
                                        $item->pivot?->keterangan_jabatan &&
                                            strtolower(trim($item->pivot?->keterangan_jabatan)) != strtolower(trim($item->pivot?->jenis_jabatan)))
                                        <span
                                            class="inline-block rounded-full bg-blue-light-100 px-3 py-1 text-xs font-medium text-blue-light-700 dark:bg-blue-light-500/20 dark:text-blue-light-400">
                                            {{ $item->pivot?->keterangan_jabatan }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->kontak_wa_hp ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="#"
                                    class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20 text-nowrap">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Mobile Card View -->
        <div class="block space-y-4 md:hidden">
            @foreach ($guru as $item)
                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-3 flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->nama }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">NIK: {{ $item->nik ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">NUPTK:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->nuptk ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jenis Kelamin:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->jenis_kelamin_label ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status Kepegawaian:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->status_kepegawaian ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="text-gray-600 dark:text-gray-400">Jabatan:</span>
                            <div class="space-y-2">
                                <span
                                    class="inline-block rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700 dark:bg-brand-500/20 dark:text-brand-400">
                                    {{ $item->pivot?->jenis_jabatan ?? '-' }}
                                </span>
                                @if ($item->pivot?->keterangan_jabatan &&
                                            strtolower(trim($item->pivot?->keterangan_jabatan)) != strtolower(trim($item->pivot?->jenis_jabatan)))
                                    <span
                                        class="inline-block ml-2 rounded-full bg-blue-light-100 px-3 py-1 text-xs font-medium text-blue-light-700 dark:bg-blue-light-500/20 dark:text-blue-light-400">
                                        {{ $item->pivot?->keterangan_jabatan }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">No. HP/WA:</span>
                            <span class="text-gray-900 dark:text-white">{{ $item->kontak_wa_hp ?? '-' }}</span>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="#"
                                class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20 text-nowrap">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Pagination -->
        @if ($guru->hasPages())
            <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                {{ $guru->appends(request()->except('page_guru'))->links() }}
            </div>
        @endif
    @else
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                @if (request('search_guru'))
                    Tidak ada guru yang ditemukan dengan pencarian "<strong>{{ request('search_guru') }}</strong>".
                @else
                    Belum ada data guru di sekolah ini.
                @endif
            </p>
        </div>
    @endif
</div>
