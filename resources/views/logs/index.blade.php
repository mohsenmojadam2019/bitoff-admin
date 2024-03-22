@extends('layouts.app')
@section('title', "Activities [{$logs->total()}]")
@section('content')
<div class="row" style="padding: 15px 0;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center table-primary" colspan=2>BTC</td>
                            <td class="text-center table-danger" colspan=2>USDT</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> <span title="number of waiting for earner btc order" class="badge badge-primary">{{ $countWaitingForEarnerBtc }}</span></td>
                            <td><span title="total btc price" class="badge badge-primary">{{ '$ '.number_format($totalBtcPrice) }}</span></td>
                            <td><span title="total of waiting for earner usdt order" class="badge badge-danger">{{ $countWaitingForEarnerUsdt }}</span></td>
                            <td><span title="total usdt price" class="badge badge-danger">{{ '$ '.number_format($totalUsdtPrice) }}</span></td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div>
    <form>
        <div class="col-md-12">
            <div class="card">
                <div class='card-body'>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" value="{{ request()->query('from_date') }}" name="from_date">
                        </div>
                        <div class="col-md-3">
                            <label for="">To Date</label>
                            <input type="date" class="form-control" value="{{ request()->query('to_date') }}" name="to_date">
                        </div>
                        <div class="col-md-3">
                            <label for="">Type</label>
                            <select class="form-control" name="type">
                                <option value>Select...</option>
                                <option @if(request()->query('type') == '1') selected @endif value="1">Left the order</option>
                                <option @if(request()->query('type') == '2') selected @endif value="2">Reserve</option>
                                <option @if(request()->query('type') == '3') selected @endif value="3">Canceled the order</option>
                                <option @if(request()->query('type') == '4') selected @endif value="4">Purchased item</option>
                                <option @if(request()->query('type') == '5') selected @endif value="5">Edit purchased item</option>
                                <option @if(request()->query('type') == '6') selected @endif value="6">Added tracking</option>
                                <option @if(request()->query('type') == '7') selected @endif value="7">Edit tacking</option>
                                <option @if(request()->query('type') == '8') selected @endif value="8">Warns delivered</option>
                                <option @if(request()->query('type') == '9') selected @endif value="9">Wish pending</option>
                                <option @if(request()->query('type') == '10') selected @endif value="10">Item canceled</option>
                                <option @if(request()->query('type') == '11') selected @endif value="11">Updated off</option>
{{--  todo--}}
                                <option @if(request()->query('type') == '12') selected @endif value="12">Score</option>
                                <option @if(request()->query('type') == '13') selected @endif value="13">Kicked earner</option>
                                <option @if(request()->query('type') == '14') selected @endif value="14">Request for support</option>
                                <option @if(request()->query('type') == '15') selected @endif value="15">Resolved support request</option>
                                <option @if(request()->query('type') == '16') selected @endif value="16">Uploaded an Image</option>
                                <option @if(request()->query('type') == '17') selected @endif value="17">Waiting for Earner</option>
                                <option @if(request()->query('type') == '18') selected @endif value="18">Completed</option>
                                <option @if(request()->query('type') == '19') selected @endif value="19">Issue Founded</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button class="btn btn-primary">Filter</button>
                            <a href="{{request()->url()}}" class="btn btn-danger">Refresh</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row" style="padding: 15px 0;">
    <div class="col-md-12">
        @if ($logs->count())
        <div class="timeline">
            @foreach ($logs as $log)
            <div>
                <i class="fas" style="background: #dee2e6">
                    @if ($log->role == 'earner' || $log->role == 'shopper')

                    <img style="width: 100%; height: 100%" src="{{ asset("img/{$log->role}.png") }}">
                    @else
                    <img style="width: 100%; height: 100%" src="{{ asset('img/round-logo.svg') }}">
                    @endif
                </i>
                <div class="timeline-item">
                    <span class="time">
                        @if ($log->order->fast_release)
                        <img src="{{ asset('img/fast.png') }}">
                        @endif
                        {{ $log->created_at->format('y M d - H:i') }}
                        | <a target="_blank" href="{{ route('orders.show', $log->order->hash) }}">{{ $log->order->hash }}</a>
                    </span>

                    <h3 class="timeline-header">
                        @if ($log->role && $log->user_id)
                        <a target="_blank" href="{{ route('users.show', $log->user_id) }}">
                            {{ $log->user->identifier }}
                        </a>
                        @else
                        System
                        @endif
                        @include('logs.title')
                    </h3>
                    @if (in_array($log->type, ['image','support', 'off', 'item.purchase', 'item.purchase.edit', 'item.ship', 'item.ship.edit']))
                    @include('logs.contents')
                    @elseif(isset($log->changes['description']))
                    @include('logs.contents')
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i>There is no changes.</h5>
        </div>
        @endif
    </div>
</div>

@include('layouts.pagination', ['data' => $logs])

@endsection
@section('script')
<script>
    $(document).on('click', '.show-big-image', function() {
        htmlImage = "<img src='" + $(this).attr('bit-image') + "'>";
        showModal({
            title: 'Image',
            body: htmlImage
        })
    })
</script>
@endsection
