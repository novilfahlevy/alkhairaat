{{-- Notification Dropdown Component --}}
<div class="relative" x-data="{
    dropdownOpen: false,
    notifying: true,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
        this.notifying = false;
        if (this.dropdownOpen) {
            this.$nextTick(() => {
                this.positionDropdown();
            });
        }
    },
    positionDropdown() {
        const button = this.$refs.notificationButton;
        const dropdown = this.$refs.dropdown;
        if (button && dropdown) {
            const rect = button.getBoundingClientRect();
            const isMobile = window.innerWidth < 768; // md breakpoint
            
            dropdown.style.position = 'fixed';
            dropdown.style.zIndex = '99999';
            
            if (isMobile) {
                // Mobile: full width with margin, positioned near top
                dropdown.style.top = (rect.bottom + 8) + 'px';
                dropdown.style.left = '16px';
                dropdown.style.right = '16px';
                dropdown.style.width = 'auto';
                dropdown.style.maxHeight = '70vh';
            } else {
                // Desktop: positioned relative to button
                dropdown.style.top = (rect.bottom + 8) + 'px';
                dropdown.style.right = (window.innerWidth - rect.right) + 'px';
                dropdown.style.left = 'auto';
                dropdown.style.width = '380px';
                dropdown.style.maxHeight = '480px';
            }
        }
    },
    closeDropdown() {
        this.dropdownOpen = false;
    },
    handleItemClick() {
        console.log('Notification item clicked');
        this.closeDropdown();
    },
    handleViewAllClick() {
        console.log('View All Notifications clicked');
        this.closeDropdown();
    }
}" @click.away="closeDropdown()" @resize.window="positionDropdown()">
    <!-- Notification Button -->
    <button
        x-ref="notificationButton"
        class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
        @click="toggleDropdown()"
        type="button"
    >
        <!-- Notification Badge -->
        <span
            x-show="notifying"
            class="absolute right-0 top-0.5 z-1 h-2 w-2 rounded-full bg-orange-400"
        >
            <span
                class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 -z-1 animate-ping"
            ></span>
        </span>

        <!-- Bell Icon -->
        <svg
            class="fill-current"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                fill=""
            />
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-ref="dropdown"
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="flex flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-2xl dark:border-gray-800 dark:bg-gray-900"
        style="display: none; position: fixed; z-index: 99999;"
    >
        <!-- Dropdown Header -->
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-800">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">Notifikasi</h5>

            <button @click="closeDropdown()" class="text-gray-500 dark:text-gray-400" type="button">
                <svg
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill=""
                    />
                </svg>
            </button>
        </div>

        <!-- Notification List -->
        <ul class="flex flex-col flex-1 overflow-y-auto custom-scrollbar min-h-0">
            @php
                $notifications = [
                    [
                        'id' => 1,
                        'title' => 'Data Murid Baru',
                        'message' => 'SDI Alkhairaat Palu menambahkan 25 murid baru untuk tahun ajaran 2025/2026',
                        'type' => 'Murid',
                        'time' => '5 menit lalu',
                        'icon' => 'user-plus',
                        'color' => 'bg-blue-500',
                    ],
                    [
                        'id' => 2,
                        'title' => 'Update Data Alumni',
                        'message' => 'MA Alkhairaat Pusat memperbarui data 15 alumni angkatan 2020',
                        'type' => 'Alumni', 
                        'time' => '15 menit lalu',
                        'icon' => 'academic-cap',
                        'color' => 'bg-green-500',
                    ],
                    [
                        'id' => 3,
                        'title' => 'Registrasi Sekolah',
                        'message' => 'Pesantren Alkhairaat Kabonena mendaftar sebagai sekolah baru',
                        'type' => 'Sekolah',
                        'time' => '30 menit lalu',
                        'icon' => 'building',
                        'color' => 'bg-purple-500',
                    ],
                    [
                        'id' => 4,
                        'title' => 'Laporan Bulanan',
                        'message' => 'Laporan statistik murid dan alumni bulan November 2025 tersedia',
                        'type' => 'Laporan',
                        'time' => '1 jam lalu',
                        'icon' => 'chart-bar',
                        'color' => 'bg-orange-500',
                    ],
                    [
                        'id' => 5,
                        'title' => 'Sinkronisasi Data',
                        'message' => 'Proses sinkronisasi data dari 12 sekolah telah selesai',
                        'type' => 'Sistem',
                        'time' => '2 jam lalu',
                        'icon' => 'refresh',
                        'color' => 'bg-indigo-500',
                    ],
                ];
            @endphp

            @foreach ($notifications as $notification)
                <li @click="handleItemClick()">
                    <a
                        class="flex gap-3 rounded-lg border-b border-gray-100 p-3 py-4 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-white/5"
                        href="#"
                    >
                        <span class="flex items-center justify-center w-10 h-10 rounded-full {{ $notification['color'] }} text-white flex-shrink-0">
                            @if($notification['icon'] === 'user-plus')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                            @elseif($notification['icon'] === 'academic-cap')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                            @elseif($notification['icon'] === 'building')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notification['icon'] === 'chart-bar')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </span>

                        <span class="block flex-1 min-w-0">
                            <span class="mb-1 block text-sm font-medium text-gray-800 dark:text-white/90 leading-tight">
                                {{ $notification['title'] }}
                            </span>
                            <span class="mb-2 block text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ $notification['message'] }}
                            </span>

                            <span class="flex items-center gap-2 text-gray-500 text-xs dark:text-gray-400">
                                <span>{{ $notification['type'] }}</span>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                <span>{{ $notification['time'] }}</span>
                            </span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- View All Button -->
        <a
            href="#"
            class="mt-3 flex justify-center rounded-lg border border-gray-300 bg-white p-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
            @click.prevent="handleViewAllClick()"
        >
            Lihat Semua Notifikasi
        </a>
    </div>
    <!-- Dropdown End -->
</div>
