<?php

namespace App\Jobs;

use App\Api\WishList;
use App\Models\Order;
use App\Models\Wish;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WishListJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Wish */
    public $wish;

    /**
     * Create a new job instance.
     */
    public function __construct(Wish $wish)
    {
        $this->wish = $wish;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        /**
         * @var WishList $api
         */
        $api = app(WishList::class)
            ->setAddress($this->wish->order->address)
            ->setOrderId($this->wish->identifier());

        $items = $this->wish->order->items()
            ->select('product_id', 'price')
            ->selectRaw('JSON_EXTRACT(meta, "$.seller_id") as seller_id')
            ->selectRaw('count(product_id) as count')
            ->groupBy('product_id', 'seller_id', 'price')
            ->get();

        foreach ($items as $item) {
            $api->addProduct(
                $item->product_id,
                $item->count,
                str_replace('"', '', $item->seller_id),
                $item->price
            );
        }

        try {
            $response = $api->send();
            $this->wish->order->toState(Order::STATUS_WISH_CALLBACK);
            $this->wish->update([
                'status' => 'submit',
                'server_id' => $response->id,
            ]);
        } catch (GuzzleException $exception) {
            $this->wish->update([
                'status' => 'submit_fail',
                'response' => ['status' => $exception->getCode()],
            ]);
        }
    }
}
