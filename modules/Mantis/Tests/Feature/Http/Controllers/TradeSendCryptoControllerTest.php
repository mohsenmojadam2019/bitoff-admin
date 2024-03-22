<?php

namespace Bitoff\Mantis\Tests\Feature\Http\Controllers;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\Currency;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\PaymentMethod;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\User;
use Bitoff\Mantis\Application\Notifications\TradeReleased;
use Bitoff\Mantis\Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class TradeSendCryptoControllerTest extends TestCase
{
    /** @test */
    public function admin_can_send_btc_for_sell_offer_and_trade_status_changes_to_released()
    {
        Notification::fake();
        $sellOffer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->sell()
            ->create();
        $trade = Trade::factory()
            ->for($sellOffer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->paid()
            ->create(['offer_data'=>json_encode($sellOffer->toArray())]);
        $admin = User::factory()->create();

        $response = $this->actingAsUser($admin)->patch(route('mantis.trades.send_crypto', $trade->hash));

        $response->assertOk();
        $this->assertDatabaseHas('trades', [
            'status' => Trade::STATUS_RELEASED,
        ]);
        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Trade::class,
            'subject_id' => $trade->id,
            'causer_id' => $admin->id,
            'properties->attributes->status' => Trade::STATUS_RELEASED]);
        Notification::assertSentTo(
            [$trader],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
    }

    /** @test */
    public function admin_can_send_crypto_for_buy_offer_and_trade_status_changes_to_released()
    {
        Notification::fake();
        $sellOffer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->buy()
            ->create();
        $trade = Trade::factory()
            ->for($sellOffer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->paid()
            ->create();

        $response = $this->actingAsUser()->patch(route('mantis.trades.send_crypto', $trade->hash));

        $response->assertOk();
        $this->assertDatabaseHas('trades', [
            'status' => Trade::STATUS_RELEASED,
        ]);
        Notification::assertSentTo(
            [$trader],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
    }

    /** @test */
    public function unauthenticated_user_can_not_send_crypto()
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
            ->paid()
            ->create();

        $response = $this->patch(route('mantis.trades.send_crypto', $trade->hash));

        $response->assertRedirect(route('loginForm'));
    }

    /** @test */
    public function escrowed_amount_should_release_to_trader_credit_after_sending_crypto_for_sell_usdt_offer()
    {
        Notification::fake();
        $sellOffer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->usdt()
            ->sell()
            ->create(['rate' => 15]);
        $trade = Trade::factory()
            ->for($sellOffer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->paid()
            ->create(['amount' => 200, 'net_amount' => 170,'offer_data'=>json_encode($sellOffer)]);

        $response = $this->actingAsUser()->patch(route('mantis.trades.send_crypto', $trade->hash));

        $response->assertOk();
        $this->assertDatabaseHas('credits', [
            'user_id' => $trader->id,
            'type' => Credit::TYPE_BUY_TRADE,
            'amount' => $trade->net_amount,
            'currency' => $sellOffer->currency,
            'creditable_type' => Trade::class,
            'creditable_id' => Trade::latest()->first()->id,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => null,
        ]);
        Notification::assertSentTo(
            [$trader],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
    }

    /** @test */
    public function escrowed_amount_should_release_to_trader_credit_after_sending_crypto_for_sell_btc_offer()
    {
        Notification::fake();
        $sellOffer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->btc()
            ->sell()
            ->create(['rate' => 15]);
        $trade = Trade::factory()
            ->for($sellOffer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->paid()
            ->create(['amount' => 200, 'net_amount' => 0.0085,'offer_data'=>json_encode($sellOffer)]);

        $response = $this->actingAsUser()->patch(route('mantis.trades.send_crypto', $trade->hash));

        $response->assertOk();
        $this->assertDatabaseHas('credits', [
            'user_id' => $trader->id,
            'type' => Credit::TYPE_BUY_TRADE,
            'amount' => $trade->net_amount,
            'currency' => $sellOffer->currency,
            'creditable_type' => Trade::class,
            'creditable_id' => Trade::latest()->first()->id,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => null,
        ]);
        Notification::assertSentTo(
            [$trader],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $sellOffer->hash
        );
    }

    /** @test */
    public function escrowed_amount_should_release_to_offerer_credit_after_sending_crypto_for_buy_offer()
    {
        Notification::fake();
        $buyOffer = Offer::factory()
            ->for($offerer = User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->usdt()
            ->buy()
            ->create();
        $trade = Trade::factory()
            ->for($buyOffer, 'offer')
            ->for($trader = User::factory()->create(), 'trader')
            ->paid()
            ->create(['amount' => 200, 'net_amount' => 170, 'offer_data'=>json_encode($buyOffer)]);

        $response = $this->actingAsUser()->patch(route('mantis.trades.send_crypto', $trade->hash));

        $response->assertOk();
        $this->assertDatabaseHas('credits', [
            'user_id' => $offerer->id,
            'type' => Credit::TYPE_SELL_TRADE,
            'amount' => $trade->net_amount,
            'currency' => $buyOffer->currency,
            'creditable_type' => Trade::class,
            'creditable_id' => Trade::latest()->first()->id,
            'status' => Credit::STATUS_CONFIRMATION,
            'extra' => null,
        ]);
        Notification::assertSentTo(
            [$trader],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $buyOffer->hash
        );
        Notification::assertSentTo(
            [$offerer],
            TradeReleased::class,
            fn ($notification) => $notification->tradeHashId === $trade->hash
            && $notification->offerHashId == $buyOffer->hash
        );
    }
}
