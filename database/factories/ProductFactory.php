<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory {
    
    protected $model = Product::class;

    public function definition() {
        return [
            'category_id' => Category::factory(),
            'name'        => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'price'       => $this->faker->numberBetween(1000, 100000),
            'stock'       => $this->faker->numberBetween(10, 5000),
        ];
    }
}