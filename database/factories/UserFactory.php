<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->name,
            'username' => $this->faker->regexify('[A-Za-z0-9]{40}'),
            'email' => $this->faker->regexify('[A-Za-z0-9]{40}') . '@gmail.com',
            'password' => Hash::make('password'),
            'remember_token' => 'njGfU3M5TXYYCTFnuge4',
            'fast_release' => $this->faker->boolean,
            'google2fa_enable' => $this->faker->boolean,
            'active' => false,
        ];
    }
}
