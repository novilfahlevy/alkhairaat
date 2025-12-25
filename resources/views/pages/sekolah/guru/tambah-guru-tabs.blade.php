<!-- Page Header Card -->
<div
    class="flex flex-col gap-y-4 md:flex-row md:justify-between md:items-center rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
    <div>
        <h1 class="text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
            Tambah Guru
        </h1>
        <p class="text-lg text-gray-500 dark:text-gray-400">
            <span class="font-medium">{{ $sekolah->nama }}</span>
        </p>
    </div>
    <div>
        <a href="{{ route('sekolah.show', $sekolah) }}"
            class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

@php
    $currentTab = request()->query('tab', 'manual');
@endphp

<div class="rounded-lg bg-white shadow-md dark:bg-gray-900">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex overflow-x-auto whitespace-nowrap scrollbar-hide" role="tablist">
            <a href="{{ route('sekolah.create-guru', ['sekolah' => $sekolah, 'tab' => 'manual']) }}" role="tab"
                aria-selected="{{ $currentTab === 'manual' ? 'true' : 'false' }}"
                class="inline-block min-w-[180px] border-b-2 px-6 py-4 text-sm font-medium transition {{ $currentTab === 'manual' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                <i class="fas fa-user-plus mr-2"></i>
                Tambah Guru Manual
            </a>
            <a href="{{ route('sekolah.create-guru', ['sekolah' => $sekolah, 'tab' => 'existing']) }}"
                role="tab" aria-selected="{{ $currentTab === 'existing' ? 'true' : 'false' }}"
                class="inline-block min-w-[180px] border-b-2 px-6 py-4 text-sm font-medium transition {{ $currentTab === 'existing' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                <i class="fas fa-users mr-2"></i>
                Pilih Guru yang Ada
            </a>
            {{-- <a href="{{ route('sekolah.create-guru', ['sekolah' => $sekolah, 'tab' => 'file']) }}" role="tab"
                aria-selected="{{ $currentTab === 'file' ? 'true' : 'false' }}"
                class="inline-block min-w-[180px] border-b-2 px-6 py-4 text-sm font-medium transition {{ $currentTab === 'file' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}">
                <i class="fas fa-file-upload mr-2"></i>
                Tambah Guru dengan File
            </a> --}}
        </div>
    </div>
</div>
