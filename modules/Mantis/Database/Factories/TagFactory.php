<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
