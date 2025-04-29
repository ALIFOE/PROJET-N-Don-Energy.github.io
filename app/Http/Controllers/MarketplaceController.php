<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    public function index()
    {
        try {
            // Vérifier si la table products existe
            if (!\Schema::hasTable('products')) {
                Log::error('La table products n\'existe pas dans la base de données');
                return view('market-place', ['products' => collect()]);
            }

            $products = Product::orderBy('created_at', 'desc')->get();
            
            // Log pour déboguer
            Log::info('Nombre de produits récupérés : ' . $products->count());
            foreach ($products as $product) {
                Log::info('Produit : ' . $product->nom . ' - Catégorie : ' . $product->categorie);
            }
            
            if ($products->isEmpty()) {
                Log::warning('Aucun produit trouvé dans la base de données');
                // Ajouter des produits de test si la base est vide
                $this->seedTestProducts();
                $products = Product::orderBy('created_at', 'desc')->get();
            }
            
            return view('market-place', compact('products'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des produits : ' . $e->getMessage());
            return view('market-place', ['products' => collect()]);
        }
    }

    private function seedTestProducts()
    {
        $products = [
            [
                'nom' => 'Panneau Solaire 400W Monocristallin',
                'description' => 'Haute efficacité, idéal pour les installations résidentielles et commerciales.',
                'prix' => 150000,
                'categorie' => 'panels',
                'image' => 'images/panneaux.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Onduleur Hybride 5kW',
                'description' => 'Compatible avec batterie et réseau, idéal pour les maisons moyennes.',
                'prix' => 300000,
                'categorie' => 'inverters',
                'image' => 'images/onduleur1.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Batterie Lithium 5kWh',
                'description' => 'Haute densité énergétique, longue durée de vie.',
                'prix' => 400000,
                'categorie' => 'batteries',
                'image' => 'images/products/batterie-lithium.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Régulateur MPPT 60A',
                'description' => 'Suivi du point de puissance maximum, haute efficacité.',
                'prix' => 80000,
                'categorie' => 'regulators',
                'image' => 'images/products/regulateur-mppt.jpg',
                'en_stock' => true,
            ],
            [
                'nom' => 'Kit de Montage Toiture',
                'description' => 'Système complet pour installation sur toit incliné.',
                'prix' => 50000,
                'categorie' => 'accessories',
                'image' => 'images/products/kit-montage.jpg',
                'en_stock' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 