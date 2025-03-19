<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Électronique 
            [
                'name' => 'Smartphone XYZ',
                'description' => 'Le dernier smartphone avec des fonctionnalités avancées',
                'price' => 799.99,
                'stock' => 50,
                'categories' => [1, 4] // Électronique et Maison
            ],
            [
                'name' => 'Ordinateur portable Pro',
                'description' => 'Ordinateur portable puissant pour les professionnels',
                'price' => 1299.99,
                'stock' => 30,
                'categories' => [1] // Électronique
            ],

            // Vêtements
            [
                'name' => 'T-shirt Coton',
                'description' => 'T-shirt en coton confortable',
                'price' => 19.99,
                'stock' => 100,
                'categories' => [2] // Vêtements
            ],
            [
                'name' => 'Jean Slim',
                'description' => 'Jean slim élégant et durable',
                'price' => 49.99,
                'stock' => 75,
                'categories' => [2] // Vêtements
            ],

            // Livres
            [
                'name' => 'Guide de programmation Laravel',
                'description' => 'Apprendre à développer avec Laravel',
                'price' => 34.99,
                'stock' => 25,
                'categories' => [3, 1] // Livres et Électronique (ebook)
            ],
            [
                'name' => 'Roman Bestseller',
                'description' => 'Le dernier roman du célèbre auteur',
                'price' => 24.99,
                'stock' => 40,
                'categories' => [3] // Livres
            ],

            // Maison
            [
                'name' => 'Ensemble de cuisine',
                'description' => 'Kit complet d\'ustensiles de cuisine',
                'price' => 89.99,
                'stock' => 15,
                'categories' => [4] // Maison
            ],
            [
                'name' => 'Lampe de bureau LED',
                'description' => 'Lampe économique avec luminosité ajustable',
                'price' => 39.99,
                'stock' => 60,
                'categories' => [4, 1] // Maison et Électronique
            ],
        ];

        foreach ($products as $productData) {
            $categoryIds = $productData['categories'];
            unset($productData['categories']);

            $product = Product::create($productData);
            $product->categories()->attach($categoryIds);
        }
    }
}
