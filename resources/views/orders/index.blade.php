@extends('layouts.app')
@section('title', 'The orders')
@section('content')
@include('orders.partials.filter')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th style="width: 10%;">
                        Order ID
                    </th>
                    <th style="width: 10%;">
                        Users
                    </th>
                    <th style="width: 20%;">
                        Products
                    </th>
                    <th style="width: 10%;">
                        Cost
                    </th>
                    <th style="width: 10%;">
                        Currency
                    </th>
                    <th style="width: 10%;">
                        Off
                    </th>
                    <th class="text-center" style="width: 10%;">
                        Status
                    </th>
                    <th style="width: 10%;">
                        Creation
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="text-sm" style="display:flex;">
                        @if (isset($order->address['can_see_address']) && $order->address['can_see_address'])
                        <a data-toggle="tooltip" title="show full address checked" class="text-primary pr-1" href="#">
                            <span class="align-middle">
                            <i class="fas fa-circle"></i>
                            </span>
                        </a>
                        @endif
                        <a class="text-secondary" target="_blank" href="{{ route('orders.show', $order->hash) }}">
                            <u>{{ $order->hash }}</u>
                        </a>

                    </td>
                    <td class="text-sm">
                        <a target="_blank" class="text-dark" href="{{ route('users.show', $order->shopper_id) }}">
                            {{ $order->shopper->identifier }}
                        </a>
                        <br>
                        @if ($order->earner)
                        <a target="_blank" class="text-dark" href="{{ route('users.show', $order->earner_id) }}">
                            {{ $order->earner->identifier }}
                        </a>
                        @endif
                    </td>
                    <td>
                        @if($order->source === 'bitoff')
                        <ul class="list-inline">
                            @foreach($order->items->groupBy('product_id')->take(5) as $items)
                            <li data-toggle="tooltip" title="{{ optional($items[0]->product)->title }}"
                                class="list-inline-item iitem">
                                @if($items[0]->product)
                                <img style="height: 2.5rem;" alt="Avatar" class="table-avatar border"
                                     src="{{ isset($items[0]->product->images[0]['thumbnail']) ? $items[0]->product->images[0]['thumbnail'] : $items[0]->product->images[0]['medium'] ?? asset('img/bitoff.jpg') }}">
                                @endif
                            </li>
                            @endforeach
                            @if($order->items->groupBy('product_id')->count() > 5)
                            <li class="list-inline-item">
                                <span class="badge badge-secondary">+{{ $order->items->count() - 5 }}</span>
                            </li>
                            @endif
                        </ul>
                        @else
                        <img src="{{ asset('img/shops/'.$order->source.'.svg')}}">
                        @endif
                    </td>
                    <td class="text-sm">
                            @if ($order->source === 'canada')
                            <span data-toggle="tooltip" title="{{ $order->getInvoice()->net(true) }}">
                                <b>C$</b> {{ number_format($order->getInvoice()->net(true), 2) }}
                                {{-- we use true for getting net value for canada and united kingdom currencies --}}
                            </span>
                            @elseif($order->source === 'united kingdom')
                            <span data-toggle="tooltip" title="{{ $order->getInvoice()->net(true) }}">
                                <b>&#163;</b> {{ number_format($order->getInvoice()->net(true), 2) }}
                                {{-- we use true for getting net value for canada and united kingdom currencies --}}
                            </span>
                            @else
                            <span data-toggle="tooltip" title="{{ $order->getInvoice()->net() }}">
                                <b>$</b> {{ number_format($order->getInvoice()->net(), 2) }}
                            </span>
                            @endif
                            <br>
                            @if ($order->bitcoin_rate != 0)
                            <span data-toggle="tooltip" title="{{ 1 / $order->bitcoin_rate }}">
                                <i class="fab fa-btc"></i>
                                {{ number_format(1 / $order->bitcoin_rate, 2) }}
                            </span>
                            @endif
                    </td>
                    <td>
                        @if($order->currency == 'usdt')
                        <img width="15px" height="15px" src="{{ asset('currency_logo/usdt_logo.png') }}">
                        @else
                        <img width="15px" height="15px" src="{{ asset('currency_logo/bitcoin_logo.png') }}">
                        @endif
                        {{ strtoupper($order->currency) }}
                    </td>
                    <td class="project_progress">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-secondary" role="progressbar" aria-volumenow="{{ $order->off }}"
                                aria-volumemin="0" aria-volumemax="100" style="width: {{ $order->off }}%">
                            </div>
                        </div>
                        <small>
                            <small>{{ $order->off }}%</small> | <small class="text-blue">
                                @if($order->source === 'canada')
                                C$
                                @elseif($order->source === 'united kingdom')
                                &#163;
                                @else
                                $
                                @endif
                                {{
                                number_format($order->getInvoice()->profit(), 2) }}
                                save</span>
                            </small>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ trans("order.color.$order->status") }}">{{
                            trans("order.translate.$order->status") }}</span>
                    </td>
                    <td class="text-sm" data-toggle="tooltip" title="{{ $order->created_at->toDateTimeString() }}">
                        {{ $order->created_at->format('M d H:i') }}
                        <br>
                        <small>{{ $order->created_at->diffForHumans() }}</small>
                    </td>
                    <td>
                        @if($order->status == 'wish_fail' || $order->status == 'issue_founded')

                        <button type="button" data-reorder-url="{{ route('orders.reorder',$order->id) }}"
                            class="btn btn-danger btn-sm reorder-button">
                            Reorder
                        </button>
                        @endif
                    </td>
                    <td class="project-actions">
                        <a data-toggle="tooltip" title="{{ $order->activities_count }} Activities" data-placement="left"
                            class="btn btn-secondary btn-sm"
                            href="{{ route('orders.show', $order->hash) . '?action=History' }}">
                            <i class="fas fa-chart-line"></i>
                            <span style="display: inline-block;width: 25px">
                                {{ $order->activities_count }}
                            </span>
                        </a>
                        @if ($order->support)
                        <a data-toggle="tooltip" title="Contact support requested" class="text-warning" href="#"
                            class="small-box-footer"><i class="fas fa-circle"></i></a>
                        @endif
                        @if($order->fast_release)
                        <img title="Fast release" src="{{ asset('img/fast.png') }}">
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('layouts.pagination', ['data' => $orders])

@endsection


@section('script')
<script>
    $(document).on('click','.reorder-button',function(){
            httpPostRequest($(this).attr('data-reorder-url')).done(function(response){
                successAlert(response.data.msg);
                setTimeout(function(){ window.location.reload(); },2000)
            });
        });
</script>
@endsection
