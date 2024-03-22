<?php

namespace Bitoff\Mantis\Tests;

use Bitoff\Mantis\Application\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase as BaseTestCase;

/**
 * @internal
 *
 * @small
 */
class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function actingAsUser($user = null)
    {
        $user = $user ?? User::factory()->create();

        $this->actingAs($user);

        return $this;
    }
}
