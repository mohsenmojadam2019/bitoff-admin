<?php

namespace Database\Factories;

use App\Models\Credit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'amount' => 0.00000002,
            'currency' => Credit::CURRENCY_BTC,
            'type' => Credit::TYPE_ADMIN,
        ];
    }

    public function usdt()
    {
        return $this->state(function () {
            return [
                'amount' => 1,
                'currency' => Credit::CURRENCY_USDT,
            ];
        });
    }
}
