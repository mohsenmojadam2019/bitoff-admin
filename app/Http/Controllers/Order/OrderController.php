<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Order;
use App\Models\Track;
use App\Presentation\Order\Chat;
use App\Repository\OrderRepositoryInterface;
use App\Services\BitCoinRate;
use App\Support\Hash\HashId;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $repository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->repository = $orderRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->repository->withRelations($request);

        $usdt = Credit::CURRENCY_USDT;
        $btc = Credit::CURRENCY_BTC;

        return view('orders.index', compact('orders', 'usdt', 'btc'));
    }

    public function show($id)
    {
        return view('orders.show', compact('id'));
    }

    public function overview($id)
    {
        $order = $this->repository->find($id);

        $support = $this->repository->support($id);

        $issueMessages = $this->repository->issueMessage($id);

        return $this->ajaxResponse(
            view(
                'orders.partials.overview',
                compact('order', 'support', 'issueMessages')
            )->render()
        );
    }

    public function products($id)
    {
        $order = $this->repository->find($id);

        return $this->ajaxResponse(view('orders.partials.products', compact('order'))->render());
    }

    public function shopper($id)
    {
        $order = $this->repository->find($id);

        $shopper = $this->repository->shopper($id);

        return $this->ajaxResponse(view('orders.partials.shopper', compact('order', 'shopper'))->render());
    }

    public function earner($id)
    {
        $order = $this->repository->find($id);

        $earner = $this->repository->earner($id);

        return $this->ajaxResponse(view('orders.partials.earner', compact('order', 'earner'))->render());
    }

    public function ticket($id)
    {
        $order = $this->repository->find($id);

        return $this->ajaxResponse(view('orders.partials.tickets', compact('order'))->render());
    }

    public function history($id)
    {
        $order = $this->repository->find($id);

        return $this->ajaxResponse(view('orders.partials.history', compact('order'))->render());
    }

    public function credit($id)
    {
        $order = $this->repository->find($id);

        $credits = $this->repository->credits($id);

        return $this->ajaxResponse(view('orders.partials.credit', compact('credits', 'order'))->render());
    }

    public function chat($id, Chat $cht)
    {
        $allChats = $this->repository->allChats($id);

        $chats = $cht->toArray($allChats);

        $order = $this->repository->find($id);

        return $this->ajaxResponse(view('orders.partials.chat', compact('chats', 'order'))->render());
    }

    public function wish($id)
    {
        $order = $this->repository->find($id);

        $wishes = $this->repository->wishes($id);

        return $this->ajaxResponse(view('orders.partials.wish', compact('wishes', 'order'))->render());
    }

    public function tracks($id, HashId $hash)
    {
        $from = $this->repository->from($id);
        $orderId = $hash->encode($id);
        if ($from == Track::FROM_AMAZON) {
            $tracks = $this->repository->amazonTrack($id);
            $view = 'orders.partials.amazon_track';
        } elseif ($from == Track::FROM_OTHER) {
            $tracks = $this->repository->otherTrack($id);
            $view = 'orders.partials.other_track';
        } else {
            $tracks = null;
            $view = 'orders.partials.amazon_track';
        }

        return $this->ajaxResponse(view($view, compact('tracks', 'orderId'))->render());
    }

    public function images($id)
    {
        $view = 'orders.partials.images';
        $images = $this->repository->images($id);

        return $this->ajaxResponse(view($view, compact('images'))->render());
    }

    public function trackItems($id, $origin)
    {
        $items = $this->repository->otherTrackItem($id, $origin);

        return $this->ajaxResponse(view('orders.partials.other_track_items', compact('items'))->render());
    }

    public function reorder($id, BitCoinRate $api)
    {
        $totalPrice = $this->repository->totalPrice($id, ['api' => $api]);
        $shopperCredit = $this->repository->shopperCredit($id);

        if ($totalPrice >= $shopperCredit) {
            $this->repository->reorder($id, ['status' => $this->repository->getStatus('NO_CREDIT')]);
        } elseif ($this->repository->isNative($id)) {
            $order = $this->repository->find($id);
            $order->wishes()->create([])->dispatch();
        } else {
            $this->repository->reorder($id, ['status' => $this->repository->getStatus('P')]);
        }

        return $this->ajaxResponse([
            'msg' => 'Done',
        ]);
    }
}
