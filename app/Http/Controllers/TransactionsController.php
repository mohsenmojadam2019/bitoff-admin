<?php

namespace App\Http\Controllers;

use App\Api\Pay\UsdtWithdrawClient;
use App\Api\Pay\WithdrawApi;
use App\Api\TransactionApi;
use App\Http\Requests\TransactionRequest;
use App\Models\Credit;
use App\Models\Transaction;
use App\Support\Currency;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Throwable;

class TransactionsController extends Controller
{
    /**
     * @var WithdrawApi
     */
    private $api;

    /**
     * TransactionsController constructor.
     * @param TransactionApi $api
     */
    public function __construct(WithdrawApi $api)
    {
        $this->api = $api;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $transactions = Transaction::where(function ($query) use ($request) {
            if ($request->id) {
                $query->find($request->id);
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->from_amount) {
                $query->where('amount', '>=', $request->from_amount);
            }

            if ($request->to_amount) {
                $query->where('amount', '<=', $request->to_amount);
            }

            if ($request->dates) {
                $dates = explode(' - ', $request->dates);
                $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            }

            if ($request->query('currency')) {
                $query->where('currency', $request->query('currency'));
            }

            if ($request->user) {
                $query->whereHas('user', function ($q) use ($request) {
                    if (substr($request->user, 0, 1) == '@') {
                        $q->where('username', str_replace('@', '', $request->user));
                    } elseif (is_numeric($request->user)) {
                        $q->where('mobile', $request->user);
                    } else {
                        $q->where('email', $request->user);
                    }
                });
            }
        })->with('user')
            ->orderByDesc('id')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function confirm(Transaction $transaction)
    {
        if (!$transaction->isPending()) {
            return response()->json(['message' => 'Transaction is not pending now'], 422);
        }
        $api = null;
        try {
            if ($transaction->currency == Credit::CURRENCY_BTC) {
                /** @var WithdrawApi $api */
                $api = app(WithdrawApi::class);
                $response = $api->setAddress($transaction->recipient)
                    ->setAmount(Currency::toSatoshi($transaction->amount))
                    ->setFee($transaction->fee)
                    ->send();
            } else {
                /** @var UsdtWithdrawClient $api */
                $api = app(UsdtWithdrawClient::class);
                $response = $api->setAddress($transaction->recipient)
                    ->setAmount(($transaction->amount - $transaction->fee) * 1000000)
                    ->send();
            }
        } catch (Throwable $exception) {
            $code = $exception->getCode();

            if ($code === 406) {
                $transaction->update(['status' => 'credit_pending']);
                return response()->json(['message' => 'No enough credit in gateway'], $code);
            }

            return response()->json(['message' => "Cannot connect payment gateway [$code] error"], 500);
        }

        DB::beginTransaction();

        try {
            $transaction->update([
                'tx_hash' => $response->tx_hash,
                'status' => 'success'
            ]);
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            return response()->json(['message' => 'Cannot update transaction'], 500);
        }

        return response()->json(['tx' => $response->tx_hash]);
    }


    public function manual(Transaction $transaction, TransactionRequest $request)
    {
        if (!$transaction->isPending()) {
            return response()->json(['message' => 'The transaction is not pending']);
        }

        DB::beginTransaction();

        try {
            $transaction->update([
                'tx_hash' => $request->get('tx_hash'),
                'status' => 'success'
            ]);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            return response()->json(['message' => 'Cannot update transaction'], 500);
        }
    }
}
