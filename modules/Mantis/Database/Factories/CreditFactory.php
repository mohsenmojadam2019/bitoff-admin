<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    protected $model = Credit::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'amount' => $this->faker->numberBetween(600, 700),
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
