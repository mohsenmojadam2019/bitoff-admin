@component('mail::message')

Dear **{{ $username }}**,

The Order ID: **{{ $order->hash }}** got canceled.

Due to different reasons, the Bitoff Support has cancelled the order.

For further information you can contact Bitoff Support Team.

<a href="{{$supportLink}}" style="color:#488cd7;">The Bitoff Support Team</a>
@endcomponent
