<?php


namespace App\Services\Invoice\Factory;

use App\Services\Invoice\Invoice;

interface InvoiceFactoryInterface
{
    public function createInvoice(): Invoice;
}
