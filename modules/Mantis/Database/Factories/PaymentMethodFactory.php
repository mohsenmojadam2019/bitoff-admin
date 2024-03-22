<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'fee' => $this->faker->randomFloat(2, 0, 10),
            'time' => $this->faker->numberBetween(10, 180)
        ];
    }
}
