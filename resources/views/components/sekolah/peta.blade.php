@props(['alamat', 'sekolah'])

<!-- Map Section -->
@if ($alamat->koordinat_x && $alamat->koordinat_y)
    <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Peta Lokasi</h3>
        <div id="map-container" class="rounded-lg overflow-hidden shadow-md h-96 bg-gray-100 dark:bg-gray-800"></div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const koordinatX = {{ $alamat->koordinat_x }};
                const koordinatY = {{ $alamat->koordinat_y }};
                const sekolahNama = '{{ $sekolah->nama }}';

                // Initialize map
                const map = L.map('map-container').setView([koordinatX, koordinatY], 15);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                // Add marker at school location
                const marker = L.marker([koordinatX, koordinatY]).addTo(map);
                marker.bindPopup(`<div class="font-semibold text-gray-800">${sekolahNama}</div><div class="text-sm text-gray-600">Lat: ${koordinatX}, Lon: ${koordinatY}</div>`);
                marker.openPopup();
            });
        </script>
    @endpush
@endif
