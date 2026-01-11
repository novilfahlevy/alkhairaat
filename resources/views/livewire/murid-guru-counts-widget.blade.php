<div class="space-y-4 sm:space-y-6">
    <!-- Statistik Murid -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100">Statistik Murid</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3 lg:gap-4">
            <!-- Total Murid -->
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-blue-200 dark:border-blue-700 md:col-span-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-300 uppercase tracking-wide truncate">
                            Total
                            Murid
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-800 dark:text-blue-100 mt-1">
                            {{ number_format($counts['murid']['total']) }}</p>
                    </div>
                    <i class="fas fa-users text-3xl sm:text-4xl text-blue-300 dark:text-blue-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Lulus -->
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-green-200 dark:border-green-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-green-600 dark:text-green-300 uppercase tracking-wide truncate">
                            Lulus
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-800 dark:text-green-100 mt-1">
                            {{ number_format($counts['murid']['lulus']) }}</p>
                    </div>
                    <i
                        class="fas fa-check-circle text-3xl sm:text-4xl text-green-300 dark:text-green-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Belum Lulus -->
            <div
                class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-yellow-200 dark:border-yellow-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-yellow-600 dark:text-yellow-300 uppercase tracking-wide truncate">
                            Belum Lulus</p>
                        <p class="text-2xl sm:text-3xl font-bold text-yellow-800 dark:text-yellow-100 mt-1">
                            {{ number_format($counts['murid']['belum_lulus']) }}</p>
                    </div>
                    <i
                        class="fas fa-graduation-cap text-3xl sm:text-4xl text-yellow-300 dark:text-yellow-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Tidak Lulus -->
            <div
                class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-red-200 dark:border-red-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-red-600 dark:text-red-300 uppercase tracking-wide truncate">
                            Tidak Lulus</p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-800 dark:text-red-100 mt-1">
                            {{ number_format($counts['murid']['tidak_lulus']) }}</p>
                    </div>
                    <i
                        class="fas fa-times-circle text-3xl sm:text-4xl text-red-300 dark:text-red-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-3 lg:gap-4 mt-2 sm:mt-3 lg:mt-4">
            <!-- Murid Laki-laki -->
            <div
                class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900 dark:to-cyan-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-cyan-200 dark:border-cyan-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-cyan-600 dark:text-cyan-300 uppercase tracking-wide truncate">
                            Laki-laki
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-cyan-800 dark:text-cyan-100 mt-1">
                            {{ number_format($counts['murid']['laki_laki']) }}</p>
                    </div>
                    <i class="fas fa-mars text-3xl sm:text-4xl text-cyan-300 dark:text-cyan-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Perempuan -->
            <div
                class="bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900 dark:to-rose-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-rose-200 dark:border-rose-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-rose-600 dark:text-rose-300 uppercase tracking-wide truncate">
                            Perempuan
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-rose-800 dark:text-rose-100 mt-1">
                            {{ number_format($counts['murid']['perempuan']) }}</p>
                    </div>
                    <i class="fas fa-venus text-3xl sm:text-4xl text-rose-300 dark:text-rose-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Statistik Guru -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-100">Statistik Guru</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            <!-- Total Guru -->
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-indigo-200 dark:border-indigo-700 md:col-span-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-indigo-600 dark:text-indigo-300 uppercase tracking-wide truncate">
                            Total
                            Guru
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-indigo-800 dark:text-indigo-100 mt-1">
                            {{ number_format($counts['guru']['total']) }}</p>
                    </div>
                    <i
                        class="fas fa-chalkboard-user text-3xl sm:text-4xl text-indigo-300 dark:text-indigo-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Aktif -->
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-emerald-200 dark:border-emerald-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-emerald-600 dark:text-emerald-300 uppercase tracking-wide truncate">
                            Aktif</p>
                        <p class="text-2xl sm:text-3xl font-bold text-emerald-800 dark:text-emerald-100 mt-1">
                            {{ number_format($counts['guru']['aktif']) }}</p>
                    </div>
                    <i
                        class="fas fa-circle-check text-3xl sm:text-4xl text-emerald-300 dark:text-emerald-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Tidak Aktif -->
            <div
                class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-red-200 dark:border-red-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-red-600 dark:text-red-300 uppercase tracking-wide truncate">
                            Tidak
                            Aktif
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-800 dark:text-red-100 mt-1">
                            {{ number_format($counts['guru']['non_aktif']) }}</p>
                    </div>
                    <i
                        class="fas fa-circle-xmark text-3xl sm:text-4xl text-red-300 dark:text-red-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Laki-laki -->
            <div
                class="bg-gradient-to-br from-sky-50 to-sky-100 dark:from-sky-900 dark:to-sky-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-sky-200 dark:border-sky-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-sky-600 dark:text-sky-300 uppercase tracking-wide truncate">
                            Laki-laki</p>
                        <p class="text-2xl sm:text-3xl font-bold text-sky-800 dark:text-sky-100 mt-1">
                            {{ number_format($counts['guru']['laki_laki']) }}</p>
                    </div>
                    <i class="fas fa-mars text-3xl sm:text-4xl text-sky-300 dark:text-sky-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Perempuan -->
            <div
                class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900 dark:to-pink-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-pink-200 dark:border-pink-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-pink-600 dark:text-pink-300 uppercase tracking-wide truncate">
                            Perempuan
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-pink-800 dark:text-pink-100 mt-1">
                            {{ number_format($counts['guru']['perempuan']) }}</p>
                    </div>
                    <i class="fas fa-venus text-3xl sm:text-4xl text-pink-300 dark:text-pink-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3 lg:gap-4 mt-4 sm:mt-6">
            <!-- Guru PNS -->
            <div
                class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900 dark:to-amber-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-amber-200 dark:border-amber-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-amber-600 dark:text-amber-300 uppercase tracking-wide truncate">
                            PNS
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-amber-800 dark:text-amber-100 mt-1">
                            {{ number_format($counts['guru']['pns']) }}</p>
                    </div>
                    <i
                        class="fas fa-id-badge text-3xl sm:text-4xl text-amber-300 dark:text-amber-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Non PNS -->
            <div
                class="bg-gradient-to-br from-lime-50 to-lime-100 dark:from-lime-900 dark:to-lime-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-lime-200 dark:border-lime-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-lime-600 dark:text-lime-300 uppercase tracking-wide truncate">
                            Non PNS
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-lime-800 dark:text-lime-100 mt-1">
                            {{ number_format($counts['guru']['non_pns']) }}</p>
                    </div>
                    <i class="fas fa-briefcase text-3xl sm:text-4xl text-lime-300 dark:text-lime-600 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru PPPK -->
            <div
                class="bg-gradient-to-br from-fuchsia-50 to-fuchsia-100 dark:from-fuchsia-900 dark:to-fuchsia-800 rounded-lg p-3 sm:p-4 lg:p-6 border border-fuchsia-200 dark:border-fuchsia-700">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p
                            class="text-xs sm:text-sm font-medium text-fuchsia-600 dark:text-fuchsia-300 uppercase tracking-wide truncate">
                            PPPK
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-fuchsia-800 dark:text-fuchsia-100 mt-1">
                            {{ number_format($counts['guru']['pppk']) }}</p>
                    </div>
                    <i
                        class="fas fa-certificate text-3xl sm:text-4xl text-fuchsia-300 dark:text-fuchsia-600 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
