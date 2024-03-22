@extends('layouts.app')
@section('title', 'The offers')
@section('content')
@include('Mantis::offers.partials.filter')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th>
                        Offer ID
                    </th>
                    <th>
                        Offerer
                    </th>
                    <th>
                        Type
                    </th>
                    <th class="text-center">
                        Price Limit
                    </th>
                    <th>
                        Currency
                    </th>
                    <th class="text-center">
                        Payment Method
                    </th>
                    <th>
                        Off
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Creation
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                <tr>
                    <td class="text-sm" style="display:flex;">
                        <a class="text-secondary" target="_blank" href="{{ route('mantis.offers.show', $offer->hash) }}">
                            <u>{{ $offer->hash }}</u>
                        </a>

                    </td>
                    <td class="text-sm">
                        <a target="_blank" class="text-dark" href="{{ route('users.show', $offer->offerer->id) }}">
                            {{ $offer->offerer->first_name }}
                        </a>
                        <br>
                    </td>
                    <td class="text-sm">
                            {{ $offer->is_buy ? 'BUY' : 'SELL' }}
                        <br>
                    </td>
                    <td class="text-sm text-center">
                            $ {{ $offer->min === $offer->max ? $offer->min : $offer->min . ' - ' . $offer->max }}
                        <br>
                    </td>
                    <td>
                        @if($offer->currency == 'usdt')
                        <img width="15px" height="15px" src="{{ asset('currency_logo/usdt_logo.png') }}">
                        @else
                        <img width="15px" height="15px" src="{{ asset('currency_logo/bitcoin_logo.png') }}">
                        @endif
                        {{ strtoupper($offer->currency) }}
                    </td>
                    <td class="text-sm text-center">
                        {{ $offer->paymentMethod->name }}
                    </td>
                    <td class="text-sm">
                        {{ $offer->rate }}%
                    </td>
                    <td >
                        <span class="badge badge-{{ $offer->active ? 'success' : 'danger' }}">
                        {{ $offer->active ? 'active' : 'inactive' }}
                        </span>
                    </td>
                    <td class="text-sm" data-toggle="tooltip" title="{{ $offer->created_at->toDateTimeString() }}">
                        {{ $offer->created_at->format('M d H:i') }}
                        <br>
                        <small>{{ $offer->created_at->diffForHumans() }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@include('layouts.pagination', ['data' => $offers])

@endsection


@section('script')
<script>
</script>
@endsection
