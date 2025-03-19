<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function ($product) {
            // Attach 1-3 random categories to each product
            $categoryIds = Category::inRandomOrder()->take($this->faker->numberBetween(1, 3))->pluck('id');

            if ($categoryIds->isEmpty()) {
                // Create categories if none exist
                $categoryIds = Category::factory(2)->create()->pluck('id');
            }

            $product->categories()->attach($categoryIds);
        });
    }
}
