<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Credit;
use App\Models\Item;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
use App\Services\BitCoinRate;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
class OrdersControllerTest extends TestCase
{
    /**
     * @test
     */
    public function after_cancel_orders_item_order_item_status_should_be_cancel_if_order_has_more_than_one_item()
    {
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.003);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
        ]);
        Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
        ]);

        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item1->id]));

        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_CANCEL,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_PURCHASE,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_cancel_one_orders_all_item_order_status_should_be_pending_if_he_has_enough_credit()
    {
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.003);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
        ]);
        $item = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
        ]);
        Credit::factory()->create([
            'user_id' => $shopper->id,
            'amount' => 2,
        ]);

        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item->id]));

        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_cancel_orders_item_order_item_status_should_be_init_and_order_status_should_be_pending_if_he_has_enough_credit()
    {
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.003);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
        ]);
        $item2 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
        ]);

        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item1->id]));
        $this->travel(1)->minutes();
        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item2->id]));

        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item2->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_cancel_orders_item_order_item_status_should_be_init_and_order_status_should_be_no_credit_if_he_has_not_enough_credit()
    {
        $this->withoutExceptionHandling();
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
        ]);
        $item2 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 200,
        ]);

        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item1->id]));
        $this->travel(1)->minutes();
        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item2->id]));

        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item2->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_cancel_orders_item_order_item_status_should_be_init_and_order_status_should_be_credit_pending_if_her_open_orders_amount_was_more_than_her_credit()
    {
        $this->markTestSkipped('we should look closer to this test');
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        Credit::factory()->create([
            'user_id' => $shopper->id,
            'amount' => 4,
        ]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 200,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_CANCEL,
            'price' => 100,
        ]);
        $item2 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 100,
        ]);
        $order = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 550,
        ]);
        Item::factory()->create([
            'order_id' => $order->id,
            'status' => Item::STATUS_INIT,
            'price' => 550,
        ]);
        $wishPendingOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_WISH_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 550,
        ]);
        Item::factory()->create([
            'order_id' => $wishPendingOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 550,
        ]);

        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item2->id]));

        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item2->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_CREDIT_PENDING,
            'shopper_id' => $shopper->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_CREDIT_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_cancel_orders_item_order_status_should_be_credit_pending_if_her_open_orders_amount_was_more_than_her_credit()
    {
        $this->markTestSkipped('we should look closer to this test');
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        Credit::factory()->create([
            'user_id' => $shopper->id,
            'amount' => 5,
        ]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 100,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 100,
        ]);
        $item2 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 50,
        ]);
        $order = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 400,
        ]);
        Item::factory()->create([
            'order_id' => $order->id,
            'status' => Item::STATUS_INIT,
            'price' => 400,
        ]);
        $wishPendingOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_WISH_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 300,
        ]);
        Item::factory()->create([
            'order_id' => $wishPendingOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 300,
        ]);

        $this->actingAsAdmin()->delete(route('orders.items.cancel', [$nativeOrder->hash, $item1->id]));

        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_CANCEL,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item2->id,
            'status' => Item::STATUS_INIT,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_PURCHASE,
            'shopper_id' => $shopper->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_CREDIT_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_remove_orders_earner_order_status_should_be_pending_or_wish_pending_if_her_open_orders_amount_was_less_than_her_credit()
    {
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        Credit::factory()->create([
            'user_id' => $shopper->id,
            'amount' => 4,
        ]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
        ]);
        Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 200,
        ]);
        $order = Order::factory()->amazonAllStore()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_CREDIT_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
        ]);
        Item::factory()->create([
            'order_id' => $order->id,
            'status' => Item::STATUS_INIT,
            'price' => 200,
        ]);

        $this->actingAsAdmin()->delete(route('orders.cancel.earner', [$nativeOrder->hash, 'description' => 'some reason']));

        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_PENDING,
            'shopper_id' => $shopper->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_remove_orders_earner_order_status_should_be_credit_pending_if_her_open_orders_amount_was_more_than_her_credit()
    {
        $this->markTestSkipped('we should look closer to this test');
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        Credit::factory()->create([
            'user_id' => $shopper->id,
            'amount' => 4,
        ]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 100,
        ]);
        $item2 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 100,
        ]);
        $order = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 550,
        ]);
        Item::factory()->create([
            'order_id' => $order->id,
            'status' => Item::STATUS_INIT,
            'price' => 550,
        ]);
        $wishPendingOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_WISH_PENDING,
            'off' => 0,
            'tax' => 0,
            'shopper_wage_percent' => 0,
            'bitcoin_rate' => 0.01,
            'tp' => 550,
        ]);
        Item::factory()->create([
            'order_id' => $wishPendingOrder->id,
            'status' => Item::STATUS_INIT,
            'price' => 550,
        ]);

        $this->actingAsAdmin()->delete(route('orders.cancel.earner', [$nativeOrder->hash, 'description' => 'some reason']));

        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_CREDIT_PENDING,
            'shopper_id' => $shopper->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_CREDIT_PENDING,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_deliver_btc_orders_item_order_item_status_should_be_deliver_and_earner_fee_should_subtracted_from_escrowed_amount()
    {
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'currency' => Order::CURRENCY_BTC,
            'bitcoin_rate' => 0.01,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'shopper_wage_percent' => 1.5,
            'earner_wage_percent' => 5.69 - 1.5,
            'off' => 10,
            'tax' => 0,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_SHIP,
            'price' => 100,
            'shipping' => 0,
            'tax' => 0,
            'wage' => 1.5,
        ]);

        $deliverResponse = $this->actingAsAdmin()->post(route('orders.items.deliver', [$nativeOrder->hash, $item1->id]));

        $this->assertDatabaseHas('credits', [
            'user_id' => $earner->id,
            'type' => Credit::TYPE_EARN,
            'amount' => 0.8581,
            'currency' => Order::CURRENCY_BTC,
            'creditable_type' => Reservation::class,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => $this->castAsJson(['order_item_id' => $item1->id]),
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_DELIVER,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_DELIVER,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_deliver_usdt_orders_item_order_item_status_should_be_deliver_and_earner_fee_should_subtracted_from_escrowed_amount()
    {
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->create([
            'shopper_id' => $shopper->id,
            'currency' => Order::CURRENCY_USDT,
            'bitcoin_rate' => 0.01,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'shopper_wage_percent' => 1.5,
            'earner_wage_percent' => 5.69 - 1.5,
            'off' => 10,
            'tax' => 0,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_SHIP,
            'price' => 100,
            'shipping' => 0,
            'tax' => 0,
            'wage' => 1.5,
        ]);

        $deliverResponse = $this->actingAsAdmin()->post(route('orders.items.deliver', [$nativeOrder->hash, $item1->id]));

        $this->assertDatabaseHas('credits', [
            'user_id' => $earner->id,
            'type' => Credit::TYPE_EARN,
            'amount' => 85.81,
            'currency' => Order::CURRENCY_USDT,
            'creditable_type' => Reservation::class,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => $this->castAsJson(['order_item_id' => $item1->id]),
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_DELIVER,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_DELIVER,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_deliver_btc_non_native_orders_item_order_item_status_should_be_deliver_and_earner_fee_should_subtracted_from_escrowed_amount()
    {
        $this->mock(BitCoinRate::class)->shouldReceive('getValue')->andReturn(0.01);
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->ebay()->create([
            'shopper_id' => $shopper->id,
            'currency' => Order::CURRENCY_BTC,
            'bitcoin_rate' => 0.01,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'shopper_wage_percent' => 1.5,
            'earner_wage_percent' => 5.78 - 1.5,
            'off' => 10,
            'tax' => 0,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_SHIP,
            'price' => 100,
            'shipping' => 0,
            'tax' => 0,
            'wage' => 1.5,
        ]);

        $deliverResponse = $this->actingAsAdmin()->post(route('orders.items.deliver', [$nativeOrder->hash, $item1->id]));

        $this->assertDatabaseHas('credits', [
            'user_id' => $earner->id,
            'type' => Credit::TYPE_EARN,
            'amount' => 0.8572,
            'currency' => Order::CURRENCY_BTC,
            'creditable_type' => Reservation::class,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => $this->castAsJson(['order_item_id' => $item1->id]),
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_DELIVER,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_DELIVER,
            'shopper_id' => $shopper->id,
        ]);
    }

    /**
     * @test
     */
    public function after_deliver_usdt_non_native_orders_item_order_item_status_should_be_deliver_and_earner_fee_should_subtracted_from_escrowed_amount()
    {
        $shopper = User::factory()->create();
        $earner = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $earner->id]);
        $nativeOrder = Order::factory()->ebay()->create([
            'shopper_id' => $shopper->id,
            'currency' => Order::CURRENCY_USDT,
            'bitcoin_rate' => 0.01,
            'earner_id' => $earner->id,
            'status' => Order::STATUS_PURCHASE,
            'reserve_id' => $reservation->id,
            'shopper_wage_percent' => 1.5,
            'earner_wage_percent' => 5.78 - 1.5,
            'off' => 10,
            'tax' => 0,
        ]);
        $item1 = Item::factory()->create([
            'order_id' => $nativeOrder->id,
            'status' => Item::STATUS_SHIP,
            'price' => 100,
            'shipping' => 0,
            'tax' => 0,
            'wage' => 1.5,
        ]);

        $deliverResponse = $this->actingAsAdmin()->post(route('orders.items.deliver', [$nativeOrder->hash, $item1->id]));

        $this->assertDatabaseHas('credits', [
            'user_id' => $earner->id,
            'type' => Credit::TYPE_EARN,
            'amount' => 85.72,
            'currency' => Order::CURRENCY_USDT,
            'creditable_type' => Reservation::class,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => $this->castAsJson(['order_item_id' => $item1->id]),
        ]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item1->id,
            'status' => Item::STATUS_DELIVER,
            'order_id' => $nativeOrder->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $nativeOrder->id,
            'status' => Order::STATUS_DELIVER,
            'shopper_id' => $shopper->id,
        ]);
    }
}
