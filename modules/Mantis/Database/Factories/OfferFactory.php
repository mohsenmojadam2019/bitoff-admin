<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition()
    {
        return [
            'is_buy' => $this->faker->randomElement(Offer::isBuy()),
            'currency' => $this->faker->randomElement(Offer::currencies()),
            'rate' => $this->faker->randomFloat(2, 0, 15),
            'min' => $this->faker->randomFloat(2, 100, 500),
            'max' => $this->faker->randomFloat(2, 500, 1000),
            'time' => $this->faker->numberBetween(10, 180),
            'fee' => $this->faker->randomFloat(2, 0, 10),
            'terms' => $this->faker->text(400),
            'active' => $this->faker->randomElement(Offer::isActive()),
        ];
    }

    public function buy(): Factory
    {
        return $this->state(fn () => [
            'is_buy' => Offer::BUY
        ]);
    }

    public function sell(): Factory
    {
        return $this->state(fn () => [
            'is_buy' => Offer::SELL
        ]);
    }

    public function btc(): Factory
    {
        return $this->state(fn () => [
            'currency' => Offer::CURRENCY_BTC,
        ]);
    }

    public function usdt(): Factory
    {
        return $this->state(fn () => [
            'currency' => Offer::CURRENCY_USDT,
        ]);
    }

    public function active(): Factory
    {
        return $this->state(fn () => [
            'active' => Offer::ACTIVE
        ]);
    }

    public function inactive(): Factory
    {
        return $this->state(fn () => [
            'active' => Offer::INACTIVE,
        ]);
    }

    public function fee(): Factory
    {
        return $this->state(fn () => [
            'fee' => 3,
        ]);
    }
}
