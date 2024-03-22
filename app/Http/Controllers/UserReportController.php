<?php

namespace App\Http\Controllers;

use App\Grid\Users\AsEarnerGrid;
use App\Grid\Users\AsShopperGrid;
use App\Grid\Users\CreditGrid;
use App\Grid\Users\CreditUsdtGrid;
use App\Grid\Users\FeedBackGrid;
use App\Grid\Users\IPGrid;
use App\Grid\Users\OffersGrid;
use App\Grid\Users\TradeFeedbackGrid;
use App\Grid\Users\TradesGrid;
use App\Grid\Users\TransactionGrid;
use App\Models\Credit;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\UserIp;
use Bitoff\Feedback\Application\Http\Controllers\UserFeedbackController;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SrkGrid\GridView\Grid;

class UserReportController extends Controller
{
    /**
     * Base route report whiteout action
     *
     * @author Reza Sarlak
     * @var string
     */
    protected $baseRoute = '';
    /**
     * Store result query
     *
     * @author Reza Sarlak
     * @var object
     */
    protected $data;
    /**
     * link action users
     *
     * @author Reza Sarlak
     * @var array
     */
    protected $links = [
        [
            'action' => 'order_as_earner',
            'caption' => 'Orders as Earner',
        ],
        [
            'action' => 'order_as_shopper',
            'caption' => 'Orders as Shopper',
        ],
        [
            'action' => 'offers',
            'caption' => 'Offers',
        ],
        [
            'action' => 'trades',
            'caption' => 'Trades',
        ],
        [
            'action' => 'feedbacks',
            'caption' => 'Order Feedbacks',
        ],
        [
            'action' => 'tradeFeedbacks',
            'caption' => 'Trade Feedbacks',
        ],
        [
            'action' => 'credits_btc',
            'caption' => 'Credits as BTC',
        ],
        [
            'action' => 'credits_usdt',
            'caption' => 'Credits as USDT',
        ],
        [
            'action' => 'transactions',
            'caption' => 'Transactions',
        ],
        [
            'action' => 'ips',
            'caption' => 'IP'
        ]
    ];

    public function __invoke(Request $request, $id)
    {
        $action = Str::camel($request->action);

        $this->baseRoute = route('users.show', $id);

        return method_exists($this, $action) ? $this->$action($request) : $this->defaultAction();
    }

    /*-------------------------------------------------------------------------------------
     * Helper Method
     * ------------------------------------------------------------------------------------
     */

    private function ips($request)
    {
        $this->data = UserIp::query()->latest()->where('user_id', $request->id);

        return $this->finalResponse(IPGrid::class, $request);
    }

    private function orderAsEarner($request)
    {
        $this->data = Order::with(['earner', 'shopper'])->where('earner_id', '=', $request->id)->latest();

        return $this->finalResponse(AsEarnerGrid::class, $request);
    }

    private function orderAsShopper($request)
    {
        $this->data = Order::with(['earner', 'shopper'])->where('shopper_id', '=', $request->id)->latest();

        return $this->finalResponse(AsShopperGrid::class, $request);
    }

    private function offers($request)
    {
        $this->data = Offer::with(['offerer', 'paymentMethod'])->where('offerer_id', $request->id)->latest();

        return $this->finalResponse(OffersGrid::class, $request);
    }

    private function trades($request)
    {
        $userId = $request->id;

        $this->data = Trade::with(['trader', 'offer', 'offer.offerer'])
            ->where('trader_id', '=', $userId)
            ->orWhereHas('offer', function ($query) use ($userId) {
                $query->where('offerer_id', $userId);
            })->latest();

        return $this->finalResponse(TradesGrid::class, $request);
    }

    private function feedbacks($request)
    {
        $this->data = Feedback::with(['fromUser', 'toUser'])
            ->where('from_user_id', '=', $request->id)
            ->orWhere('to_user_id', '=', $request->id)->latest();

        return $this->finalResponse(FeedBackGrid::class, $request);
    }

    private function tradeFeedbacks($request)
    {
        $this->data = Feedback::with(['fromUser', 'toUser', 'feedbackable','feedbackable.offer'])->
              where('feedbackable_type', 'Bitoff\\Mantis\\Application\\Models\\Trade')
            ->orWhere('feedbackable_type', 'Bitoff\\Mantis\\Application\\Models\\Offer')
            ->where(function ($query) use ($request) {
                $query->where('from_user_id', $request->id)
                    ->orWhere('to_user_id', $request->id);
            })
            ->latest();
        return $this->finalResponse(TradeFeedbackGrid::class, $request);
    }

    private function creditsBtc($request)
    {
        $this->data = Credit::with(['user', 'creditable'])
            ->where('currency', '=', Credit::CURRENCY_BTC)
            ->where('user_id', '=', $request->id)
            ->latest('id');

        return $this->finalResponse(CreditGrid::class, $request);
    }

    private function creditsUsdt($request)
    {
        $this->data = Credit::with(['user', 'creditable'])
            ->where('currency', '=', Credit::CURRENCY_USDT)
            ->where('user_id', '=', $request->id)
            ->latest();

        return $this->finalResponse(CreditUsdtGrid::class, $request);
    }

    private function transactions($request)
    {
        $this->data = Transaction::with(['user'])->where('user_id', '=', $request->id)->latest();

        return $this->finalResponse(TransactionGrid::class, $request);
    }

    private function defaultAction(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to($this->baseRoute . '?action=order_as_earner');
    }


    private function createGrid($class): string
    {
        return Grid::make($class, $this->data, ['baseRoute' => $this->baseRoute]);
    }

    protected function finalResponse($class, $request)
    {
        $view = $this->createGrid($class);
        return request()->ajax() ?
            response()->json(['status' => '100', 'data' => $view]) :
            app(UserFeedbackController::class)->showFeedback($view, $request, $this->baseRoute, $this->data, $this->links);
    }

}
