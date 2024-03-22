@component('mail::message')
Dear **{{ $user->username }}**,

Welcome to Bitoff!

You are just one step away to complete your registration.

Confirming your email address will give you full access to Bitoff:

@component('mail::button', ['url' => $url, 'color' => 'primary'])
    Confirm your email address
@endcomponent

If you did not create this account, please ignore this message.

<a href="{{ $supportLink }}">The Bitoff Support Team</a>
@endcomponent
