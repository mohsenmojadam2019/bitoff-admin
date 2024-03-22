@if ($log->type == 'status')
    @if ($log->type == 'status' && in_array($log->changes['status'], ['credit_pending', 'wish_pending']))
        Started order with <b>@lang("order.translate.{$log->changes['status']}")</b> status
    @elseif($log->changes['status'] == 'cancel' && $log->role == 'earner')
        Left the order
    @else
        changes status to <b>@lang("order.translate.{$log->changes['status']}")</b>
    @endif
@elseif($log->type == 'reserve')
    Accepted the order
@elseif($log->type == 'cancel' && $log->role == 'shopper')
    Canceled the order
@elseif($log->type == 'item.purchase')
    Purchased <kbd>#{{ $log->changes['item'] }}</kbd>
@elseif($log->type == 'item.purchase.edit')
    Edited <kbd>#{{ $log->changes['item'] }}</kbd> Purchase info
@elseif($log->type == 'item.ship')
    Added tracking for <kbd>#{{ $log->changes['item'] }}</kbd>
@elseif($log->type == 'item.ship.edit')
    Edited <kbd>#{{ $log->changes['item'] }}</kbd> Tracking
@elseif($log->type == 'item.deliver')
    Warns <kbd>#{{ $log->changes['item'] }}</kbd> has been <b>delivered</b>
@elseif($log->type == 'item.cancel')
    Canceled <kbd>#{{ $log->changes['item'] }}</kbd>
@elseif($log->type == 'off')
    Updated off to <b>{{ $log->changes['off'] }}</b>%
{{--    todo--}}
@elseif($log->type == 'score')
    Scored <span class="">
        @for ($i=0; $i < 5; $i++)
            <i class="fa fa-star {{$i < $log->changes['score'] ? 'star-active' : '' }}"></i>
        @endfor
    </span>

@elseif($log->type == 'expire')
    Kicked earner
@elseif($log->type == 'support')
    Requested for support
@elseif($log->type == 'support.resolve')
    Resolved support request
@endif
@if(isset($log->changes['status']) && ($log->changes['status'] == $order::STATUS_PURCHASE || $log->changes['status'] == $order::STATUS_CANCEL))
    @if($log->order->isNative() && $log->order->bitcoin_rate)
        <b class="text-success">BTC</b><b class="text-info"> %{{ $log->order->off }}</b><b class="text-success"> ( ${{ number_format($log->order()->first()->getInvoice()->net(),2) }} )</b>
    @else
        <b class="text-danger">USDT</b><b class="text-info"> {{ $log->order->off }} %</b><b class='text-danger'> ( ${{ number_format($log->order->getInvoice()->net(),2) }})</b>
    @endif
@endif
@if(($log->type) == 'image')
     Uploaded an Image
@endif
