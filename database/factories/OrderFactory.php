<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shopper_id' => User::factory()->create()->id,
            'currency' => 'btc',
            'off' => mt_rand(5, 20),
            'tax' => $this->faker->randomFloat(3, 0.2, 1),
            'bitcoin_rate' => 1 / (mt_rand(9000, 12000)),
            'status' => Order::STATUS_PENDING,
            'shopper_wage_percent' => $this->faker->randomFloat(3, 0.5, 2),
            'shopper_wage_amount' => 0,
            'address' => [
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'state' => $this->faker->state,
                'city' => $this->faker->city,
                'street' => $this->faker->streetName,
                'building' => $this->faker->buildingNumber,
                'zip_code' => $this->faker->postcode,
                'phone' => $this->faker->phoneNumber,
            ],
            'address_book_id' => 1,
            'tp' => mt_rand(20, 300),
        ];
    }

    public function canada()
    {
        return $this->state(function () {
            return [
                'source' => 'canada',
                'meta' => ['origin_currency' => 'canada'],
            ];
        });
    }

    public function amazonAllStore()
    {
        return $this->state(function () {
            return [
                'source' => 'amazon',
            ];
        });
    }

    public function ebay()
    {
        return $this->state(function () {
            return [
                'source' => 'ebay',
            ];
        });
    }
}
