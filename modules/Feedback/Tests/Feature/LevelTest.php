<?php

namespace Bitoff\Feedback\Tests\Feature;

use App\Models\User;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Models\Level;
use Bitoff\Feedback\Tests\TestCase;

class LevelTest extends TestCase
{
    /** @test */
    public function it_can_get_shopper_and_earner_levels_for_user()
    {
        $user = User::factory()->create();

        Level::factory()->create([
            'user_id' => $user->id,
            'role' => 'shopper',
            'level' => 2,
        ]);

        Level::factory()->create([
            'user_id' => $user->id,
            'role' => 'earner',
            'level' => 3,
        ]);

        $shopperLevel = Level::where('user_id', $user->id)
            ->where('role', 'shopper')
            ->first();

        $earnerLevel = Level::where('user_id', $user->id)
            ->where('role', 'earner')
            ->first();

        $this->assertEquals($shopperLevel->level ?? 0, 2);
        $this->assertEquals($earnerLevel->level ?? 0, 3);
    }
}
