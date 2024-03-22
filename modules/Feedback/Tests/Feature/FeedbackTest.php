<?php

namespace Bitoff\Feedback\Tests\Feature;

use App\Models\User;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Application\Models\Level;
use Bitoff\Feedback\Tests\TestCase;

class FeedbackTest extends TestCase
{
    /** @test */
    public function it_can_count_positive_and_negative_feedback()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Feedback::factory()->times(5)->create([
            'to_user_id' => $user->id,
            'from_user_id' => $user2->id,
            'role' => 'shopper',
            'is_positive' => true,
            'feedbackable_type' => 'default_type',
            'feedbackable_id' => 1,
        ]);

        Feedback::factory()->times(2)->create([
            'to_user_id' => $user->id,
            'from_user_id' => $user2->id,
            'role' => 'shopper',
            'is_positive' => false,
            'feedbackable_type' => 'default_type',
            'feedbackable_id' => 1,
        ]);

        Feedback::factory()->times(2)->create([
            'to_user_id' => $user->id,
            'from_user_id' => $user2->id,
            'role' => 'earner',
            'is_positive' => false,
            'feedbackable_type' => 'default_type',
            'feedbackable_id' => 1,
        ]);

        $shopperPositiveCount = Feedback::where('to_user_id', $user->id)
            ->where('role', 'shopper')
            ->where('is_positive', true)
            ->count();

        $shopperNegativeCount = Feedback::where('to_user_id', $user->id)
            ->where('role', 'shopper')
            ->where('is_positive', false)
            ->count();

        $earnerNegativeCount = Feedback::where('to_user_id', $user->id)
            ->where('role', 'earner')
            ->where('is_positive', false)
            ->count();

        $this->assertEquals(5, $shopperPositiveCount);
        $this->assertEquals(2, $shopperNegativeCount);
        $this->assertEquals(2, $earnerNegativeCount);
    }
}
