@component('mail::message')
<img src="{{ $image }}">
Hi {{ $user->username }}

You have a new chat message waiting for your response.

Order ID chatbox: {{ $order->hash }}

You can check and respond to your message via the link below:

@component('mail::button', ['url' => $orderUrl, 'color' => 'primary'])
    View Message
@endcomponent

<p>
Note: This email was sent based on automation; please do not respond.
Here is <a href="{{ $contactUrl }}">Ways to contact the bitoff support team</a>
</p>
@endcomponent
