<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'code' => $this->faker->name,
        ];
    }
}
