<x-app-layout>
    <head>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
            }
            .form-card {
                background: white;
                border-radius: 12px;
                padding: 2.5rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                max-width: 800px;
                margin: 0 auto;
            }
            .form-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .btn-submit {
                background-color: var(--primary-color, #FFA500);
                color: var(--secondary-color, #FFFFFF);
                padding: 0.75rem 2rem;
                border-radius: 8px;
                transition: all 0.3s ease;
                font-weight: 600;
                width: 100%;
                max-width: 300px;
            }
            .btn-submit:hover {
                background-color: var(--accent-color, #0000FF);
                transform: translateY(-2px);
            }
            .form-section {
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
            }
            .form-section-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--dark-color, #000000);
                margin-bottom: 1rem;
            }
            .input-group {
                margin-bottom: 1.5rem;
            }
            .file-input-wrapper {
                border: 2px dashed #e5e7eb;
                padding: 1rem;
                border-radius: 8px;
                text-align: center;
                transition: all 0.3s ease;
            }
            .file-input-wrapper:hover {
                border-color: var(--primary-color, #FFA500);
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Fermer</title>
                            <path d="M14.348 5.652a1 1 0 10-1.414-1.414L10 7.586 7.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z"/>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="form-card">
                <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Inscription à la Formation</h1>

                <form action="{{ route('formation.inscription') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="form-section">
                        <h2 class="form-section-title">Informations Personnelles</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="input-group">
                                <label for="nom" class="block text-gray-700 font-medium mb-2">Nom complet *</label>
                                <input type="text" id="nom" name="nom" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                    required minlength="2" 
                                    placeholder="Entrez votre nom complet"
                                    value="{{ old('nom') }}">
                                @error('nom')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Adresse e-mail *</label>
                                <input type="email" id="email" name="email" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                    required 
                                    placeholder="exemple@email.com"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="telephone" class="block text-gray-700 font-medium mb-2">Numéro de téléphone *</label>
                                <input type="tel" id="telephone" name="telephone" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                    required 
                                    pattern="[0-9]{8,15}"
                                    placeholder="Ex: 0123456789"
                                    value="{{ old('telephone') }}">
                                @error('telephone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="formation" class="block text-gray-700 font-medium mb-2">Formation souhaitée *</label>
                                <select name="formation" id="formation" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                    <option value="">Sélectionnez une formation</option>                                    @foreach ($formations as $formation)
                                        <option value="{{ $formation->id }}" {{ old('formation') == $formation->id ? 'selected' : '' }}>
                                            {{ $formation->titre }} - {{ \Carbon\Carbon::parse($formation->date_debut)->diffInMonths($formation->date_fin) }} Mois
                                        </option>
                                    @endforeach
                                </select>
                                @error('formation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="form-section-title">Documents Requis</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="input-group">
                                <label for="acte_naissance_path" class="block text-gray-700 font-medium mb-2">
                                    Acte de naissance *
                                    <span class="text-sm text-gray-500">(PDF, JPG, PNG)</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="acte_naissance_path" name="acte_naissance_path" 
                                        class="w-full" 
                                        required 
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                @error('acte_naissance_path')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="cni_path" class="block text-gray-700 font-medium mb-2">
                                    CNI ou n'importe carte d'identité *
                                    <span class="text-sm text-gray-500">(PDF, JPG, PNG)</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="cni_path" name="cni_path" 
                                        class="w-full" 
                                        required 
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                @error('cni_path')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="diplome_path" class="block text-gray-700 font-medium mb-2">
                                    Diplôme *
                                    <span class="text-sm text-gray-500">(PDF, JPG, PNG)</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="diplome_path" name="diplome_path" 
                                        class="w-full" 
                                        required 
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                @error('diplome_path')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-group">
                                <label for="autres_documents_paths" class="block text-gray-700 font-medium mb-2">
                                    Autres documents
                                    <span class="text-sm text-gray-500">(Optionnel)</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="autres_documents_paths" name="autres_documents_paths[]" 
                                        class="w-full" 
                                        multiple 
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                @error('autres_documents_paths')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="form-section-title">Message</h2>
                        <div class="input-group">
                            <label for="message" class="block text-gray-700 font-medium mb-2">
                                Questions ou commentaires
                                <span class="text-sm text-gray-500">(Optionnel)</span>
                            </label>
                            <textarea id="message" name="message" rows="4" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Avez-vous des questions particulières concernant la formation ?">{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <button type="submit" class="btn-submit">
                            Soumettre ma candidature
                        </button>
                        <p class="text-sm text-gray-500 mt-2">* Champs obligatoires</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Amélioration de l'expérience utilisateur pour les uploads de fichiers
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const wrapper = this.closest('.file-input-wrapper');
                const existingMessage = wrapper.querySelector('.file-message');
                
                // Supprime l'ancien message s'il existe
                if (existingMessage) {
                    existingMessage.remove();
                }

                if (this.files.length > 0) {
                    const messageElement = document.createElement('p');
                    messageElement.className = 'mt-2 text-sm text-gray-600 file-message';
                    
                    if (this.multiple) {
                        messageElement.textContent = `${this.files.length} fichier(s) sélectionné(s)`;
                    } else {
                        messageElement.textContent = this.files[0].name;
                    }
                    
                    wrapper.appendChild(messageElement);
                    wrapper.style.borderColor = '#2563eb';
                }
            });
        });
    </script>
</x-app-layout>
