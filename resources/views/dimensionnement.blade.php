<x-app-layout>
    <head>
        <style>
            .form-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 2rem;
            }
            .form-section {
                background: white;
                border-radius: 12px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .form-title {
                color: #1e88e5;
                font-weight: 600;
                margin-bottom: 1.5rem;
                font-size: 1.5rem;
            }
            .form-group {
                margin-bottom: 1.5rem;
            }
            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                color: #4b5563;
                font-weight: 500;
            }
            .form-input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                transition: border-color 0.3s;
            }
            .form-input:focus {
                border-color: #1e88e5;
                outline: none;
            }
            .checkbox-group {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
            }
            .checkbox-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .submit-btn {
                background-color: #1e88e5;
                color: white;
                padding: 0.75rem 2rem;
                border-radius: 6px;
                border: none;
                cursor: pointer;
                font-weight: 500;
                transition: background-color 0.3s;
            }
            .submit-btn:hover {
                background-color: #0d47a1;
            }
            .info-text {
                color: #6b7280;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
        </style>
    </head>

    <div class="py-12">
        <div class="form-container">
            <h1 class="text-3xl font-bold mb-8 text-center text-blue-600">Formulaire de Dimensionnement Solaire</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('dimensionnement.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Informations générales -->
                <div class="form-section">
                    <h2 class="form-title">Informations générales</h2>
                    <div class="form-group">
                        <label class="form-label">Nom et prénom</label>
                        <input type="text" name="nom" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse du projet</label>
                        <input type="text" name="adresse" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Numéro de téléphone</label>
                        <input type="tel" name="telephone" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>
                </div>

                <!-- Consommation électrique -->
                <div class="form-section">
                    <h2 class="form-title">Consommation électrique</h2>
                    <div class="form-group">
                        <label class="form-label">Montant annuel de la facture électrique (€)</label>
                        <input type="number" name="facture_annuelle" class="form-input" step="0.01">
                        <p class="info-text">Si vous avez votre facture annuelle</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fournisseur d'électricité</label>
                        <input type="text" name="fournisseur" class="form-input">
                    </div>
                </div>

                <!-- Estimation par le nombre d'appareils -->
                <div class="form-section">
                    <h2 class="form-title">Estimation par le nombre d'appareils</h2>
                    <div class="form-group">
                        <label class="form-label">Nombre de personnes dans le foyer</label>
                        <input type="number" name="nb_personnes" class="form-input" min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type de logement</label>
                        <select name="type_logement" class="form-input">
                            <option value="maison">Maison</option>
                            <option value="appartement">Appartement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Équipements énergivores</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="checkbox" name="equipements[]" value="chauffage">
                                Chauffage électrique
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="equipements[]" value="ballon">
                                Ballon eau chaude électrique
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="equipements[]" value="climatisation">
                                Climatisation
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Surface et orientation -->
                <div class="form-section">
                    <h2 class="form-title">Surface et orientation</h2>
                    <div class="form-group">
                        <label class="form-label">Surface de toiture disponible (m²)</label>
                        <input type="number" name="surface_toiture" class="form-input" step="0.1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Orientation du toit</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="radio" name="orientation" value="sud">
                                Sud
                            </label>
                            <label class="checkbox-item">
                                <input type="radio" name="orientation" value="sud-est">
                                Sud-Est
                            </label>
                            <label class="checkbox-item">
                                <input type="radio" name="orientation" value="sud-ouest">
                                Sud-Ouest
                            </label>
                            <label class="checkbox-item">
                                <input type="radio" name="orientation" value="autre">
                                Autre
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Objectifs personnels -->
                <div class="form-section">
                    <h2 class="form-title">Objectifs personnels</h2>
                    <div class="form-group">
                        <label class="form-label">Objectif de production</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="checkbox" name="objectifs[]" value="reduction">
                                Réduction facture
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="objectifs[]" value="autoproduction">
                                Autoproduction
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="objectifs[]" value="revente">
                                Revente d'électricité
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="objectifs[]" value="environnement">
                                Impact environnemental
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Contraintes et préférences -->
                <div class="form-section">
                    <h2 class="form-title">Contraintes et préférences</h2>
                    <div class="form-group">
                        <label class="form-label">Budget approximatif (€)</label>
                        <input type="number" name="budget" class="form-input" step="1000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type d'installation souhaitée</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item">
                                <input type="radio" name="type_installation" value="toiture">
                                Toiture
                            </label>
                            <label class="checkbox-item">
                                <input type="radio" name="type_installation" value="sol">
                                Sol
                            </label>
                            <label class="checkbox-item">
                                <input type="radio" name="type_installation" value="ombriere">
                                Ombrière
                            </label>
                            <label class="checkbox-item">
                                <input type="radio" name="type_installation" value="autre">
                                Autre
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="submit-btn">Envoyer ma demande de dimensionnement</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 