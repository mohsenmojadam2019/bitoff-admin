<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        return [
            'key' => $this->faker->word,
            'value' => [
                [
                    'max'=>20,
                    'min'=>1,
                    'level'=>1,
                    'max_offer'=>40,
                    'max_percent'=>10,
                ],
                [
                    'max'=>50,
                    'min'=>21,
                    'level'=>2,
                    'max_offer'=>140,
                    'max_percent'=>20,
                ],
                [
                    'max'=>200,
                    'min'=>51,
                    'level'=>3,
                    'max_offer'=>240,
                    'max_percent'=>30,
                ]
            ],
        ];
    }
}
