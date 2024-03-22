<?php

namespace Bitoff\Mantis\Tests\Feature\Http\Controllers;

use Bitoff\Mantis\Application\Models\Currency;
use Bitoff\Mantis\Application\Models\PaymentMethod;
use Bitoff\Mantis\Application\Models\Tag;
use Bitoff\Mantis\Application\Models\User;
use Bitoff\Mantis\Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * @group offer
 */
class PaymentMethodControllerTest extends TestCase
{
    /** @test */
    public function authenticate_user_can_see_all_payment_methods()
    {
        $paymentMethods = PaymentMethod::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.payment_methods.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::paymentMethods.index');
        $response->assertViewHas('paymentMethods');
        $response->assertSee($paymentMethods->first()->name);
    }

    /** @test */
    public function unauthenticated_user_can_not_see_payment_methods()
    {
        $response = $this->get(route('mantis.payment_methods.index'));

        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticate_user_can_see_a_payment_methods_details()
    {
        $paymentMethodOne = PaymentMethod::factory()->create();
        $paymentMethodTwo = PaymentMethod::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.payment_methods.show', $paymentMethodTwo));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::paymentMethods.show');
        $response->assertViewHas('paymentMethod');
        $response->assertSee($paymentMethodTwo->fee);
        $response->assertSee($paymentMethodTwo->tags);
    }

    /** @test */
    public function unauthenticated_user_can_not_see_payment_methods_details()
    {
        $response = $this->get(route('mantis.payment_methods.show', 1));

        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticate_user_can_create_a_payment_method()
    {
        $parentPaymentMethod = PaymentMethod::factory()
            ->hasTags()
            ->hasCurrencies()
            ->create();
        $attributes = [
            'name' => 'name',
        ];
        $this->actingAsUser()->post(route('mantis.payment_methods.store'), $attributes);

        unset($attributes['currencies']);
        $this->assertDatabaseHas('payment_methods', $attributes);
    }

    /** @test */
    public function unauthenticated_user_can_not_create_a_payment_method()
    {
        $response = $this->post(route('mantis.payment_methods.store'), []);

        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticate_user_can_update_a_payment_methods_fee()
    {
        $paymentMethod = PaymentMethod::factory()
            ->hasTags()
            ->hasCurrencies()
            ->create();
        $this->actingAsUser()->patch(route('mantis.payment_methods.update', $paymentMethod), ['fee' => 20, 'name' => 'Updated Name',]);

        $this->assertDatabaseHas('payment_methods', ['fee' => 20]);
    }

    /** @test */
    public function authenticate_user_can_update_a_parent_payment_methods_tags()
    {
        $paymentMethod = PaymentMethod::factory()
            ->hasTags()
            ->hasCurrencies()
            ->create();

        $tag = Tag::factory()->create();

        $this->actingAsUser()->patch(route('mantis.payment_methods.update', $paymentMethod), [
            'name' => 'Updated Payment Method Name',
            'tags' => [$tag->id],
        ]);

        $paymentMethod->tags()->sync([$tag->id]);

        $this->assertDatabaseHas('payment_method_tag', [
            'tag_id' => $tag->id,
            'payment_method_id' => $paymentMethod->id
        ]);
    }

    /** @test */
    public function after_update_a_parent_payment_methods_fee_children_should_be_updated_also()
    {
        $childPaymentMethod = PaymentMethod::factory()
            ->for($parentPaymentMethod = PaymentMethod::factory()->create(), 'parent')
            ->hasTags()
            ->hasCurrencies()
            ->create();
        $this->actingAsUser()->patch(route('mantis.payment_methods.update', $parentPaymentMethod), ['fee' => $childPaymentMethod->fee, 'name' => $childPaymentMethod->name,]);

        $this->assertDatabaseHas('payment_methods', ['id' => $childPaymentMethod->id, 'fee' => $childPaymentMethod->fee, 'name' => $childPaymentMethod->name,]);
    }

    /** @test */
    public function after_update_a_parent_payment_methods_tags_children_should_be_updated_also()
    {
        $childPaymentMethod = PaymentMethod::factory()
            ->for($parentPaymentMethod = PaymentMethod::factory()->create(), 'parent')
            ->hasTags()
            ->hasCurrencies()
            ->create();
        $tagId = Tag::factory()->create()->id;
        $this->actingAsUser()->patch(route('mantis.payment_methods.update', $parentPaymentMethod), [
            'name' => 'Updated Name',
            'tags' => ['id' => $tagId],
        ]);
        $childPaymentMethod->tags()->sync([$tagId]);

        $this->assertDatabaseHas('payment_method_tag', [
            'tag_id' => $tagId,
            'payment_method_id' => $childPaymentMethod->id]);
    }

    /** @test */
    public function authenticated_user_can_toggle_child_payment_methods()
    {
        $childPaymentMethod = PaymentMethod::factory()
            ->for(PaymentMethod::factory()->create(), 'parent')
            ->hasTags()
            ->hasCurrencies()
            ->create();

        $this->actingAsUser()->patch(route('mantis.payment_methods.toggle', $childPaymentMethod));

        $this->assertDatabaseHas('payment_methods', ['id' => $childPaymentMethod->id, 'active' => PaymentMethod::INACTIVE]);
    }

    /** @test */
    public function unauthenticated_user_can_not_toggle_a_payment_method()
    {
        $childPaymentMethod = PaymentMethod::factory()
            ->for(PaymentMethod::factory()->create(), 'parent')
            ->hasTags()
            ->hasCurrencies()
            ->create();

        $response = $this->patch(route('mantis.payment_methods.toggle', $childPaymentMethod));

        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticate_user_can_update_a_child_payment_methods()
    {
        $parentPaymentMethod1 = PaymentMethod::factory()
            ->hasTags()
            ->hasCurrencies()
            ->create();

        $childPaymentMethod = PaymentMethod::factory()
            ->for($parentPaymentMethod1, 'parent')
            ->hasTags()
            ->hasCurrencies()
            ->create();

        $newCurrency = Currency::factory()->create();

        $this->actingAsUser()->patch(route('mantis.payment_methods.update', $childPaymentMethod), [
            'name' => 'new',
            'parent_id' => $parentPaymentMethod1->id,
            'currencies' => ['id' => $newCurrency->id]
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'name' => 'new',
            'parent_id' => $parentPaymentMethod1->id
        ]);
    }

    /** @test */
    public function authenticated_user_can_update_payment_method_fee_icon()
    {
        $this->actingAs(User::factory()->create());

        $paymentMethod = PaymentMethod::factory()->hasCurrencies()->create();

        $file = UploadedFile::fake()->image('icon.jpg');
        $response = $this->patch(route('mantis.payment_methods.update', $paymentMethod), [
            'fee' => $paymentMethod->fee,
            'name' => $paymentMethod->name,
            'icon' => $file
        ]);


        $this->assertTrue($paymentMethod->getFirstMedia('icon')->exists());

        $this->assertDatabaseHas('payment_methods', ['fee' => $paymentMethod->fee]);
    }


}
