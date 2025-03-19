<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Électronique',
                'description' => 'Produits électroniques comme smartphones, ordinateurs, etc.'
            ],
            [
                'name' => 'Vêtements',
                'description' => 'Vêtements pour hommes, femmes et enfants'
            ],
            [
                'name' => 'Livres',
                'description' => 'Livres, magazines et publications'
            ],
            [
                'name' => 'Maison',
                'description' => 'Articles pour la maison et le jardin'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
