@props(['murid', 'sekolah'])

<!-- Murid Table Section -->
<div x-data="{
    showDeleteModal: false,
    selectedMurid: null,
    confirmationText: '',
    countdown: 10,
    countdownActive: false,
    openDeleteModal(muridId, muridNama) {
        this.selectedMurid = { id: muridId, nama: muridNama };
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
    deleteMurid() {
        if (this.selectedMurid) {
            document.getElementById('delete-murid-form-' + this.selectedMurid.id).submit();
        }
    }
}" @keydown.escape.window="showDeleteModal = false">
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Murid</h2>
        </x-slot>

        <!-- Search and Filter Controls -->
        <div class="mb-6 space-y-3 sm:space-y-4">
            <!-- Search and Filter Form -->
            <form method="GET" class="flex flex-col gap-3">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                
                <!-- Search Input -->
                <div class="flex flex-col gap-2 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NISN, atau NIK murid..."
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                    <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition sm:px-6">
                        Cari
                    </button>
                </div>

                <!-- Filters Row -->
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Jenis Kelamin Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">
                            Jenis Kelamin
                        </label>
                        <select name="jenis_kelamin"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Semua</option>
                            <option value="L" @selected(request('jenis_kelamin') === 'L')>Laki-laki</option>
                            <option value="P" @selected(request('jenis_kelamin') === 'P')>Perempuan</option>
                        </select>
                    </div>

                    <!-- Status Kelulusan Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase mb-1">
                            Status Kelulusan
                        </label>
                        <select name="status_kelulusan"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Semua</option>
                            <option value="belum" @selected(request('status_kelulusan') === 'belum')>Belum Lulus</option>
                            <option value="ya" @selected(request('status_kelulusan') === 'ya')>Sudah Lulus</option>
                            <option value="tidak" @selected(request('status_kelulusan') === 'tidak')>Tidak Lulus</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    @if (request('search') || request('jenis_kelamin') || request('status_kelulusan'))
                        <div class="flex items-end">
                            <a href="{{ route('sekolah.show-murid', $sekolah) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            <!-- Export and Action Buttons -->
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                @if ($murid->count() > 0)
                    <form method="GET" action="{{ route('sekolah.export-murid', $sekolah) }}" class="w-full sm:w-auto">
                        <!-- Pass all current filter parameters to export -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if (request('jenis_kelamin'))
                            <input type="hidden" name="jenis_kelamin" value="{{ request('jenis_kelamin') }}">
                        @endif
                        @if (request('status_kelulusan'))
                            <input type="hidden" name="status_kelulusan" value="{{ request('status_kelulusan') }}">
                        @endif
                        <button type="submit"
                            class="flex w-full items-center justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 transition sm:w-auto">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Ekspor ke XLSX
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Desktop Table View -->
        @if ($murid->count() > 0)
            <div class="hidden md:block">
                <x-ui.table>
                    <x-slot name="thead">
                        <tr>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Nama</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                NISN</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Jenis Kelamin</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Tahun Masuk</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                NIK</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Kelas</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status Kelulusan</th>
                            <th
                                class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            </th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @foreach ($murid as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->nisn ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->jenis_kelamin_label ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->pivot?->tahun_masuk ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->nik ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $item->pivot?->kelas ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span>
                                        @if ($item->pivot?->status_kelulusan === 'ya')
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Lulus
                                            </span>
                                        @elseif ($item->pivot?->status_kelulusan === 'tidak')
                                            <span
                                                class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                Tidak Lulus
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Belum Lulus
                                            </span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('sekolah.show-detail-murid', ['sekolah' => $sekolah->id, 'murid' => $item->id]) }}"
                                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20 text-nowrap">Lihat
                                            Detail</a>
                                        <form id="delete-murid-form-{{ $item->id }}"
                                            action="{{ route('sekolah.delete-murid', ['sekolah' => $sekolah->id, 'murid' => $item->id]) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                            class="rounded-md bg-red-50 px-3 py-2 text-center text-sm font-medium text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 text-nowrap">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-ui.table>
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
                                <span class="text-gray-900 dark:text-white">{{ $item->pivot?->tahun_masuk }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Kelas:</span>
                                <span class="text-gray-900 dark:text-white">{{ $item->pivot?->kelas ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span>
                                    @if ($item->pivot?->status_kelulusan === 'ya')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Lulus
                                        </span>
                                    @elseif ($item->pivot?->status_kelulusan === 'tidak')
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Tidak Lulus
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Belum Lulus
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('sekolah.show-detail-murid', ['sekolah' => $sekolah->id, 'murid' => $item->id]) }}"
                                    class="text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex-1 rounded-md bg-blue-50 px-3 py-2 text-center text-sm font-medium dark:bg-blue-900/20 text-nowrap">
                                    Lihat Detail
                                </a>
                                <form id="delete-murid-form-{{ $item->id }}"
                                    action="{{ route('sekolah.delete-murid', ['sekolah' => $sekolah->id, 'murid' => $item->id]) }}"
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

            <x-pagination :paginator="$murid"></x-pagination>
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

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false"
            class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5">

            <!-- Backdrop -->
            <div @click="showDeleteModal = false"
                class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
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
                        Hapus Murid dari Sekolah?
                    </h3>

                    <!-- Description -->
                    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                        Anda akan menghapus murid <strong x-text="selectedMurid?.nama"></strong> dari sekolah ini.
                    </p>
                    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                        <strong class="text-red-600 dark:text-red-400">Catatan:</strong> Data murid tidak akan dihapus
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
                        <button @click="deleteMurid()" :disabled="confirmationText !== 'HAPUS' || countdownActive"
                            :class="{
                                'bg-red-600 hover:bg-red-700 text-white': confirmationText === 'HAPUS' && !
                                    countdownActive,
                                'bg-gray-300 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500': confirmationText !==
                                'HAPUS' ||
                                    countdownActive
                            }"
                            class="flex-1 rounded-lg px-4 py-2.5 text-sm font-medium transition">
                            Hapus Murid
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
