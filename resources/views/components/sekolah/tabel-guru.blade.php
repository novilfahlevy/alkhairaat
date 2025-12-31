@props(['guru', 'sekolah'])

<!-- Guru Table Section -->
<div x-data="{
    showDeleteModal: false,
    selectedGuru: null,
    confirmationText: '',
    countdown: 10,
    countdownActive: false,
    openDeleteModal(guruId, guruNama) {
        this.selectedGuru = { id: guruId, nama: guruNama };
        this.showDeleteModal = true;
        this.confirmationText = '';
        this.startCountdown();
    },
    startCountdown() {
        this.countdownActive = true;
        this.countdown = 10;
        const interval = setInterval(() => {
            this.countdown--;
            if (this.countdown <= 0) {
                this.countdownActive = false;
                clearInterval(interval);
            }
        }, 1000);
    },
    deleteGuru() {
        if (this.selectedGuru) {
            document.getElementById('delete-guru-form-' + this.selectedGuru.id).submit();
        }
    }
}" @keydown.escape.window="showDeleteModal = false">
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Guru dan Jabatannya</h2>
        </x-slot>

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
            <div class="hidden md:block">
                <x-ui.table>
                    <x-slot name="thead">
                        <tr>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Nama</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                NIK</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status Kepegawaian</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                NPK</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                NUPTK</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Jabatan</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                No. HP/WA</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            </th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @foreach ($guru as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->nama }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->nik ?? '-' }}
                                </td>
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
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('sekolah.show-detail-guru', ['sekolah' => $sekolah->id, 'guru' => $item->id]) }}"
                                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20 text-nowrap">
                                            Lihat Detail
                                        </a>
                                        <form id="delete-guru-form-{{ $item->id }}"
                                            action="{{ route('sekolah.delete-guru', ['sekolah' => $sekolah->id, 'guru' => $item->id]) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                            class="rounded-md bg-red-50 px-3 py-2 text-center text-sm font-medium text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 text-nowrap">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-ui.table>
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
                                <span
                                    class="text-gray-900 dark:text-white">{{ $item->jenis_kelamin_label ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status Kepegawaian:</span>
                                <span
                                    class="text-gray-900 dark:text-white">{{ $item->status_kepegawaian ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-600 dark:text-gray-400">Jabatan:</span>
                                <div class="space-y-2">
                                    <span
                                        class="inline-block rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700 dark:bg-brand-500/20 dark:text-brand-400">
                                        {{ $item->pivot?->jenis_jabatan ?? '-' }}
                                    </span>
                                    @if (
                                        $item->pivot?->keterangan_jabatan &&
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
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('sekolah.show-detail-guru', ['sekolah' => $sekolah->id, 'guru' => $item->id]) }}"
                                    class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20 text-nowrap">
                                    Lihat Detail
                                </a>
                                <form id="delete-guru-form-{{ $item->id }}"
                                    action="{{ route('sekolah.delete-guru', ['sekolah' => $sekolah->id, 'guru' => $item->id]) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button"
                                    @click="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                    class="flex-1 rounded-md bg-red-50 px-3 py-2 text-center text-sm font-medium text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$guru"></x-pagination>
        @else
            <div
                class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @if (request('search_guru'))
                        Tidak ada guru yang ditemukan dengan pencarian "<strong>{{ request('search_guru') }}</strong>".
                    @else
                        Belum ada data guru di sekolah ini.
                    @endif
                </p>
            </div>
        @endif
    </x-ui.card>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false"
        class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">

        <!-- Backdrop -->
        <div @click="showDeleteModal = false" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        <!-- Modal Content -->
        <div @click.stop class="relative w-full max-w-lg rounded-3xl bg-white dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Close Button -->
            <button @click="showDeleteModal = false"
                class="absolute right-4 top-4 rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6">
                <!-- Icon -->
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Title -->
                <h3 class="mt-4 text-center text-xl font-semibold text-gray-900 dark:text-white">
                    Hapus Guru dari Sekolah?
                </h3>

                <!-- Description -->
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Anda akan menghapus guru <strong x-text="selectedGuru?.nama"></strong> dari sekolah ini.
                </p>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    <strong class="text-red-600 dark:text-red-400">Catatan:</strong> Data guru tidak akan dihapus
                    dari sistem, hanya hubungannya dengan sekolah ini saja yang akan dihapus.
                </p>

                <!-- Confirmation Input -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Ketik <span class="font-bold">HAPUS</span> untuk konfirmasi
                    </label>
                    <input type="text" x-model="confirmationText"
                        class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                        placeholder="Ketik HAPUS" />
                </div>

                <!-- Countdown Warning -->
                <div x-show="countdownActive" class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                    Tunggu <span x-text="countdown" class="font-bold text-red-600 dark:text-red-400"></span> detik
                    untuk melanjutkan
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex gap-3">
                    <button @click="showDeleteModal = false"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </button>
                    <button @click="deleteGuru()" :disabled="confirmationText !== 'HAPUS' || countdownActive"
                        :class="{
                            'bg-red-600 hover:bg-red-700 text-white': confirmationText === 'HAPUS' && !
                                countdownActive,
                            'bg-gray-300 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500': confirmationText !==
                                'HAPUS' ||
                                countdownActive
                        }"
                        class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition">
                        Hapus Guru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add CSS for x-cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
