<?php

namespace App\Repository\Eloquent;

use App\Models\Credit;
use App\Models\Order;
use App\Models\Product;
use App\Models\Track;
use App\Repository\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    public function getStatus($status)
    {
        /** @var Order $order */
        $order = $this->model;

        return $order::STATUS[$status];
    }

    public function isNative($id)
    {
        return $this->model->find($id)->isNative();
    }

    public function support($id)
    {
        $order = $this->find($id);

        if ($order->support) {
            return $order->logs()->where('reserve_id', $order->reserve_id)->where('type', 'support')->first();
        }

    }

    public function issueMessage($id)
    {
        $order = $this->find($id);

        if ($order->status == Order::STATUS_ISSUE_FOUNDED) {
            return $order->logs()->where('changes->status', Order::STATUS_CANCEL)->get();
        }
    }

    public function shopper($id)
    {
        return $this->find($id)->shopper;
    }

    public function earner($id)
    {
        return $this->find($id)->earner;
    }

    public function credits($id)
    {
        return optional($this->find($id)->reservation)->credits;
    }

    public function reservations($id)
    {
        return $this->find($id)->reservations;
    }

    public function allChats($id)
    {
        return $this->find($id)->chats()->orderBy('created_at')->get();
    }

    public function wishes($id)
    {
        return $this->find($id)->wishes;
    }

    public function tracking($id)
    {
        if ($this->find($id)->tracks) {
            return $this->find($id)->tracks;
        }
        if ($this->find($id)->reservation && $this->find($id)->reservation->tracks) {
            return $this->find($id)->reservation->tracks;
        }
    }

    public function images($id)
    {
        return optional($this->find($id)->reservation)->images;
    }

    public function from($id)
    {
        if ($this->find($id)->tracks()->count()) {
            return Track::FROM_OTHER;
        }
        if ($this->find($id)->reservation && $this->find($id)->reservation->tracks) {
            return Track::FROM_AMAZON;
        }
    }

    public function amazonTrack($id)
    {
        return $this->find($id)->reservation->tracks;
    }

    public function otherTrack($id)
    {
        return $this->find($id)->reservation->tracks()->where('order_item_id', '<>', 0)->get()->unique('origin');
    }

    public function otherTrackItem($id, $origin)
    {
        return $this->find($id)->tracks()->where('origin', $origin)->first()->items;
    }

    public function totalPrice($id, $option = [])
    {
        $order = $this->find($id);
        if ($order->currency == Credit::CURRENCY_BTC && $order->bitcoin_rate) {
            return $order->getInvoice()->btc($order->bitcoin_rate)->net();
        }
        if ($order->currency == Credit::CURRENCY_BTC) {
            return $order->getInvoice()->btc($option['api']->getValue())->net();
        }

        return $order->getInvoice()->net();

    }

    public function shopperCredit($orderId)
    {
        $order = $this->find($orderId);

        return $this->shopper($orderId)->getCreditSum($order->currency);
    }

    public function reorder($id, $attributes)
    {
        $this->model->whereIn('status', [
            $this->getStatus('WL_FAIL'),
            $this->getStatus('ISSUE'),
        ])->find($id)->update($attributes);
    }

    public function countCondition($condition, $currency = null)
    {
        $model = $this->getOrderWithCondition($condition, $currency);

        if ($currency) {
            $model->where('currency', '=', $currency);
        }

        return $model->count();
    }

    public function getOrderWithCondition($condition, $currency = null)
    {
        $model = $this->model->where('status', $condition);

        if ($currency) {
            $model->where('currency', '=', $currency);
        }

        return $model->get();
    }

    public function withRelations($request)
    {
        return $this->model->where(function ($query) use ($request) {
            if ($request->id) {
                $query->find($request->id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->query('from_date')) {
                $query->whereDate('created_at', '>=', $request->query('from_date'));
            }

            if ($request->query('to_date')) {
                $query->whereDate('created_at', '<=', $request->query('to_date'));
            }

            if ($request->has('issue')) {
                $query->where('status', Order::STATUS_PENDING)
                    ->whereIn('id', function ($query) {
                        $query->select('order_id')->from('order_logs')->where('changes->status', 'cancel');
                    })
                    ->whereNull('earner_id');
            }

            if ((bool) $request->get('support')) {
                $query->where('support', 1);
            }

            if ($request->fast) {
                $query->where('fast_release', 1);
            }

            if ($request->shopper) {
                $query->whereHas('shopper', function ($q) use ($request) {
                    if (substr($request->shopper, 0, 1) == '@') {
                        $q->where('username', str_replace('@', '', $request->shopper));
                    } elseif (is_numeric($request->shopper)) {
                        $q->where('mobile', $request->shopper);
                    } else {
                        $q->where('email', $request->shopper);
                    }
                });
            }

            if ($request->earner) {
                $query->whereHas('earner', function ($q) use ($request) {
                    if (substr($request->earner, 0, 1) == '@') {
                        $q->where('username', str_replace('@', '', $request->earner));
                    } elseif (is_numeric($request->earner)) {
                        $q->where('mobile', $request->earner);
                    } else {
                        $q->where('email', $request->earner);
                    }
                });
            }
            if ($request->currency) {
                $query->where('currency', $request->currency);
            }
        })
            ->with('earner', 'shopper', 'items.product')
            ->whereHas('items', function ($query) use ($request) {
                $query->when($request->query('amazon_id'), function ($q) use ($request) {
                    $q->where('product_id', $request->query('amazon_id'));
                });
                $query->when($request->query('amazon_title'), function ($q) use ($request) {
                    $products = Product::where('title', 'like', '%'.$request->query('amazon_title').'%')
                        ->get()
                        ->pluck('amazon_id')
                        ->toArray();
                    $q->whereIn('product_id', $products);
                });
            })
            ->withCount('activities')
            ->orderByDesc('id')
            ->paginate(15);
    }
}
