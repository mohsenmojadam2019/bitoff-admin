@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<form>
    <div class="row">
        <div class="col-md-4">
            <label>From</label>
            <input type="date" class="form-control" name="from" value="{{ request()->query('from') }}">
        </div>
        <div class="col-md-4">
            <label>To</label>
            <input type="date" class="form-control" name="to" value="{{ request()->query('to') }}">
        </div>
        <div class="col-md-4 float-right" style="margin-top: 32px;">
            <button class="btn btn-primary">Filter</button>
            <a href="/" class="btn btn-danger">Refresh</a>
        </div>
    </div>
</form>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Btc Order</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($ordersWithBtcCurrency as $orderWithBtcCurrency)
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="{{__('order.dashboard.'.$orderWithBtcCurrency->status)}}">
                            <div class="info-box-content">
                                <h5 class="info-box-text">{{ __('order.translate.'.$orderWithBtcCurrency->status) }}</h5>
                                <span class="info-box-number">{{ $orderWithBtcCurrency->numberOfStatus }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Usdt Order</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($ordersWithUsdtCurrency as $orderWithUsdtCurrency)
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="{{__('order.dashboard.'.$orderWithUsdtCurrency->status)}}">
                            <div class="info-box-content">
                                <h5 class="info-box-text">{{ __('order.translate.'.$orderWithUsdtCurrency->status) }}</h5>
                                <span class="info-box-number">{{ $orderWithUsdtCurrency->numberOfStatus }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Report</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin orders have been accepted at least one time</h5>
                                <span class="info-box-number">{{ $ordersBtcAccepted }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt orders have been accepted at least one time</h5>
                                <span class="info-box-number">{{ $ordersUsdtAccepted }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin Orders in Waiting For Earner</h5>
                                <span class="info-box-number">{{ $ordersBtcWaitForEarner }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt Orders in Waiting For Earner</h5>
                                <span class="info-box-number">{{ $ordersUsdtWaitForEarner }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin orders have been supported by earner</h5>
                                <span class="info-box-number">{{ $ordersBtcByEarnerSupported }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt orders have been supported by earner</h5>
                                <span class="info-box-number">{{ $ordersUsdtByEarnerSupported }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin orders have been supported by shopper</h5>
                                <span class="info-box-number">{{ $ordersBtcByShopperSupported }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt orders have been supported by shopper</h5>
                                <span class="info-box-number">{{ $ordersUsdtByShopperSupported }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin orders have been supported and resolved</h5>
                                <span class="info-box-number">{{ $ordersBtcSupportedAndResolved }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt orders have been supported and resolved</h5>
                                <span class="info-box-number">{{ $ordersUsdtSupportedAndResolved }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin orders that Earner left them</h5>
                                <span class="info-box-number">{{ $ordersBtcCanceled }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt orders that Earner left them</h5>
                                <span class="info-box-number">{{ $ordersUsdtCanceled }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Bitcoin Orders in Issue Founded</h5>
                                <span class="info-box-number">{{ $ordersBtcIssueFounded }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <div class="info-box-content">
                                <h5 class="info-box-text">Usdt Orders in Issue Founded</h5>
                                <span class="info-box-number">{{ $ordersUsdtIssueFounded }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection