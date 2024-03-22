<?php

namespace Bitoff\Mantis\Database\Factories;

use Bitoff\Mantis\Application\Models\Currency;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\PaymentMethod;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TradeFactory extends Factory
{
    protected $model = Trade::class;

    public function definition()
    {
        $offer = Offer::factory()
            ->for(User::factory()->create(), 'offerer')
            ->for(PaymentMethod::factory())
            ->for(Currency::factory(), 'paymentMethodCurrency')
            ->fee()
            ->create();

        return [
            'amount' => $this->faker->numberBetween(300, 700),
            'net_amount' => $this->faker->numberBetween(300, 700),
            'net_amount_in_usd' => $this->faker->numberBetween(200, 600),
            'offer_id' => $offer->id,
            'offer_data' => json_encode($offer->toArray()),
            'trader_id' => User::factory()->create(),
            'bitcoin_rate' => 1 / mt_rand(9000, 12000),
        ];
    }

    public function btc(): Factory
    {
        return $this->state(fn () => [
            'offer_id' => Offer::factory()
                ->for(User::factory()->create(), 'offerer')
                ->for(PaymentMethod::factory())
                ->for(Currency::factory(), 'paymentMethodCurrency')
                ->btc()
                ->fee()
                ->create(),
        ]);
    }

    public function usdt(): Factory
    {
        return $this->state(fn () => [
            'offer_id' => Offer::factory()
                ->for(User::factory()->create(), 'offerer')
                ->for(PaymentMethod::factory())
                ->for(Currency::factory(), 'paymentMethodCurrency')
                ->usdt()
                ->fee()
                ->create(),
        ]);
    }

    public function buy(): Factory
    {
        return $this->state(fn () => [
            'offer_id' => Offer::factory()
                ->for(User::factory()->create(), 'offerer')
                ->for(PaymentMethod::factory())
                ->for(Currency::factory(), 'paymentMethodCurrency')
                ->sell()
                ->fee()
                ->create(),
        ]);
    }

    public function sell(): Factory
    {
        return $this->state(fn () => [
            'offer_id' => Offer::factory()
                ->for(User::factory()->create(), 'offerer')
                ->for(PaymentMethod::factory())
                ->for(Currency::factory(), 'paymentMethodCurrency')
                ->buy()
                ->fee()
                ->create(),
        ]);
    }

    public function active(): Factory
    {
        return $this->state(fn () => [
            'status' => Trade::STATUS_ACTIVE,
        ]);
    }

    public function paid(): Factory
    {
        return $this->state(fn () => [
            'status' => Trade::STATUS_PAID,
        ]);
    }

    public function released(): Factory
    {
        return $this->state(fn () => [
            'status' => Trade::STATUS_RELEASED,
        ]);
    }

    public function dispute(): Factory
    {
        return $this->state(fn () => [
            'status' => Trade::STATUS_DISPUTE,
        ]);
    }

    public function offerData(Offer $offer): Factory
    {
        return $this->state(fn () => [
            'offer_data' => json_encode($offer->toArray()),
        ]);
    }
}
