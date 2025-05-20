<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Gestion de la galerie</h1>
                    <button onclick="openUploadModal()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        <i class="fas fa-plus mr-2"></i> Ajouter un média
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($media as $item)
                        <div class="card bg-white rounded-lg overflow-hidden shadow-md">                            <div class="relative aspect-w-16 aspect-h-9">
                                @if($item->type === 'image')
                                    <img src="{{ Storage::url($item->path) }}" 
                                         alt="{{ $item->title }}" 
                                         class="object-cover w-full h-full">
                                @else
                                    <video src="{{ Storage::url($item->path) }}" 
                                           class="object-cover w-full h-full" 
                                           controls></video>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->description }}</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <form action="{{ route('admin.gallery.toggle-featured', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-{{ $item->is_featured ? 'yellow' : 'gray' }}-500 hover:text-yellow-600">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
    </div>    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h2 class="text-2xl font-bold mb-4">Ajouter des médias</h2>
                <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data"
                      class="dropzone" id="mediaDropzone">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Images et Vidéos
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition-colors duration-200">
                            <input type="file" name="media[]" id="media" multiple accept="image/*,video/*"
                                   class="hidden" onchange="updateFileList(this)">
                            <label for="media" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">Glissez et déposez vos fichiers ici ou cliquez pour sélectionner</p>
                            </label>
                            <div id="fileList" class="mt-4 text-left"></div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" class="form-checkbox">
                            <span class="ml-2">Afficher sur la page d'accueil</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="closeUploadModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openUploadModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('fileList').innerHTML = '';
            document.getElementById('media').value = '';
        }

        function updateFileList(input) {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';

            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'text-sm text-gray-600 mb-1';
                    fileItem.innerHTML = `<i class="fas fa-${file.type.startsWith('image/') ? 'image' : 'video'} mr-2"></i>${file.name}`;
                    fileList.appendChild(fileItem);
                });
            }
        }

        // Gestion du drag & drop
        const dropZone = document.querySelector('.dropzone');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const fileInput = document.getElementById('media');
            
            fileInput.files = files;
            updateFileList(fileInput);
        }
    </script>
</x-app-layout>
