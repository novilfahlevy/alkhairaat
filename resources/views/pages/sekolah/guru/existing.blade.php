@extends('layouts.app')

@section('content')
    <div class="space-y-6 pb-60">
        @include('pages.sekolah.guru.tambah-guru-tabs', compact('sekolah'))

        <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-white">Tambah Guru dari Data yang Ada</h1>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div
                    class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    <p class="font-medium mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sekolah.store-existing-guru', $sekolah) }}" method="POST" x-data="{ isSubmitting: false, selectedGuruId: '' }"
                @submit="isSubmitting = true">
                @csrf
                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-700">
                    <div class="space-y-6">
                        <!-- Pilih Guru -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Pilih Guru <span class="text-red-500">*</span>
                            </label>
                            <select name="id_guru" id="guruSelect" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white select2">
                                <option value="">Pilih Guru</option>
                            </select>
                            @error('id_guru')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Jabatan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Jenis Jabatan <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_jabatan" required onchange="onJenisJabatanChange()"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">Pilih Jenis Jabatan</option>
                                @foreach (\App\Models\JabatanGuru::JENIS_JABATAN_OPTIONS as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('jenis_jabatan') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_jabatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan Jabatan -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Keterangan Jabatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="keterangan_jabatan" value="{{ old('keterangan_jabatan', '') }}"
                                placeholder="Keterangan Jabatan" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            @error('keterangan_jabatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div
                        class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                        <a href="{{ route('sekolah.show', $sekolah) }}"
                            class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                            class="flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium transition-all bg-brand-500 text-white hover:bg-brand-600 dark:bg-brand-600 dark:hover:bg-brand-700 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed dark:disabled:bg-gray-700">
                            <span x-show="!isSubmitting">Tambah Guru</span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function onJenisJabatanChange() {
            const jenisJabatan = document.querySelector('select[name="jenis_jabatan"]').value;
            const keteranganInput = document.querySelector('input[name="keterangan_jabatan"]');

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_KEPALA_SEKOLAH }}';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_WAKIL_KEPALA_SEKOLAH }}';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_GURU }}') {
                keteranganInput.placeholder = 'Mata pelajaran yang diampu, contoh: Matematika';
                keteranganInput.value = '';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_STAFF_TU }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_STAFF_TU }}';
            }

            if (jenisJabatan === '{{ \App\Models\JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA }}') {
                keteranganInput.value = '{{ \App\Models\JabatanGuru::JENIS_JABATAN_PENGASUH_ASRAMA }}';
            }
        }

        function updateSubmitButtonState() {
            const guruId = document.querySelector('select[name="id_guru"]').value;
            const submitBtn = document.querySelector('#submitBtn');
            submitBtn.disabled = !guruId;
        }

        // Initialize Select2 for Guru
        document.addEventListener('DOMContentLoaded', function() {
            // Check if jQuery and Select2 are loaded
            if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                console.error('jQuery or Select2 not loaded');
                return;
            }

            const $guruSelect = $('#guruSelect');

            $guruSelect.select2({
                placeholder: 'Cari guru berdasarkan nama, NIK, atau NUPTK...',
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: "{{ route('sekolah.get-existing-guru', ['sekolah' => $sekolah->id]) }}",
                    delay: 500,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    }
                }
            });

            // Update button state when selection changes
            $guruSelect.on('change', function() {
                updateSubmitButtonState();
            });

            // Initial state
            updateSubmitButtonState();
        });
    </script>
@endpush
