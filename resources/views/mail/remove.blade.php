@inject('url', 'App\Support\FrontUrl')
@component('mail::message')
Dear **{{ $username }}**,

Your [item]({{ $url->product($item->product_id) }}) from the **{{ $order->hash }}** order ID got canceled by Bitoff support.

@if($description)
The earner cancelation’s reason is:


@component('mail::panel')
 {{ $description }}
@endcomponent


@endif

<p>
    <a style="padding:10px 18px;background:#4472c4;border-radius: 3px;color: white;box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);text-decoration: none;" href="{{ $url->order($order->hash) }}">
        Check the order
    </a>
</p>

If you have any question, our support team is just a few clicks away!

<a href="{{$supportLink}}" style="color:#488cd7;">The Bitoff Support Team</a>
@endcomponent
