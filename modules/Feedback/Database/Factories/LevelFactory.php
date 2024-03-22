<?php

namespace Bitoff\Feedback\Database\Factories;

use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Level;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    /**
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'level' => random_int(1, 3),
            'role' => $this->faker->randomElement(FeedbackRole::values()),
        ];
    }
}
