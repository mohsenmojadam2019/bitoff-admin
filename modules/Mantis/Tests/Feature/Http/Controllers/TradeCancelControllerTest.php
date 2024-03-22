<?php

namespace Bitoff\Mantis\Tests\Feature\Http\Controllers\v1;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\Currency;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\PaymentMethod;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\User;
use Bitoff\Mantis\Application\Notifications\TradeCanceled;
use Bitoff\Mantis\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class TradeCancelControllerTest extends TestCase
{
    /** @test */
    public function admin_can_cancel_trade_for_buy_offer()
    {
        Notification::fake();
        $offer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->buy()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->create();
        $admin = User::factory()->create();

        $response = $this->actingAsUser($admin)->patch(route('mantis.trades.cancel', $trade->hash),['reason' => 'some text']);

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertJson(['msg' => 'Trade canceled successfully']);
        $this->assertDatabaseHas('trades', ['status' => Trade::STATUS_CANCELED]);
        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Trade::class,
            'subject_id' => $trade->id,
            'causer_id' => $admin->id,
            'properties->attributes->status' => Trade::STATUS_CANCELED]);
        Notification::assertSentTo(
            [$trader],
            TradeCanceled::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $offer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeCanceled::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $offer->hash
        );
    }

    /** @test */
    public function escrow_amount_should_add_to_credits_of_trader_after_canceling_trade_for_buy_offer()
    {
        Notification::fake();
        $offer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->buy()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->create(['amount' => 200, 'net_amount' => 170, 'fee' => 6, 'offer_data'=>json_encode($offer->toArray())]);

        $response = $this->actingAsUser()->patch(route('mantis.trades.cancel', $trade->hash),['reason' => 'some text']);

        $this->assertDatabaseHas('credits', [
            'type' => Credit::TYPE_CANCEL_TRADE,
            'currency' => $offer->currency,
            'user_id' => $trader->id,
            'amount' => $trade->net_amount + $trade->fee,
        ]);
        Notification::assertSentTo(
            [$trader],
            TradeCanceled::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $offer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeCanceled::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $offer->hash
        );
    }

    /** @test */
    public function escrow_amount_should_add_to_credits_of_offerer_after_canceling_trade_for_sell_offer()
    {
        Notification::fake();
        $offer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->sell()
            ->usdt()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->create(['amount' => 200, 'net_amount' => 170, 'fee' => 6, 'offer_data'=>json_encode($offer->toArray())]);

        $response = $this->actingAsUser()->patch(route('mantis.trades.cancel', $trade->hash),['reason' => 'some text']);

        $this->assertDatabaseHas('credits', [
            'type' => Credit::TYPE_CANCEL_TRADE,
            'currency' => $offer->currency,
            'user_id' => $offerer->id,
            'amount' => $trade->net_amount + $trade->fee,
        ]);
        Notification::assertSentTo(
            [$trader],
            TradeCanceled::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $offer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeCanceled::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $offer->hash
        );
    }

    /** @test */
    public function unauthenticated_user_can_not_cancel_trade()
    {
        $buyOffer = Offer::factory()
            ->for(User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->buy()
            ->create();
        $trade = Trade::factory()
            ->for($buyOffer, 'offer')
            ->for(User::factory()->create(), 'trader')
            ->create();

        $response = $this->patch(route('mantis.trades.cancel', $trade->hash));

        $response->assertRedirect(route('loginForm'));
    }
}
