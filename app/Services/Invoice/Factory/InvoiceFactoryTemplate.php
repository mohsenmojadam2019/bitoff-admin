<?php


namespace App\Services\Invoice\Factory;

use App\Services\Invoice\Invoice;

abstract class InvoiceFactoryTemplate
{
    /**
     * @var Invoice
     */
    protected $instance;

    protected function getInstance()
    {
        return $this->instance;
    }

    abstract protected function buildInstance();

    abstract protected function buildItems();

    public function createInvoice(): Invoice
    {
        $this->buildInstance();
        $this->buildItems();

        return $this->getInstance();
    }
}
