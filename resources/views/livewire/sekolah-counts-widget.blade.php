<div class="space-y-4 sm:space-y-6 mb-6">
    <!-- Statistik Status Sekolah -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100">Statistik Sekolah</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            <!-- Total Sekolah -->
            <div
                class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-slate-200 dark:border-slate-600 sm:col-span-2 md:col-span-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 uppercase tracking-wide truncate">Total Sekolah
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100 mt-1">
                            {{ number_format($counts['total']) }}</p>
                    </div>
                    <i class="fas fa-school text-3xl sm:text-4xl text-slate-300 dark:text-slate-500 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Sekolah Aktif -->
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-green-200 dark:border-green-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-green-600 dark:text-green-300 uppercase tracking-wide truncate">Aktif
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-800 dark:text-green-100 mt-1">
                            {{ number_format($counts['aktif']) }}</p>
                    </div>
                    <i class="fas fa-circle-check text-3xl sm:text-4xl text-green-300 dark:text-green-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Sekolah Tidak Aktif -->
            <div
                class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-red-200 dark:border-red-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-red-600 dark:text-red-300 uppercase tracking-wide truncate">Tidak Aktif
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-800 dark:text-red-100 mt-1">
                            {{ number_format($counts['tidak_aktif']) }}</p>
                    </div>
                    <i class="fas fa-circle-xmark text-3xl sm:text-4xl text-red-300 dark:text-red-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Statistik Jenis Sekolah -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100">Statistik Jenis Sekolah</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-3 lg:gap-4">
            <!-- RA / TK -->
            <div
                class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900 dark:to-amber-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-amber-200 dark:border-amber-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-amber-600 dark:text-amber-300 uppercase tracking-wide truncate">RA / TK
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-amber-800 dark:text-amber-100 mt-1">
                            {{ number_format($counts['jenis']['ra_tk']) }}</p>
                    </div>
                    <i class="fas fa-child text-3xl sm:text-4xl text-amber-300 dark:text-amber-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- MI / SD -->
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-blue-200 dark:border-blue-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-300 uppercase tracking-wide truncate">MI / SD
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-800 dark:text-blue-100 mt-1">
                            {{ number_format($counts['jenis']['mi_sd']) }}</p>
                    </div>
                    <i class="fas fa-book text-3xl sm:text-4xl text-blue-300 dark:text-blue-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- MTS / SMP -->
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-purple-200 dark:border-purple-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-purple-600 dark:text-purple-300 uppercase tracking-wide truncate">MTS / SMP
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-purple-800 dark:text-purple-100 mt-1">
                            {{ number_format($counts['jenis']['mts_smp']) }}</p>
                    </div>
                    <i class="fas fa-pen-to-square text-3xl sm:text-4xl text-purple-300 dark:text-purple-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- MA / SMA -->
            <div
                class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900 dark:to-cyan-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-cyan-200 dark:border-cyan-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-cyan-600 dark:text-cyan-300 uppercase tracking-wide truncate">MA / SMA
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-cyan-800 dark:text-cyan-100 mt-1">
                            {{ number_format($counts['jenis']['ma_sma']) }}</p>
                    </div>
                    <i class="fas fa-graduation-cap text-3xl sm:text-4xl text-cyan-300 dark:text-cyan-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Perguruan Tinggi -->
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-indigo-200 dark:border-indigo-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-indigo-600 dark:text-indigo-300 uppercase tracking-wide truncate">PT
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-indigo-800 dark:text-indigo-100 mt-1">
                            {{ number_format($counts['jenis']['pt']) }}</p>
                    </div>
                    <i class="fas fa-university text-3xl sm:text-4xl text-indigo-300 dark:text-indigo-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Statistik Bentuk Pendidikan -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100">Statistik Bentuk Pendidikan</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            <!-- Sekolah Umum -->
            <div
                class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900 dark:to-teal-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-teal-200 dark:border-teal-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-teal-600 dark:text-teal-300 uppercase tracking-wide truncate">Sekolah Umum
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-teal-800 dark:text-teal-100 mt-1">
                            {{ number_format($counts['bentuk']['umum']) }}</p>
                    </div>
                    <i class="fas fa-building text-3xl sm:text-4xl text-teal-300 dark:text-teal-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Pesantren -->
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-orange-200 dark:border-orange-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-orange-600 dark:text-orange-300 uppercase tracking-wide truncate">Pesantren
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-orange-800 dark:text-orange-100 mt-1">
                            {{ number_format($counts['bentuk']['ponpes']) }}</p>
                    </div>
                    <i class="fas fa-mosque text-3xl sm:text-4xl text-orange-300 dark:text-orange-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
