<div class="space-y-4 sm:space-y-6">
    <!-- Statistik Murid -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Statistik Murid</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            <!-- Total Murid -->
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-blue-200 sm:col-span-2 md:col-span-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-blue-600 uppercase tracking-wide truncate">Total
                            Murid
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-800 mt-1">
                            {{ number_format($counts['murid']['total']) }}</p>
                    </div>
                    <i class="fas fa-users text-3xl sm:text-4xl text-blue-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Aktif -->
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-green-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-green-600 uppercase tracking-wide truncate">Aktif
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-800 mt-1">
                            {{ number_format($counts['murid']['aktif']) }}</p>
                    </div>
                    <i class="fas fa-check-circle text-3xl sm:text-4xl text-green-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Alumni -->
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-purple-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-purple-600 uppercase tracking-wide truncate">
                            Alumni</p>
                        <p class="text-2xl sm:text-3xl font-bold text-purple-800 mt-1">
                            {{ number_format($counts['murid']['alumni']) }}</p>
                    </div>
                    <i class="fas fa-graduation-cap text-3xl sm:text-4xl text-purple-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Laki-laki -->
            <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-cyan-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-cyan-600 uppercase tracking-wide truncate">
                            Laki-laki
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-cyan-800 mt-1">
                            {{ number_format($counts['murid']['laki_laki']) }}</p>
                    </div>
                    <i class="fas fa-mars text-3xl sm:text-4xl text-cyan-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Murid Perempuan -->
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-rose-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-rose-600 uppercase tracking-wide truncate">
                            Perempuan
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-rose-800 mt-1">
                            {{ number_format($counts['murid']['perempuan']) }}</p>
                    </div>
                    <i class="fas fa-venus text-3xl sm:text-4xl text-rose-300 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Statistik Guru -->
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Statistik Guru</h2>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:gap-4">
            <!-- Total Guru -->
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-indigo-200 md:col-span-2">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-indigo-600 uppercase tracking-wide truncate">Total
                            Guru
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-indigo-800 mt-1">
                            {{ number_format($counts['guru']['total']) }}</p>
                    </div>
                    <i class="fas fa-chalkboard-user text-3xl sm:text-4xl text-indigo-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Aktif -->
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-emerald-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-emerald-600 uppercase tracking-wide truncate">
                            Aktif</p>
                        <p class="text-2xl sm:text-3xl font-bold text-emerald-800 mt-1">
                            {{ number_format($counts['guru']['aktif']) }}</p>
                    </div>
                    <i class="fas fa-circle-check text-3xl sm:text-4xl text-emerald-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Tidak Aktif -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-red-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-red-600 uppercase tracking-wide truncate">Tidak
                            Aktif
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-800 mt-1">
                            {{ number_format($counts['guru']['non_aktif']) }}</p>
                    </div>
                    <i class="fas fa-circle-xmark text-3xl sm:text-4xl text-red-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Laki-laki -->
            <div class="bg-gradient-to-br from-sky-50 to-sky-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-sky-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-sky-600 uppercase tracking-wide truncate">
                            Laki-laki</p>
                        <p class="text-2xl sm:text-3xl font-bold text-sky-800 mt-1">
                            {{ number_format($counts['guru']['laki_laki']) }}</p>
                    </div>
                    <i class="fas fa-mars text-3xl sm:text-4xl text-sky-300 flex-shrink-0"></i>
                </div>
            </div>

            <!-- Guru Perempuan -->
            <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-3 sm:p-4 lg:p-6 border border-pink-200">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-pink-600 uppercase tracking-wide truncate">
                            Perempuan
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold text-pink-800 mt-1">
                            {{ number_format($counts['guru']['perempuan']) }}</p>
                    </div>
                    <i class="fas fa-venus text-3xl sm:text-4xl text-pink-300 flex-shrink-0"></i>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
