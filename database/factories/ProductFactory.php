<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Outlet;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchasePrice = $this->faker->randomFloat(2, 5000, 100000);
        $margin = $this->faker->randomFloat(2, 1.2, 3.0); // Margin 20% - 200%
        $sellingPrice = $purchasePrice * $margin;

        return [
            'outlet_id' => Outlet::factory(),
            'product_category_id' => ProductCategory::factory(),
            'code' => strtoupper($this->faker->unique()->lexify('PRD??????')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'barcode' => $this->faker->optional()->unique()->ean13(),
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'stock' => $this->faker->numberBetween(0, 200),
            'minimum_stock' => $this->faker->numberBetween(5, 20),
            'unit' => $this->faker->randomElement(['pcs', 'kg', 'gram', 'liter', 'ml', 'box', 'pack', 'bottle']),
            'image' => $this->faker->optional()->imageUrl(300, 300, 'food'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'is_sellable' => $this->faker->boolean(90), // 90% chance of being sellable
            'weight' => $this->faker->optional()->randomFloat(2, 10, 2000), // 10g - 2kg
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the product is sellable.
     */
    public function sellable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_sellable' => true,
        ]);
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(0, $attributes['minimum_stock']),
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}