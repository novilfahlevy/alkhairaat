<div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
  <table class="min-w-full leading-normal">
    <thead>
      <tr>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
          NISN
        </th>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
          Nama Lengkap
        </th>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
          L/P
        </th>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
          Tempat, Tgl Lahir
        </th>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
          Status
        </th>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
          Kontak
        </th>
        <th
          class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
          Aksi
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse($murids as $murid)
        <tr>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <p class="text-gray-900 whitespace-no-wrap font-medium">{{ $murid->nisn }}</p>
          </td>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <div class="flex items-center">
              <div>
                <p class="text-gray-900 whitespace-no-wrap font-semibold">
                  {{ $murid->nama }}
                </p>
                <p class="text-gray-500 text-xs">NIK: {{ $murid->nik ?? '-' }}</p>
              </div>
            </div>
          </td>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <span
              class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $murid->jenis_kelamin == 'L' ? 'text-blue-900' : 'text-pink-900' }}">
              <span aria-hidden
                class="absolute inset-0 {{ $murid->jenis_kelamin == 'L' ? 'bg-blue-200' : 'bg-pink-200' }} opacity-50 rounded-full"></span>
              <span class="relative">{{ $murid->jenis_kelamin == 'L' ? 'L' : 'P' }}</span>
            </span>
          </td>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <p class="text-gray-900 whitespace-no-wrap">{{ $murid->tempat_lahir }}</p>
            <p class="text-gray-500 text-xs">
              {{ $murid->tanggal_lahir ? $murid->tanggal_lahir->format('d M Y') : '-' }}
            </p>
          </td>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            @if ($murid->status_alumni)
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                Alumni
              </span>
            @else
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Aktif
              </span>
            @endif
          </td>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <div class="text-gray-900 whitespace-no-wrap text-sm">
              @if ($murid->kontak_wa_hp)
                <div class="flex items-center gap-2">
                  <i class="fab fa-whatsapp text-green-600 text-lg"></i>
                  <span>{{ $murid->kontak_wa_hp }}</span>
                </div>
              @endif
            </div>
            <p class="text-gray-500 text-xs truncate max-w-37.5" title="{{ $murid->kontak_email }}">
              {{ $murid->kontak_email ?? '-' }}
            </p>
          </td>
          <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
            <div class="flex item-center justify-center space-x-2">
              <a href="{{ route('murid.edit', $murid->id) }}"
                class="w-8 h-8 flex items-center justify-center bg-yellow-100 rounded hover:bg-yellow-200 text-yellow-600 transition"
                title="Edit">
                <i class="fas fa-edit"></i>
              </a>

              <form action="{{ route('murid.destroy', $murid->id) }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="w-8 h-8 flex items-center justify-center bg-red-100 rounded hover:bg-red-200 text-red-600 transition"
                  title="Hapus">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
            Data tidak ditemukan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
    {{ $murids->links() }}
  </div>
</div>
