<?php

namespace Bitoff\Mantis\Database\Seeders;

use Bitoff\Mantis\Application\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $paymentsData = [
            [
                'name' => 'Gift Card',
            ],
            [
                'name' => 'Online Wallets',
            ],
            [
                'name' => 'Cash Payments',
            ],
            [
                'name' => 'Debit/credit Cards',
            ],
            [
                'name' => 'Digital Currencies',
            ],
            [
                'name' => 'Goods and Services',
            ],
        ];

        foreach ($paymentsData as $paymentData) {
            PaymentMethod::query()->updateOrCreate($paymentData);
        }
    }
}
