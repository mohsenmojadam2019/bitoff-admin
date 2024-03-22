<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition()
    {
        $user = User::factory()->create();
        return [
            'order_id' => Order::factory()->create([
                'earner_id' => $user->id,
            ])->id,
            'user_id' => $user->id,
        ];
    }
}
