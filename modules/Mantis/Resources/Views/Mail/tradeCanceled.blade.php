@inject('url', 'Bitoff\Mantis\Application\Support\MantisUrl')
@component('mail::message')

Dear {{$user_name}},

We have reviewed the dispute for trade ID {{$trade_hash_id}}. Unfortunately, the trade has been canceled after careful consideration.

Any funds held in escrow for this trade have been released back to the respective parties.

If you have any questions or concerns, please contact our support team.

To view trade details, click on the button:

@component('mail::button', ['url' => $url->trade($offer_hash_id, $trade_hash_id), 'color' => 'primary'])
Trade
@endcomponent
Best regards,Your <a href="{{$support_link}}">Bitoff Support Team</a>
@endcomponent
