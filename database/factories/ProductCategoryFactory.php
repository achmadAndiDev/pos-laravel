<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Food', 'Beverages', 'Snacks', 'Electronics', 'Clothing', 
            'Cosmetics', 'Medicine', 'Stationery', 'Toys', 'Books'
        ];

        $categoryName = $this->faker->randomElement($categories);
        
        return [
            'name' => $categoryName,
            'code' => strtoupper($this->faker->unique()->lexify('CAT???')),
            'description' => $this->faker->optional()->sentence(),
            'image' => $this->faker->optional()->imageUrl(200, 200, 'business'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}