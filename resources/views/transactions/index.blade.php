@extends('layouts.app')
@section('title', 'Transactions')
@section('content')
@include('transactions.filter')

<div class="card card-solid">
    <div class="card-body pb-0">
        @foreach($transactions as $transaction)
        <div class="row justify-content-center" style="margin-bottom: 15px">
            <div class="col-lg-10 col-m-12 col-sm-12 col- d-flex align-items-stretch flex-column">
                <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                        @if($transaction->type === 'deposit')
                        <span class="text-green" data-id="{{ $transaction->id }}">
                            Deposit
                            <i class="fas fa-sort-numeric-up"></i>
                        </span>
                        @elseif($transaction->type === 'withdraw')
                        @if($transaction->status !== 'success')
                        <span class="text-red text-bold">[{{ \Illuminate\Support\Str::humanize($transaction->status) }}]</span>
                        @endif
                        <span class="text-red" data-id="{{ $transaction->id }}">
                            Withdraw
                            <i class="fas fa-sort-numeric-down"></i>
                        </span>
                        @endif
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-7">
                                <h2 class="lead">
                                    {{ $transaction->amount }}
                                    <small class="text-uppercase">{{ $transaction->currency }}</small>
                                    @if($transaction->currency === 'btc')
                                    ≈ {{ number_format($transaction->amount / $transaction->rate, 2) }}
                                    <small>USD</small>
                                    @endif
                                </h2>
                                @if($transaction->currency === 'btc' && $transaction->fee)
                                <p class="text-muted text-sm" style="margin: 0 !important;">
                                    <b><u>{{ $transaction->fee }}</u></b> <small>Satushies paid for the network fee.</small>
                                </p>
                                @endif
                                <hr>

                            </div>
                        </div>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            @if($transaction->tx_hash)
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-hashtag"></i></span> <code class="text-dark">{{ $transaction->tx_hash }}</code></li>
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-check"></i></span> <code class="text-dark">{{ $transaction->confirmations }}</code></li>
                            @endif

                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-square"></i></span> <code class="text-dark">{{'IP:'. optional($transaction->ip)->ip }}</code></li>
                            @if($transaction->recipient)
                            <li class="small"><span class="fa-li"><i class="fas fa-wallet"></i></span> <code class="text-dark">{{ $transaction->recipient }}</code></li>
                            @endif

                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-user"></i></span>
                                <span>By</span>
                                <a class="text-dark" href="{{ route('users.show', $transaction->user_id) }}"><u>{{ $transaction->user->identifier }}</u></a>
                                <span>in</span>
                                <span>{{ $transaction->created_at->diffForHumans() }}</span>
                                <span>|</span>
                                <span><b>{{ $transaction->created_at->format('M d Y - H:i') }}</b></span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            @if($transaction->tx_hash)
                            @if($transaction->currency === 'btc')
                            <a href="https://btc.com/{{ $transaction->tx_hash }}" target="_blank" class="btn btn-xs btn-primary">
                                <i class="fas fa-arrow-circle-right"></i> BTC.com
                            </a>
                            @elseif($transaction->currency === 'usdt')
                            <a href="https://tronscan.org/#/transaction/{{ $transaction->tx_hash }}" target="_blank" class="btn btn-xs btn-primary">
                                <i class="fas fa-arrow-circle-right"></i> Tron Scan
                            </a>
                            @endif
                            @endif
                            @if($transaction->status === 'admin_pending')
                            <a href="#" class="btn btn-xs btn-primary confirm-tr" data-target="{{ route('transactions.confirm', $transaction->id) }}">
                                <i class="fas fa-check-circle"></i> Confirm
                            </a>
                            <a href="#" class="btn btn-xs btn-file manual-confirm" data-target="{{ route('transactions.manual', $transaction->id) }}">
                                <i class="fas fa-fingerprint"></i> Manual
                            </a>
                            @endif
                            @if($transaction->status === 'credit_pending')
                            <a href="#" class="btn btn-xs btn-primary confirm-tr" data-target="{{ route('transactions.confirm', $transaction->id) }}">
                                <i class="fas fa-redo"></i> Retry
                            </a>
                            <a href="#" class="btn btn-xs btn-file manual-confirm" data-target="{{ route('transactions.manual', $transaction->id) }}">
                                <i class="fas fa-fingerprint"></i> Manual
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- /.card-body -->
    <div class="card-footer">

    </div>
    <!-- /.card-footer -->
</div>

@include('layouts.pagination', ['data' => $transactions])

@endsection