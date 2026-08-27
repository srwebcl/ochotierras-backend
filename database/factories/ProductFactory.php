<?php

namespace Database\Factories;

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
        $name = ucfirst($this->faker->words(3, true));

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 999999),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(5000, 80000),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'is_active' => true,
            'is_pack' => false,
            'is_featured' => false,
        ];
    }
}
