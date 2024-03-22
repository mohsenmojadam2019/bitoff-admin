<?php

namespace Bitoff\Mantis\Tests\Feature\Http\Controllers;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\Currency;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\PaymentMethod;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\User;
use Bitoff\Mantis\Tests\TestCase;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;

/**
 * @internal
 *
 * @small
 */
class TradeControllerTest extends TestCase
{
    /**
     * @test
     */
    public function authenticate_user_can_see_all_trades()
    {
        $trades = Trade::factory()->count(5)->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::trades.index');
        $response->assertViewHas('trades');
        $response->assertSee($trades->first()->type);
        $response->assertSee($trades->first()->currency);
    }

    /**
     * @test
     */
    public function unauthenticated_user_can_not_see_all_trades()
    {
        $response = $this->get(route('mantis.trades.index'));

        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function pagination_functionality_works_fine()
    {
        $trades = Trade::factory()->count(16)->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index'));

        $response->assertSee('Next');
        $this->assertInstanceOf(Paginator::class, $response->viewData('trades'));
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_currency()
    {
        $btcOffer = Trade::factory()->btc()->create();
        $usdtOffer = Trade::factory()->usdt()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['currency' => Offer::CURRENCY_BTC]));

        $response->assertOk();
        $response->assertSee($btcOffer->currency);
        $response->assertDontSee($usdtOffer->hash);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_created_date()
    {
        $olderTrade = Trade::factory()->create(['created_at' => now()->subWeeks(3)]);
        $newTrade = Trade::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['from_date' => now()->subWeeks(4)->toDateString(), 'to_date' => now()->subWeeks(2)->toDateString()]));

        $response->assertOk();
        $response->assertSee($olderTrade->currency);
        $response->assertDontSee($newTrade->id);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_status()
    {
        $releasedTrade = Trade::factory()
            ->released()
            ->create();
        $activeTrade = Trade::factory()
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['status' => Trade::STATUS_ACTIVE]));

        $response->assertOk();
        $response->assertSee($activeTrade->status);
        $response->assertDontSee($releasedTrade->hash);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_type()
    {
        $buyTrade = Trade::factory()->buy()->create();
        $sellTrade = Trade::factory()->sell()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['is_buy' => Offer::BUY]));

        $response->assertOk();
        $response->assertSee($buyTrade->type);
        $response->assertDontSee($sellTrade->type);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_trader_user_name()
    {
        $trade1 = Trade::factory()->create();
        $trade2 = Trade::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['trader' => "@{$trade1->trader->username}"]));

        $response->assertOk();
        $response->assertSee($trade1->trader->username);
        $response->assertDontSee($trade2->trader->username);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_trader_email()
    {
        $trade1 = Trade::factory()->create();
        $trade2 = Trade::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['trader' => $trade1->trader->email]));

        $response->assertOk();
        $response->assertSee($trade1->hash);
        $response->assertDontSee($trade2->hash);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_offerer_username()
    {
        $trade1 = Trade::factory()->create();
        $trade2 = Trade::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['offerer' => "@{$trade1->offer->offerer->username}"]));

        $response->assertOk();
        $response->assertSee($trade1->offer->offerer->username);
        $response->assertDontSee($trade2->offer->offerer->username);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_offerer_email()
    {
        $trade1 = Trade::factory()->create();
        $trade2 = Trade::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['offerer' => $trade1->offer->offerer->email]));

        $response->assertOk();
        $response->assertSee($trade1->hash);
        $response->assertDontSee($trade2->hash);
    }

    /**
     * @test
     */
    public function can_filter_trades_result_based_on_hash_id()
    {
        $trade1 = Trade::factory()->create();
        $trade2 = Trade::factory()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.index', ['id' => $trade1->hash]));

        $response->assertOk();
        $response->assertSee($trade1->currency);
        $response->assertDontSee($trade2->hash);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_trade_details()
    {
        $trades = Trade::factory()->count(4)->create();
        $oneTrade = Trade::factory()->create();
        $response = $this->actingAsUser()->get(route('mantis.trades.show', $trades->first()->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::trades.show');
        $response->assertViewHas('trade');
        $response->assertSee($oneTrade->first()->hash);
        $response->assertSee($oneTrade->first()->rate);
        $response->assertSee($oneTrade->first()->currency);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_trade_offerer_in_overview_tab()
    {
        $offer = Offer::factory()
            ->for(User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->active()
            ->create(['offer_data' => json_encode($offer->toArray())]);

        $response = $this->actingAsUser()->get(route('mantis.trades.overview', $trade->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($trade->offer->fee);
        $response->assertSee($trade->offer->rate);
        $response->assertSee($trade->offer->currency);
        $response->assertSee($trade->amount);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_trade_offerer_in_offerer_tab()
    {
        $offer = Offer::factory()
            ->for(User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.offerer', $trade->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($trade->offer->offerer->username);
        $response->assertSee($trade->offer->offerer->email);
        $response->assertSee($trade->offer->offerer->created_at);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_trade_trader_in_trader_tab()
    {
        $trade = Trade::factory()->active()->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.trader', $trade->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($trade->trader->username);
        $response->assertSee($trade->trader->email);
        $response->assertSee($trade->trader->created_at);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_trade_credits_in_credits_list_tab()
    {
        $trade = Trade::factory()
            ->hasCredits(2)
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.trades.credits', $trade->hash));

        $credits = Credit::where('type', 'admin')->get();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($credits[0]->amount);
        $response->assertSee($credits[1]->amount);
        $response->assertSee($credits[0]->created_at);
        $response->assertSee($credits[1]->creditable->username);
    }
}
