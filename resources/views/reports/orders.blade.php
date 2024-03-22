@extends('layouts.app')
@section('title', 'Report Users')
@section('content')

    <div class="row mt-3">
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Last 3 month</span>
                    <span class="info-box-number">{{ number_format($numberOfOrderLastThreeMonth) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar-week"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Last Month</span>
                    <span class="info-box-number">{{ number_format($numberOfStatusLastMonth) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar-week"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Last Week</span>
                    <span class="info-box-number">{{ number_format($numberOfStatusLastWeek) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Today</span>
                    <span class="info-box-number">{{ number_format($numberOfStatusToDay) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-list-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                        All orders cost
                        <small>[without collecting cancels]</small>
                    </span>
                    <span class="info-box-number">${{ number_format($sumTotalPriceOrder) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-truck"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Delivered products</span>
                    <span class="info-box-number">{{ number_format($numberOfOrderItemDelivered) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12 mt-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-heart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                        Total Wage
                    </span>
                    <span class="info-box-number">${{ number_format($totalWage) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Order report</h3>
                </div>
                <div class="card-body">
                    <form method="get" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ request()->query('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>To Date</label>
                                <input type="date" class="form-control" name="to_date"
                                    value="{{ request()->query('to_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Order Status</label>
                                <select class="form-control" name="status">
                                    <option value>selecte...</option>
                                    @foreach(\App\Models\Order::STATUS as $status)
                                        <option
                                            {{ request()->query('status') == $status ? 'selected' : '' }} value="{{ $status }}">{{ __('order.translate.'.$status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Currency</label>
                                <select class="form-control" name="currency">
                                    <option
                                        {{ request()->query('currency') == 'btc' ? 'selected' : '' }} value="btc">btc
                                    </option>
                                    <option
                                        {{ request()->query('currency') == 'usdt' ? 'selected' : '' }} value="usdt">usdt
                                    </option>
                                    <option
                                        {{ request()->query('currency') == 'all' ? 'selected' : '' }} value="all">all
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-block">Filter</button>
                            </div>
                            <div class="col-md-1">
                                <a class="btn btn-danger" href="{{ request()->url() }}">refresh</a>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="row mt-4">

                        <div class="col-md col-sm-6 col-12">
                            <div class="info-box bg-gradient-gray">
                                <span class="info-box-icon"></span>

                                <div class="info-box-content text-center">
                                    <h5 class="info-box-text">Number of orders</h5>
                                    <span class="info-box-number">{{ number_format($numberOfOrders) }}</span>
                                    <span class="progress-description"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md col-sm-6 col-12">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fa fa-money-bill"></i></span>

                                <div class="info-box-content">
                                    <h5 class="info-box-text text-dark">Total price before off</h5>
                                    @if(request()->query('currency') != 'all')
                                        <span
                                            class="info-box-number">{{request()->query('currency') == 'usdt' ? number_format($totalPriceBeforeOff->dollar).'  usdt' : number_format($totalPriceBeforeOff->btc,8).'  btc' }}
                                        </span>
                                    @endif
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span
                                        class="progress-description">{{ number_format($totalPriceBeforeOff->dollar,2) }}  dollar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-sm-6 col-12">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fa fa-money-bill"></i></span>

                                <div class="info-box-content">
                                    <h5 class="info-box-text text-dark">Total price next off</h5>
                                    @if(request()->query('currency') != 'all')
                                        <span
                                            class="info-box-number">{{ request()->query('currency') == 'usdt' ? number_format($totalPriceNextOff->dollar).'  usdt' : number_format($totalPriceNextOff->btc,8).'  btc' }}
                                        </span>
                                    @endif
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span
                                        class="progress-description">{{ number_format($totalPriceNextOff->dollar,2) }}  dollar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md col-sm-6 col-12">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fa fa-money-bill"></i></span>

                                <div class="info-box-content">
                                    <h5 class="info-box-text text-dark">Wage</h5>
                                    @if(request()->query('currency') != 'all')
                                        <span
                                            class="info-box-number">{{ request()->query('currency') == 'usdt' ? number_format($wage->dollar).'  usdt' : number_format($wage->btc,8).'  btc' }}
                                        </span>
                                    @endif
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">{{ number_format($wage->dollar,2) }}  dollar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md col-sm-6 col-12">
                            <div class="info-box bg-gradient-orange">
                                <span class="info-box-icon"><i class="fa fa-money-bill"></i></span>

                                <div class="info-box-content">
                                    <h5 class="info-box-text text-dark">Escrow</h5>
                                    @if(request()->query('currency') != 'all')
                                        <span
                                            class="info-box-number">{{ request()->query('currency') == 'usdt' ? number_format($escrow->dollar).'  usdt' : number_format($escrow->btc,8).'  btc' }}
                                        </span>
                                    @endif
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">{{ number_format($escrow->dollar,2) }}  dollar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
    </div>
@endsection
