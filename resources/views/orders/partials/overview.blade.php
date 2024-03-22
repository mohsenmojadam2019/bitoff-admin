@if($support)
    <div class="alert alert-warning">
        <h6>
            <i class="icon fas fa-exclamation-triangle"></i>
            Contact support activated by <b>{{ $support->role }}</b>
            at {{ $support->created_at->format('Y-M-d - h:m') }}
            <button data-target="{{ route('orders.resolve', $order->hash) }}" id="resolve" class="btn btn-xs"
                    style="background-color: #bf9825;">Resolve
            </button>
        </h6>
    </div>
@endif
@if($order->status == \App\Models\Order::STATUS_ISSUE_FOUNDED)
    <div class="pointer alert alert-danger show-issue-message">
        Show Issue Messages
    </div>
    <table class="table table-bordered issue-message" style="display: none;margin-top:0px">
        <tbody>
        @foreach($issueMessages as $issue)
            <tr @if($loop->iteration%2 != 0) class='table-danger' @endif>
                <td>{{ optional($issue->reserve->user)->username }}</td>
                <td>{{ $issue->changes['description'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>

@endif
<div class="table-responsive">
    <table class="table table-bordered" style="background: #9c8d8d0d">
        <tbody>
        <tr>
            <th>At</th>
            <td>
                <b data-toggle="tooltip"
                   title="{{ $order->created_at }}">{{ $order->created_at->format('M d H:i') }}</b>
                |
                <span>{{ $order->created_at->diffForHumans() }}</span>
                @if($order->fast_release)
                    <span><img src="{{ asset('img/fast.png') }}" alt=""></span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <b class="badge badge-{{ trans("order.color.{$order->status}") }}">{{ trans("order.translate.{$order->status}") }}</b>
            </td>
        </tr>
        @if($order->status == 'reserve')
            <tr>
                <th>Reserved at</th>
                <td>
                    @if($order->reserved_at)
                        <span>{{ $order->reserved_at }}</span>
                        |
                        <b>{{ $order->reserved_at->diffForHumans() }}</b>
                    @endif
                </td>
            </tr>
        @endif
        <tr>
            <th>Off</th>
            <td><b>{{ $order->off }}</b> %</td>
        </tr>
        <tr>
            <th>USD Net</th>
            <td>
                <span title="" data-placement="left" data-toggle="tooltip">~</span>
                <b>{{ $order->getInvoice()->net() }}</b>
            </td>
        </tr>
        @if($order->source === 'canada')
        <tr>
            <th>CAD Net</th>
            <td>
                <span title="" data-placement="left" data-toggle="tooltip">~</span>
                <b>{{ $order->getInvoice()->net(true) }}</b>
                {{-- we use true for getting net value for canada and united kingdom currencies --}}
            </td>
        </tr>
        @endif
        @if($order->source === 'united kingdom')
        <tr>
            <th>GBP Net</th>
            <td>
                <span title="" data-placement="left" data-toggle="tooltip">~</span>
                <b>{{ $order->getInvoice()->net(true) }}</b>
                {{-- we use true for getting net value for canada and united kingdom currencies --}}
            </td>
        </tr>
        @endif
        @if($order->currency == 'usdt')
            <tr>
                <td><b>Usdt</b></td>
                <td><b>{{ $order->getInvoice()->net() }}</b></td>
            </tr>
        @else
            <tr>
                <th>BTC Net</th>
                <td>
                    @if($order->bitcoin_rate)
                        <b>{{ $order->getInvoice()->btc($order->bitcoin_rate)->net() }}</b>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>BTC Rate</th>
                <td>
                    @if($order->bitcoin_rate)
                        <b>{{ $order->bitcoin_rate }}</b>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @if(in_array($order->source,['canada','united kingdom']))
            <tr>
                @if($order->source=='canada')
                <th>BTC-CAD Rate</th>
                @else
                <th>BTC-GBP Rate</th>
                @endif
                <td>
                    @if($order->items->first()->meta['origin_bitcoin_rate'])
                        <b>{{ $order->items->first()->meta['origin_bitcoin_rate'] }}</b>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endif
        @endif
        <tr>
            <th>Shopper Wage percent</th>
            <td>% <b data-toggle="tooltip">
                    {{ number_format($order->shopper_wage_percent, 2) }}
                </b></td>
        </tr>
        <tr>
            <th>Shopper Wage as USD</th>
            <td>$ <b data-toggle="tooltip"
                     title="{{ $order->getInvoice()->getWagePercent() }} % | {{ number_format($order->getInvoice()->usd()->wage(), 10) }}">
                    {{ number_format($order->getInvoice()->usd()->wage(), 5) }}
                </b></td>
        </tr>
        @if($order->bitcoin_rate && $order->currency == \App\Models\Credit::CURRENCY_BTC)
            <tr>
                <th>shopper Wage as BTC</th>
                <td>$ <b data-toggle="tooltip"
                         title="{{ $order->getInvoice()->getWagePercent() }} % | {{ $order->getInvoice()->btc($order->bitcoin_rate)->wage() }}">
                        {{ number_format($order->getInvoice()->btc($order->bitcoin_rate)->wage(), 8) }}
                    </b></td>
            </tr>
        @endif
        <tr>
            <th>Earner Wage percent</th>
            <td>% <b data-toggle="tooltip">
                    {{ number_format($order->earner_wage_percent, 2) }}
                </b></td>
        </tr>
        <tr>
            <th>Earner Wage as USD</th>
            <td>$ <b data-toggle="tooltip"
                     title="{{ $order->getInvoice()->getEarnerWagePercent() }} % | {{ number_format($order->getInvoice()->usd()->earnerWage(), 10) }}">
                    {{ number_format($order->getInvoice()->usd()->earnerWage(), 5) }}
                </b></td>
        </tr>
        @if($order->bitcoin_rate && $order->currency == \App\Models\Credit::CURRENCY_BTC)
            <tr>
                <th>Earner Wage as BTC</th>
                <td>$ <b data-toggle="tooltip"
                         title="{{ $order->getInvoice()->getEarnerWagePercent() }} % | {{ $order->getInvoice()->btc($order->bitcoin_rate)->earnerWage() }}">
                        {{ number_format($order->getInvoice()->btc($order->bitcoin_rate)->earnerWage(), 8) }}
                    </b></td>
            </tr>
        @endif
        <tr>
        <tr>
            <th>Local id</th>
            <td><b>{{ $order->id }}</b></td>
        </tr>
        @if($order->isNative())
            <tr>
                <th>Amazon wish list</th>
                <td>
                    @if(isset($order->meta['wish_id']) && isset($order->meta['wish_link']))
                        <a target="_blank" href="{{ $order->meta['wish_link'] }}">{{ $order->meta['wish_id'] }}</a>
                    @else
                        <span>-</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Amazon wish list new</th>
                <td>
                    @if(isset($order->meta['wish_id']))
                        <a target="_blank" href="https://www.amazon.com/hz/wishlist/ls/{{ $order->meta['wish_id'] }}">{{ $order->meta['wish_id'] }}</a>
                    @else
                        <span>-</span>
                    @endif
                </td>
            </tr>
        @endif
{{--        todo--}}
        @if($order->feedbacks)
            @include('orders.partials.score')
        @endif

        </tbody>
    </table>
</div>
