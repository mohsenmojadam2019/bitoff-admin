<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as TestingTestCase;
use Illuminate\Support\Facades\Artisan;

/**
 * @internal
 *
 * @small
 */
class TestCase extends TestingTestCase
{
    use DatabaseTransactions;
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        $adminUser = User::factory()->create(['admin' => 1]);
        $this->seed([
            \DatabaseSeeder::class,
        ]);
    }

    protected function actingAsUser($user = null)
    {
        $user = $user ?? User::factory()->create();

        $this->actingAs($user);

        return $this;
    }

    protected function actingAsAdmin()
    {
        $user = User::where('admin', 1)->first();

        $this->actingAs($user);

        return $this;
    }
}
