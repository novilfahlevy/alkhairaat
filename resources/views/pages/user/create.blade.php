@extends('layouts.app')

@section('content')
    <!-- Page Content -->
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900 mb-50">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        {{ $title }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Buat akun user baru dan atur hak akses sekolah
                    </p>
                </div>
                <a href="{{ route('user.index') }}"
                    class="flex items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('user.store') }}" method="POST" id="userForm" x-data="{ isSubmitting: false, role: '' }"
            x-on:submit="isSubmitting = true">
            @csrf

            <!-- Account Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Informasi Akun</h2>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Nama -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username unik"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" x-model="role"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('role') border-red-500 @enderror">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ $roleLabels[$role] ?? $role }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Masukkan ulang password"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sekolah Section -->
            <div class="mb-8" x-show="role != 'pengurus_besar'">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">Sekolah yang Dinaungi</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Pilih sekolah-sekolah yang akan berada di bawah naungan user ini
                </p>

                <div class="space-y-6">
                    @forelse ($sekolahByProvinsi as $provinsi => $kabupatenList)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg" x-data="{ open: false, provinsiId: {{ $loop->index }} }">
                            <div
                                class="w-full flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer" @click="open = !open">
                                <button type="button"
                                    class="flex items-center justify-between flex-1 font-medium text-gray-800 dark:text-white/90">
                                    <span>{{ $provinsi }}</span>
                                    <svg class="h-5 w-5 text-gray-600 dark:text-gray-400 transition-transform"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </button>
                                <button type="button"
                                    @click.prevent.stop="window.checkAllProvinsi(provinsiId); open = true"
                                    class="ml-3 px-3 py-1.5 text-xs font-medium rounded bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 whitespace-nowrap">
                                    Pilih Semua
                                </button>
                            </div>

                            <div x-show="open" x-transition class="space-y-4 py-5">
                                @forelse ($kabupatenList as $kabupaten)
                                    <div class="mx-4">
                                        <div
                                            class="flex items-center justify-between font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
                                            <span>{{ $kabupaten->nama_kabupaten }}</span>
                                            <button type="button" @click.prevent="window.checkAllKabupaten({{ $loop->index }}, provinsiId)"
                                                class="px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 whitespace-nowrap">
                                                Pilih Semua
                                            </button>
                                        </div>
                                        <div class="space-y-2 ml-4">
                                            @forelse ($kabupaten->sekolah as $sekolah)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="sekolah_ids[]" value="{{ $sekolah->id }}"
                                                        data-provinsi="{{ $loop->parent->parent->index }}"
                                                        data-kabupaten="{{ $loop->parent->index }}"
                                                        {{ in_array($sekolah->id, old('sekolah_ids', [])) ? 'checked' : '' }}
                                                        class="h-4 w-4 rounded border-gray-300 bg-white text-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-900">
                                                    <span
                                                        class="ml-2 text-sm text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                                                        {{ $sekolah->nama }}
                                                    </span>
                                                </label>
                                            @empty
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Tidak ada sekolah aktif
                                                </p>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 px-4">
                                        Tidak ada kabupaten
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div
                            class="rounded-lg bg-yellow-50 dark:bg-yellow-900/30 p-4 text-sm text-yellow-800 dark:text-yellow-300">
                            Tidak ada sekolah aktif yang tersedia
                        </div>
                    @endforelse
                </div>

                @error('sekolah_ids')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div
                class="mt-8 flex flex-col-reverse gap-4 border-t border-gray-200 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <a href="{{ route('user.index') }}"
                    class="flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center rounded-lg px-6 py-3 text-sm font-medium text-white transition"
                    :disabled="isSubmitting" x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                    <template x-if="isSubmitting">
                        <svg class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </template>
                    <template x-if="!isSubmitting">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </template>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        window.checkAllProvinsi = function(provinsiId) {
            const checkboxes = document.querySelectorAll(`input[data-provinsi="${provinsiId}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        };

        window.checkAllKabupaten = function(kabupatenId, provinsiId) {
            const checkboxes = document.querySelectorAll(`input[data-kabupaten="${kabupatenId}"][data-provinsi="${provinsiId}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        };
    </script>
@endpush