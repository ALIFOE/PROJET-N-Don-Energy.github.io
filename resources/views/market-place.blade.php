<x-app-layout>
    <head>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        <style>
            .product-card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .category-tab {
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .category-tab.active {
                background-color: var(--primary-color, #FFA500);
                color: var(--secondary-color, #FFFFFF);
            }
            .product-image {
                height: 200px;
                object-fit: cover;
            }
            .price-tag {
                font-size: 1.5rem;
                font-weight: bold;
                color: var(--primary-color, #FFA500);
            }
            .product-action-btn {
                background-color: var(--primary-color, #FFA500);
                color: var(--secondary-color, #FFFFFF);
                transition: all 0.3s ease;
            }
            .product-action-btn:hover {
                background-color: var(--accent-color, #0000FF);
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- <div class="mb-8">
                        <img src="{{ asset('images/banniere.jpg') }}" alt="Market Place Banner" class="w-full h-64 object-cover rounded-lg shadow-md">
                    </div> -->

                    <!-- Titre de la page -->
                    <h1 class="text-3xl font-bold mb-8 text-center section-title">Market place</h1>
                    
                    <!-- Catégories -->
                    <div class="flex flex-wrap gap-4 mb-8 justify-center">
                        <div class="category-tab active px-4 py-2 rounded-lg" data-category="all">Tous les produits</div>
                        <div class="category-tab px-4 py-2 rounded-lg" data-category="panels">Panneaux Solaires</div>
                        <div class="category-tab px-4 py-2 rounded-lg" data-category="inverters">Onduleurs</div>
                        <div class="category-tab px-4 py-2 rounded-lg" data-category="batteries">Batteries</div>
                        <div class="category-tab px-4 py-2 rounded-lg" data-category="regulators">Régulateurs</div>
                        <div class="category-tab px-4 py-2 rounded-lg" data-category="accessories">Accessoires</div>
                    </div>

                    <!-- Produits -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if($products->isEmpty())
                            <div class="col-span-full text-center py-8">
                                <p class="text-gray-500 text-lg">Aucun produit disponible pour le moment.</p>
                            </div>
                        @else
                            @foreach($products as $product)
                                <div class="product-card" data-category="{{ $product->categorie }}">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->nom }}" class="w-full h-48 object-cover rounded-t-lg">
                                    @else
                                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->nom }}" class="w-full h-48 object-cover rounded-t-lg">
                                    @endif
                                      <div class="p-4">
                                        <h3 class="text-xl font-semibold mb-2">{{ $product->nom }}</h3>
                                        <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                                <div class="flex justify-between items-center">
                                            <span class="text-2xl font-bold">{{ $product->formatted_price }}</span>
                                            <a href="{{ route('checkout', $product->id) }}" class="product-action-btn px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                                Commander
                                            </a>
                            </div>
                        </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryTabs = document.querySelectorAll('.category-tab');
            const products = document.querySelectorAll('.product-card');

            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Retirer la classe active de tous les onglets
                    categoryTabs.forEach(t => t.classList.remove('active'));
                    // Ajouter la classe active à l'onglet cliqué
                    this.classList.add('active');

                    const category = this.dataset.category;
                    
                    // Afficher/masquer les produits selon la catégorie
                    products.forEach(product => {
                        if (category === 'all' || product.dataset.category === category) {
                            product.style.display = 'block';
                        } else {
                            product.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>