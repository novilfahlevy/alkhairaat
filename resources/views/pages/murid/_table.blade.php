{{-- A. TAMPILAN DESKTOP (TABLE) --}}
<div
  class="hidden md:block rounded-lg overflow-hidden shadow-xs bg-white dark:bg-black border border-gray-200 dark:border-gray-700">
  {{-- pb-24 dipertahankan agar dropdown baris terakhir tidak terpotong --}}
  <div class="w-full overflow-x-auto min-h-75">
    <table class="w-full whitespace-no-wrap">
      <thead>
        <tr
          class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-transparent">
          <th class="px-4 py-3">NISN</th>
          <th class="px-4 py-3">Nama Lengkap</th>
          <th class="px-4 py-3">L/P</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">No. HP</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y dark:divide-gray-700 rounded-lg dark:bg-black">
        @forelse ($murid as $index => $item)
          <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-4 py-3 text-sm">
              {{ $item->nisn }}
            </td>
            <td class="px-4 py-3 flex flex-col text-base font-semibold">
              <span>
                {{ $item->nama }}
              </span>
              <span class="text-sm font-light italic">
                {{ $item->nik ? $item->nik : '-' }}
              </span>
            </td>
            <td class="px-4 py-3 text-sm">
              {{ $item->jenis_kelamin }}
            </td>
            <td class="px-4 py-3 text-xs">
              @if ($item->status_alumni == 0)
                <span
                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                  Aktif
                </span>
              @else
                <span
                  class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                  Alumni
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-sm">
              {{ $item->kontak_wa_hp ?? '-' }}
            </td>

            {{-- DROPDOWN AKSI DESKTOP --}}
            <td class="px-4 py-3 text-sm text-right relative">
              <div x-data="{ open: false }" class="relative inline-block text-left">

                {{-- Tombol Trigger --}}
                <button @click="open = !open"
                  class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none bg-gray-100 dark:bg-black rounded-full w-8 h-8 flex items-center justify-center transition">
                  <i class="fas fa-ellipsis-v text-xs"></i>
                </button>

                {{-- Menu Dropdown --}}
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="transform opacity-0 scale-95"
                  x-transition:enter-end="transform opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="transform opacity-100 scale-100"
                  x-transition:leave-end="transform opacity-0 scale-95"
                  class="absolute right-0 z-50 mt-2 w-32 origin-top-right bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700 rounded-md shadow-lg ring-opacity-5 focus:outline-none"
                  style="display: none;">

                  <div class="py-1">
                    {{-- Edit --}}
                    <a href="{{ route('murid.edit', $item->id) }}"
                      class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 w-full text-left">
                      <i class="fas fa-edit w-4 mr-2 text-center"></i> Edit
                    </a>

                    {{-- Delete --}}
                    <form action="{{ route('murid.destroy', $item->id) }}" method="POST" class="w-full"
                      onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="group flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-700 w-full text-left">
                        <i class="fas fa-trash-alt w-4 mr-2 text-center"></i> Hapus
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">
              Data tidak ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- B. TAMPILAN MOBILE/TABLET (GRID CARD) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
  @forelse ($murid as $item)
    {{-- PERBAIKAN: Pindahkan x-data ke wrapper Card --}}
    {{-- Tambahkan :class z-30 agar card ini naik ke atas saat dropdown terbuka --}}
    <div x-data="{ open: false }" :class="{ 'z-30 ring-2 ring-blue-400': open, 'z-0': !open }"
      class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-col justify-between relative transition-all duration-200">

      {{-- Header Card & Dropdown --}}
      <div class="flex justify-between items-start mb-2">
        <div class="pr-8">
          <h3 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1">
            {{ $item->nama }}
          </h3>
          <p class="text-xs text-gray-500 dark:text-gray-400">NISN: {{ $item->nisn }}</p>
        </div>

        {{-- Trigger Button --}}
        <div class="absolute top-4 right-4">
          <button @click="open = !open"
            class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none p-1 bg-transparent rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <i class="fas fa-ellipsis-v text-lg"></i>
          </button>

          {{-- Dropdown Menu Mobile --}}
          <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            class="absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-2xl z-50"
            style="display: none;">

            <div class="py-1">
              <a href="{{ route('murid.edit', $item->id) }}"
                class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600">
                <i class="fas fa-edit mr-2"></i> Edit Data
              </a>

              <form action="{{ route('murid.destroy', $item->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="block w-full text-left px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                  <i class="fas fa-trash-alt mr-2"></i> Hapus
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="space-y-2 mb-4">
        <div>
          @if ($item->status_alumni == 0)
            <span
              class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
              Aktif
            </span>
          @else
            <span
              class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
              Alumni
            </span>
          @endif
        </div>

        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
          <i class="fas fa-venus-mars w-5 text-center mr-2 text-gray-400"></i>
          <span>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
        </div>
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
          <i class="fas fa-phone w-5 text-center mr-2 text-gray-400"></i>
          <span>{{ $item->kontak_wa_hp ?? '-' }}</span>
        </div>
      </div>

    </div>
  @empty
    <div
      class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
      Data tidak ditemukan.
    </div>
  @endforelse
</div>

<div class="mt-4">
  {{ $murid->links() }}
</div>
