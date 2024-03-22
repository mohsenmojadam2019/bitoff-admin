<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'order_id' => Order::factory()->create()->id,
            'price' => $this->faker->randomFloat(1, 20, 100),
            'shipping' => mt_rand(0, 3),
            'tax' => $this->faker->randomFloat(3, 0, 0.5),
            'wage' => $this->faker->randomFloat(3, 0, 1),
            'extra' => 0,
            // 'product_id' => Product::factory()->create()->id,
        ];
    }
}
