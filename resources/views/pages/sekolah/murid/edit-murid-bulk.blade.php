@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="space-y-6 pb-60">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('sekolah.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    Sekolah
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show', $sekolah) }}" class="hover:text-brand-600 dark:hover:text-brand-400">
                    {{ $sekolah->nama }}
                </a>
                <span>/</span>
                <a href="{{ route('sekolah.show-murid', $sekolah) }}"
                    class="hover:text-brand-600 dark:hover:text-brand-400">
                    Data Murid
                </a>
                <span>/</span>
                <span class="text-gray-900 dark:text-white">Edit Massal</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <div>
                <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                    Edit Murid Massal
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Edit kelas dan status kelulusan murid secara massal di {{ $sekolah->nama }}
                </p>
            </div>
            <div class="my-4 border-b border-gray-200 dark:border-gray-700"></div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('sekolah.show-murid', $sekolah) }}"
                    class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Bulk Edit Form -->
        <x-ui.card>
            <x-slot name="header">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Murid</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Total: {{ $murid->total() }} murid
                    </p>
                </div>
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
                            <label class="mb-1 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
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
                            <label class="mb-1 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
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

                        <!-- Tahun Masuk Filter -->
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
                                Tahun Masuk
                            </label>
                            <select name="tahun_masuk"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">Semua</option>
                                @foreach ($tahunMasukOptions ?? [] as $tahun)
                                    <option value="{{ $tahun }}" @selected(request('tahun_masuk') == $tahun)>{{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reset Button -->
                        @if (request('search') || request('jenis_kelamin') || request('status_kelulusan') || request('tahun_masuk'))
                            <div class="flex items-end">
                                <a href="{{ route('sekolah.edit-murid-bulk', $sekolah) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Reset
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bulk Edit Table Form -->
            @if ($murid->count() > 0)
                <form method="POST" action="{{ route('sekolah.update-murid-bulk', $sekolah) }}" id="bulk-edit-form">
                    @csrf
                    @method('PUT')

                    <!-- Bulk Action Panel -->
                    <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Aksi Massal</h3>
                        
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <!-- Bulk Kelas Update -->
                            <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-600 dark:bg-gray-700">
                                <label class="mb-2 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
                                    Set Kelas untuk Semua Murid
                                </label>
                                <div class="flex gap-2">
                                    <input type="text" id="bulk-kelas-input"
                                        class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
                                        placeholder="Contoh: XII IPA 1" />
                                    <button type="button" onclick="applyBulkKelas()"
                                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">
                                        Terapkan
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Masukkan nama kelas lalu klik "Terapkan" untuk mengubah semua kelas murid di halaman ini.
                                </p>
                            </div>

                            <!-- Bulk Status Update -->
                            <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-600 dark:bg-gray-700">
                                <label class="mb-2 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">
                                    Set Status Kelulusan untuk Semua Murid
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="setAllStatus('ya')"
                                        class="rounded-lg border border-green-300 bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-700 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50">
                                        Semua Lulus
                                    </button>
                                    <button type="button" onclick="setAllStatus('tidak')"
                                        class="rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-700 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                        Semua Tidak Lulus
                                    </button>
                                    <button type="button" onclick="setAllStatus('')"
                                        class="rounded-lg border border-yellow-300 bg-yellow-50 px-3 py-2 text-sm font-medium text-yellow-700 hover:bg-yellow-100 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50">
                                        Semua Belum Lulus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                class="flex items-center justify-center rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block">
                        <x-ui.table>
                            <x-slot name="thead">
                                <tr>
                                    <th
                                        class="w-12 px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        <input type="checkbox" id="select-all-desktop"
                                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700"
                                            onchange="toggleAllCheckboxes(this.checked)">
                                    </th>
                                    <th
                                        class="w-16 px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        No</th>
                                    <th
                                        class="min-w-[200px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Nama</th>
                                    <th
                                        class="min-w-[120px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        NISN</th>
                                    <th
                                        class="min-w-[100px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Jenis Kelamin</th>
                                    <th
                                        class="min-w-[100px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Tahun Masuk</th>
                                    <th
                                        class="min-w-[150px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Kelas</th>
                                    <th
                                        class="min-w-[180px] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Status Kelulusan</th>
                                </tr>
                            </x-slot>
                            <x-slot name="tbody">
                                @foreach ($murid as $index => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800" data-murid-row="{{ $item->id }}">
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="murid[{{ $item->id }}][selected]"
                                                value="1"
                                                class="murid-checkbox h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700"
                                                data-murid-id="{{ $item->id }}"
                                                onchange="updateSelectAllState()"
                                                checked>
                                        </td>
                                        <td class="px-4 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                            {{ $murid->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            <input type="hidden" name="murid[{{ $item->id }}][id]"
                                                value="{{ $item->id }}">
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
                                        <td class="px-6 py-4">
                                            <input type="text" name="murid[{{ $item->id }}][kelas]"
                                                value="{{ old('murid.' . $item->id . '.kelas', $item->pivot?->kelas ?? '') }}"
                                                class="kelas-input w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                                placeholder="Contoh: XII IPA 1" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="murid[{{ $item->id }}][status_kelulusan]"
                                                class="status-input w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                                <option value=""
                                                    @selected(old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === null || old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === '')>Belum Lulus</option>
                                                <option value="ya" @selected(old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === 'ya')>Sudah Lulus
                                                </option>
                                                <option value="tidak" @selected(old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === 'tidak')>Tidak Lulus
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-ui.table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="block space-y-4 md:hidden">
                        <!-- Mobile Select All -->
                        <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                            <input type="checkbox" id="select-all-mobile"
                                class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700"
                                onchange="toggleAllCheckboxes(this.checked)"
                                checked>
                            <label for="select-all-mobile" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Pilih Semua Murid
                            </label>
                            <span class="ml-auto text-sm text-gray-500 dark:text-gray-400" id="selected-count-mobile">
                                {{ $murid->count() }} dipilih
                            </span>
                        </div>

                        @foreach ($murid as $index => $item)
                            <div
                                class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800"
                                data-murid-row="{{ $item->id }}">
                                <div class="mb-3 flex items-start gap-3">
                                    <input type="checkbox"
                                        value="1"
                                        class="murid-checkbox-mobile mt-1 h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700"
                                        data-murid-id="{{ $item->id }}"
                                        data-sync-checkbox="murid[{{ $item->id }}][selected]"
                                        onchange="syncMobileToDesktop(this); updateSelectAllState()"
                                        checked>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                {{ $murid->firstItem() + $index }}
                                            </span>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->nama }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">NISN: {{ $item->nisn }}</p>
                                    </div>
                                </div>

                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Jenis Kelamin:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $item->jenis_kelamin_label }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Tahun Masuk:</span>
                                        <span
                                            class="text-gray-900 dark:text-white">{{ $item->pivot?->tahun_masuk ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">Kelas</label>
                                        <input type="text"
                                            value="{{ old('murid.' . $item->id . '.kelas', $item->pivot?->kelas ?? '') }}"
                                            class="kelas-input-mobile w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            data-sync-input="murid[{{ $item->id }}][kelas]"
                                            oninput="syncMobileToDesktop(this)"
                                            placeholder="Contoh: XII IPA 1" />
                                    </div>
                                    <div>
                                        <label
                                            class="mb-1 block text-xs font-semibold uppercase text-gray-600 dark:text-gray-400">Status
                                            Kelulusan</label>
                                        <select
                                            class="status-input-mobile w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            data-sync-select="murid[{{ $item->id }}][status_kelulusan]"
                                            onchange="syncMobileToDesktop(this)">
                                            <option value=""
                                                @selected(old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === null || old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === '')>Belum Lulus</option>
                                            <option value="ya" @selected(old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === 'ya')>Sudah Lulus
                                            </option>
                                            <option value="tidak" @selected(old('murid.' . $item->id . '.status_kelulusan', $item->pivot?->status_kelulusan) === 'tidak')>Tidak Lulus
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bottom Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="flex items-center justify-center rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <x-pagination :paginator="$murid"></x-pagination>
                </div>
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
        </x-ui.card>
    </div>

    <script>
        function applyBulkKelas() {
            const kelasInput = document.getElementById('bulk-kelas-input');
            const kelas = kelasInput.value;
            
            // Get all checked checkboxes (desktop only, they have the name attribute)
            document.querySelectorAll('.murid-checkbox:checked').forEach(checkbox => {
                const muridId = checkbox.getAttribute('data-murid-id');
                // Find kelas input by name attribute (desktop)
                const kelasInputField = document.querySelector(`input[name="murid[${muridId}][kelas]"]`);
                if (kelasInputField) {
                    kelasInputField.value = kelas;
                }
                // Also update mobile input if exists
                const mobileKelasInput = document.querySelector(`input[data-sync-input="murid[${muridId}][kelas]"]`);
                if (mobileKelasInput) {
                    mobileKelasInput.value = kelas;
                }
            });

            // Visual feedback
            const originalBg = kelasInput.style.backgroundColor;
            kelasInput.style.backgroundColor = '#d1fae5'; // green-100
            setTimeout(() => {
                kelasInput.style.backgroundColor = originalBg;
            }, 500);
        }

        function setAllStatus(status) {
            // Get all checked checkboxes (desktop only, they have the name attribute)
            document.querySelectorAll('.murid-checkbox:checked').forEach(checkbox => {
                const muridId = checkbox.getAttribute('data-murid-id');
                // Find status select by name attribute (desktop)
                const statusSelect = document.querySelector(`select[name="murid[${muridId}][status_kelulusan]"]`);
                if (statusSelect) {
                    statusSelect.value = status;
                }
                // Also update mobile select if exists
                const mobileStatusSelect = document.querySelector(`select[data-sync-select="murid[${muridId}][status_kelulusan]"]`);
                if (mobileStatusSelect) {
                    mobileStatusSelect.value = status;
                }
            });
        }

        function syncMobileToDesktop(element) {
            // Sync mobile field value to corresponding desktop field
            const syncAttr = element.getAttribute('data-sync-input') || 
                             element.getAttribute('data-sync-select') || 
                             element.getAttribute('data-sync-checkbox');
            
            if (syncAttr) {
                const desktopField = document.querySelector(`[name="${syncAttr}"]`);
                if (desktopField) {
                    if (element.type === 'checkbox') {
                        desktopField.checked = element.checked;
                    } else {
                        desktopField.value = element.value;
                    }
                }
            }
        }

        function toggleAllCheckboxes(checked) {
            // Toggle desktop checkboxes
            document.querySelectorAll('.murid-checkbox').forEach(checkbox => {
                checkbox.checked = checked;
            });
            // Toggle mobile checkboxes
            document.querySelectorAll('.murid-checkbox-mobile').forEach(checkbox => {
                checkbox.checked = checked;
            });
            // Sync both select-all checkboxes
            const desktopSelectAll = document.getElementById('select-all-desktop');
            const mobileSelectAll = document.getElementById('select-all-mobile');
            if (desktopSelectAll) desktopSelectAll.checked = checked;
            if (mobileSelectAll) mobileSelectAll.checked = checked;
            updateSelectedCount();
        }

        function updateSelectAllState() {
            const checkboxes = document.querySelectorAll('.murid-checkbox');
            const checkedCount = document.querySelectorAll('.murid-checkbox:checked').length;
            const allChecked = checkboxes.length === checkedCount;
            const someChecked = checkedCount > 0 && !allChecked;

            const desktopSelectAll = document.getElementById('select-all-desktop');
            const mobileSelectAll = document.getElementById('select-all-mobile');
            
            if (desktopSelectAll) {
                desktopSelectAll.checked = allChecked;
                desktopSelectAll.indeterminate = someChecked;
            }
            if (mobileSelectAll) {
                mobileSelectAll.checked = allChecked;
                mobileSelectAll.indeterminate = someChecked;
            }
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.murid-checkbox:checked').length;
            const mobileCounter = document.getElementById('selected-count-mobile');
            if (mobileCounter) {
                mobileCounter.textContent = checkedCount + ' dipilih';
            }
        }

        // Allow pressing Enter in the bulk kelas input to apply
        document.getElementById('bulk-kelas-input')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyBulkKelas();
            }
        });

        // Initialize selected count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();
        });
    </script>
@endsection
