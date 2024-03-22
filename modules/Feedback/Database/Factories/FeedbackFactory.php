<?php

namespace Bitoff\Feedback\Database\Factories;

use Bitoff\Feedback\Application\Enum\FeedbackRole;
use Bitoff\Feedback\Application\Models\Feedback;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    /**
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'role' => $this->faker->randomElement(FeedbackRole::values()),
            'is_positive' => random_int(0, 1),
            'comment' => $this->faker->sentence
        ];
    }

    public function positive(): Factory
    {
        return $this->state(fn () => [
            'is_positive' => true,
        ]);
    }

    public function negative(): Factory
    {
        return $this->state(fn () => [
            'is_positive' => false,
        ]);
    }
}
