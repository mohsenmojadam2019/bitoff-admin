<?php

namespace Bitoff\Mantis\Tests\Feature\Http\Controllers;

use Bitoff\Mantis\Tests\TestCase;

/**
 * @group offer
 *
 * @internal
 *
 * @small
 */
class TagControllerTest extends TestCase
{
    /**
     * @test
     */
    public function authenticate_user_can_create_a_tag()
    {
        $this->actingAsUser()->post(route('mantis.tags.store'), ['name' => 'new tag']);

        $this->assertDatabaseHas('tags', ['name' => 'new tag']);
    }

    /**
     * @test
     */
    public function unauthenticated_user_can_not_create_a_tag()
    {
        $response = $this->post(route('mantis.tags.store'), []);

        $response->assertRedirect('login');
    }

    /**
     * @test
     *
     * @dataProvider createTagDataProvider
     *
     * @param mixed $attributes
     * */
    public function required_some_attributes_when_call_create_tag_endpoint($attributes)
    {
        $response = $this->actingAsUser()->post(route('mantis.tags.store'), []);

        $response->assertSessionHasErrors('name');
    }

    public static function createTagDataProvider()
    {
        return [
            [
                [
                    'name' => '', //required
                ],
            ],
            [
                [
                    'name' => 34, //string
                ],
            ],
        ];
    }
}
