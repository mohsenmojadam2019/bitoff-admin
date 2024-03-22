<?php

namespace Bitoff\Feedback\Tests;

use App\Models\User;
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

}
