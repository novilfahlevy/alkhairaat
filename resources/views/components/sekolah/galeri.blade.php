@props(['sekolah'])

<!-- Galeri Sekolah Section -->
@if ($sekolah->galeri->count() > 0)
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-900">
        <h2 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">Galeri Sekolah</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($sekolah->galeri as $galeri)
                <div class="group relative overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
                    <img src="{{ asset('storage/' . $galeri->image_path) }}" alt="Galeri Sekolah"
                        class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-110">

                    <!-- Overlay -->
                    <div
                        class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/40 flex items-center justify-center">
                        <button type="button" onclick="openLightbox('{{ asset('storage/' . $galeri->image_path) }}')"
                            class="opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@push('modals')
    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4 hidden z-999999">
        <div class="max-w-4xl w-full">
            <div class="relative max-h-[90vh] flex flex-col items-center justify-center">
                <div class="overflow-y-auto max-h-screen w-full flex items-center justify-center">
                    <img id="lightbox-image" src="" alt="Galeri Sekolah - Full Size"
                        class="w-full h-auto rounded-lg object-contain">
                </div>
                <button type="button" onclick="closeLightbox()"
                    class="absolute top-4 right-4 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <p id="lightbox-caption" class="mt-4 text-white text-center text-sm hidden"></p>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        function openLightbox(imageSrc, caption = '') {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxCaption = document.getElementById('lightbox-caption');

            lightboxImage.src = imageSrc;
            lightboxCaption.textContent = caption;

            if (caption) {
                lightboxCaption.classList.remove('hidden');
            } else {
                lightboxCaption.classList.add('hidden');
            }

            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when lightbox is open
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close lightbox when clicking outside the image
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Close lightbox with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
@endpush
