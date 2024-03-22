<?php


namespace App\Services\Invoice\Factory;

use App\Models\Cart;
use App\Models\User;
use App\Services\Invoice\Invoice;
use Illuminate\Support\Collection;
use RuntimeException;

class CartInvoiceFactory extends InvoiceFactoryTemplate implements InvoiceFactoryInterface
{
    /**
     * @var int
     */
    private $off;
    /**
     * @var int
     */
    private $tax;
    /**
     * @var int
     */
    private $prime;
    /**
     * @var Collection
     */
    protected $carts;
    /**
     * @var int
     */
    private $wage;

    public function __construct(Collection $carts, $off = 0, $prime = 0, $tax = 0, $wage = 0)
    {
        $this->carts = $carts;
        $this->off = $off;
        $this->prime = $prime;
        $this->tax = $tax;
        $this->wage = $wage;
    }

    public static function fromUser(User $user): self
    {
        return new static($user->carts);
    }

    public function fromCollection(Collection $carts)
    {
        return new static($carts);
    }

    public function off(int $percent): self
    {
        $this->off = $percent;

        return $this;
    }

    public function tax($percent): self
    {
        $this->tax = $percent;

        return $this;
    }

    public function prime(int $prime): self
    {
        $this->prime = $prime;

        return $this;
    }

    public function wage($percent): self
    {
        $this->wage = $percent;

        return $this;
    }

    protected function buildInstance(): self
    {
        $this->instance = Invoice::withGroup([
            'offPercent' => $this->off,
            'taxPercent' => $this->tax,
            'wagePercent' => $this->wage,
            'prime' => $this->prime,
        ]);

        return $this;
    }

    protected function buildItems(): self
    {
        if (!is_a($this->instance, Invoice::class)) {
            throw new RuntimeException('Invoice does not exists in object');
        }

        /** @var Cart $cart */
        foreach ($this->carts as $cart) {
            $this->instance->addProduct($cart->product, $cart->extra ?: 0, $cart->count);
        }

        return $this;
    }
}
