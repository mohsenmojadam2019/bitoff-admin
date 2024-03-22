@inject('url', 'App\Support\FrontUrl')
@component('mail::message')

You have a ticket. Check it now by clicking the button below:

<p>
    <a style="padding:10px 18px;background:#f6b69c;border-radius: 3px;color: #333;box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);text-decoration: none;" href="{{ $url->ticket($id) }}">
        Check the Ticket
    </a>
</p>

If you have any question, our support team is just a few clicks away.

<a href="{{$supportLink}}" style="color:#488cd7;">The Bitoff Support Team</a>
@endcomponent
