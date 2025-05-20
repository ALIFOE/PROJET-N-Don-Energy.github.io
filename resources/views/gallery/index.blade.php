<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-8">Galerie média</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($media as $item)
                        <div class="card bg-white rounded-lg overflow-hidden shadow-md">
                            <div class="relative aspect-w-16 aspect-h-9">                    @if($item->type === 'image')
                                    <img src="{{ Storage::url($item->path) }}" 
                                         alt="{{ $item->title }}" 
                                         class="object-cover w-full h-full cursor-pointer media-item"
                                         data-media-src="{{ Storage::url($item->path) }}"
                                         data-title="{{ $item->title }}"
                                         data-description="{{ $item->description }}"
                                         data-type="image">
                                @else
                                    <video 
                                           class="object-cover w-full h-full cursor-pointer media-item"
                                           data-media-src="{{ Storage::url($item->path) }}"
                                           data-title="{{ $item->title }}"
                                           data-description="{{ $item->description }}"
                                           data-type="video"
                                           preload="metadata">
                                        <source src="{{ Storage::url($item->path) }}" type="video/mp4">
                                        <source src="{{ Storage::url($item->path) }}" type="video/webm">
                                        <source src="{{ Storage::url($item->path) }}" type="video/ogg">
                                        <source src="{{ Storage::url($item->path) }}" type="video/avi">
                                        <source src="{{ Storage::url($item->path) }}" type="video/wav">
                                        Votre navigateur ne prend pas en charge la lecture de vidéos.
                                    </video>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">Aucun média disponible pour le moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $media->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="mediaModal" class="fixed inset-0 bg-black bg-opacity-95 hidden z-50" tabindex="-1">
        <div class="flex items-center justify-center h-screen p-4 w-full modal-overlay">
            <div class="relative w-full h-full flex flex-col items-center justify-center modal-container">
                <!-- Bouton de fermeture amélioré -->
                <button type="button" class="absolute top-2 right-2 bg-black bg-opacity-50 rounded-full p-2 text-white hover:text-red-500 hover:bg-white transition-all duration-200 z-50 close-modal">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="relative w-full h-full flex flex-col items-center modal-content">                    <div class="w-full h-[85vh] flex items-center justify-center overflow-hidden">
                        <img id="modalImage" src="" alt="" class="hidden max-w-[95%] max-h-[85vh] w-auto h-auto object-contain">
                        <video id="modalVideo" class="hidden max-w-[95%] max-h-[85vh] w-auto h-auto" controls controlsList="nodownload" playsinline>
                            <source src="" type="video/mp4">
                            <source src="" type="video/webm">
                            <source src="" type="video/ogg">
                            Votre navigateur ne prend pas en charge la lecture de vidéos.
                        </video>
                    </div>
                    <div class="bg-white bg-opacity-90 p-4 mt-4 rounded-lg w-full max-w-4xl mx-auto">
                        <h3 id="modalTitle" class="text-2xl font-semibold text-gray-800"></h3>
                        <p id="modalDescription" class="text-gray-600 mt-2"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .close-modal {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .close-modal:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mediaItems = document.querySelectorAll('.media-item');
            const modal = document.getElementById('mediaModal');
            const modalImage = document.getElementById('modalImage');
            const modalVideo = document.getElementById('modalVideo');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');

            // Gestionnaire de clic pour la modale
            modal.addEventListener('click', function(e) {
                // Vérifie si le clic est en dehors du contenu de la modale
                if (e.target.classList.contains('modal-overlay') || 
                    e.target.classList.contains('modal-container') || 
                    e.target === modal) {
                    closeModal();
                }
            });

            // Fermeture avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            mediaItems.forEach(function(item) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    openModal(this);
                });
            });            function openModal(element) {
                const mediaSrc = element.dataset.mediaSrc;
                const title = element.dataset.title;
                const description = element.dataset.description;
                const type = element.dataset.type;

                // Réinitialiser complètement l'état
                resetModal();
                
                if (type === 'image') {
                    modalImage.src = mediaSrc;
                    // Attendre que l'image soit chargée
                    modalImage.onload = function() {
                        modalImage.classList.remove('hidden');
                        adjustImageSize(modalImage);
                    };
                } else {
                    // Mise à jour des sources vidéo
                    const sources = modalVideo.getElementsByTagName('source');
                    sources.forEach(source => {
                        source.src = mediaSrc;
                    });
                    modalVideo.load(); // Recharger la vidéo avec les nouvelles sources
                    modalVideo.classList.remove('hidden');
                    modalVideo.play().catch(function(error) {
                        console.log("Erreur de lecture vidéo:", error);
                    });
                }
                
                modalTitle.textContent = title;
                modalDescription.textContent = description;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                // Animation de fermeture
                modal.style.opacity = '0';
                modal.style.transition = 'opacity 0.2s ease-out';
                
                setTimeout(() => {
                    resetModal();
                    modal.classList.add('hidden');
                    modal.style.opacity = '1';
                    document.body.style.overflow = 'auto';
                }, 200);
            }

            function resetModal() {
                // Réinitialiser l'image
                modalImage.src = '';
                modalImage.style.width = '';
                modalImage.style.height = '';
                modalImage.classList.add('hidden');
                
                // Réinitialiser la vidéo
                if (modalVideo) {
                    modalVideo.pause();
                    modalVideo.currentTime = 0;
                    modalVideo.src = '';
                    modalVideo.classList.add('hidden');
                }
                
                // Réinitialiser les textes
                modalTitle.textContent = '';
                modalDescription.textContent = '';
            }

            function adjustImageSize(img) {
                const windowWidth = window.innerWidth;
                const windowHeight = window.innerHeight;
                const imageRatio = img.naturalWidth / img.naturalHeight;
                const windowRatio = windowWidth / windowHeight;

                if (imageRatio > windowRatio) {
                    // Image plus large que haute par rapport à la fenêtre
                    img.style.width = '95%';
                    img.style.height = 'auto';
                } else {
                    // Image plus haute que large par rapport à la fenêtre
                    img.style.height = '85vh';
                    img.style.width = 'auto';
                }
            }

            // Gérer le redimensionnement de la fenêtre
            window.addEventListener('resize', function() {
                if (!modalImage.classList.contains('hidden')) {
                    adjustImageSize(modalImage);
                }
            });

            // Gérer la fermeture
            const closeButtons = document.querySelectorAll('.close-modal');
            closeButtons.forEach(button => {
                button.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>
