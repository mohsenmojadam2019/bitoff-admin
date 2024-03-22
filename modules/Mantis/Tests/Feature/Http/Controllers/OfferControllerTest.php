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
use Spatie\Activitylog\Models\Activity;

/**
 * @group offer
 *
 * @internal
 *
 * @small
 */
class OfferControllerTest extends TestCase
{
    /**
     * @test
     */
    public function authenticate_user_can_see_all_offers()
    {
        $offers = Offer::factory()
            ->count(5)
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::offers.index');
        $response->assertViewHas('offers');
        $response->assertSee($offers->first()->min);
        $response->assertSee($offers->first()->max);
    }

    /**
     * @test
     */
    public function unauthenticated_user_can_not_see_all_offers()
    {
        $response = $this->get(route('mantis.offers.index'));

        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function pagination_functionality_works_fine()
    {
        Offer::factory()
            ->count(16)
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index'));

        $response->assertSee('Next');
        $this->assertInstanceOf(Paginator::class, $response->viewData('offers'));
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_currency()
    {
        $offerer = User::factory()->create();
        $btcOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->fee()
            ->create();
        $usdtOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['currency' => Offer::CURRENCY_USDT]));

        $response->assertOk();
        $response->assertSee($usdtOffer->hash);
        $response->assertDontSee($btcOffer->hash);
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_created_date()
    {
        $offerer = User::factory()->create();
        $olderOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->fee()
            ->create(['created_at' => now()->subWeeks(3)]);
        $newOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['from_date' => now()->subWeeks(4)->toDateString(), 'to_date' => now()->subWeeks(2)->toDateString()]));

        $response->assertOk();
        $response->assertSee($olderOffer->hash);
        $response->assertDontSee($newOffer->hash);
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_status()
    {
        $offerer = User::factory()->create();
        $inactiveOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->inActive()
            ->fee()
            ->create();
        $activeOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->active()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['status' => Offer::ACTIVE]));

        $response->assertOk();
        $response->assertSee($activeOffer->hash);
        $response->assertDontSee($inactiveOffer->hash);
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_type()
    {
        $offerer = User::factory()->create();
        $buyOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->buy()
            ->fee()
            ->create();
        $sellOffer = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->sell()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['is_buy' => Offer::BUY]));

        $response->assertOk();
        $response->assertSee($buyOffer->hash);
        $response->assertDontSee($sellOffer->hsah);
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_offerer_user_name()
    {
        $offerer1 = User::factory()->create();
        $offerer2 = User::factory()->create();
        $offer1 = Offer::factory()
            ->for($offerer1, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->inActive()
            ->fee()
            ->create();
        $offer2 = Offer::factory()
            ->for($offerer2, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->active()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['offerer' => "@{$offerer1->username}"]));

        $response->assertOk();
        $response->assertSee($offer1->hash);
        $response->assertDontSee($offer2->hash);
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_offerer_email()
    {
        $offerer1 = User::factory()->create();
        $offerer2 = User::factory()->create();
        $offer1 = Offer::factory()
            ->for($offerer1, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->inActive()
            ->fee()
            ->create();
        $offer2 = Offer::factory()
            ->for($offerer2, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->active()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['offerer' => $offerer1->email]));

        $response->assertOk();
        $response->assertSee($offer1->hash);
        $response->assertDontSee($offer2->hash);
    }

    /**
     * @test
     */
    public function can_filter_offers_result_based_on_offer_hash_id()
    {
        $offerer = User::factory()->create();
        $offer1 = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->btc()
            ->inActive()
            ->fee()
            ->create();
        $offer2 = Offer::factory()
            ->for($offerer, 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->usdt()
            ->active()
            ->fee()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.index', ['id' => $offer1->hash]));

        $response->assertOk();
        $response->assertSee($offer1->hash);
        $response->assertDontSee($offer2->hash);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_offer_details()
    {
        $offers = Offer::factory()
            ->count(5)
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.show', $offers->first()->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('Mantis::offers.show');
        $response->assertViewHas('offer');
        $response->assertSee($offers->first()->hash);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_offer_offerer_in_offerer_tab()
    {
        $offers = Offer::factory()
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.offerer', $offers->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($offers->offerer->username);
        $response->assertSee($offers->offerer->email);
        $response->assertSee($offers->offerer->created_at);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_offer_trades_in_trades_list_tab()
    {
        $offer = Offer::factory()
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->for(User::factory()->create(), 'trader')
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.trades', $offer->hash));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($trade->amount);
        $response->assertSee($trade->status);
        $response->assertSee($trade->trader->username);
        $response->assertSee($trade->hash);
        $response->assertSee($trade->created_at);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_offer_credits_in_credits_list_tab()
    {
        $offer = Offer::factory()
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();
        $trade = Trade::factory()
            ->for($offer, 'offer')
            ->for(User::factory()->create(), 'trader')
            ->hasCredits(2)
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.credits', $offer->hash));

        $credits = Credit::where('type', 'admin')->get();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($credits[0]->amount);
        $response->assertSee($credits[1]->amount);
        $response->assertSee($credits[0]->created_at);
        $response->assertSee($credits[1]->creditable->username);
    }

    /**
     * @test
     */
    public function authenticate_user_can_see_offer_history_in_history_tab()
    {
        $offer = Offer::factory()
            ->for(User::factory(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->buy()
            ->active()
            ->create();

        $response = $this->actingAsUser()->get(route('mantis.offers.history', $offer->hash));

        $activities = Activity::all();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($activities[0]->description);
        $response->assertSee($activities[0]['properties']['attributes']['max']);
        $response->assertSee($activities[0]['properties']['attributes']['terms']);
        $response->assertSee($activities[0]['properties']['attributes']['rate']);
    }
}
