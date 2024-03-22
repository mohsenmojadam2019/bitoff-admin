@extends('layouts.app')
<script>
    window.onload = function (){
        @foreach($trades->where('status', \Bitoff\Mantis\Application\Models\Trade::STATUS_ACTIVE) as $trade)
             getRemTime('{!! $trade->remainingTime() !!}','{!! $trade->id !!}');
        @endforeach
    }

    function getRemTime(remTime, tradeId) {
        // Set the date we're counting down to
        const countDownDate = new Date(remTime).getTime();

        // Update the count down every 1 second
        const x = setInterval(function () {

            // Get today's date and time
            const now = new Date().getTime();

            // Find the distance between now and the count down date
            const distance = countDownDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("remTime_" + tradeId).innerHTML = days + " : " + hours + " : "
                + minutes + " : " + seconds;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("remTime_" + tradeId).innerHTML = "expired";
            }
        }, 1000);
    }
</script>
@section('title', 'The trades')
@section('content')
    @include('Mantis::trades.partials.filter')

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th>
                        Trade ID
                    </th>
                    <th>
                        Trader
                    </th>
                    <th>
                        Offerer
                    </th>
                    <th>
                        Type
                    </th>
                    <th class="text-center">
                        Price
                    </th>
                    <th>
                        Currency
                    </th>
                    <th class="text-center">
                        Payment Method
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Creation
                    </th>
                    <th>
                        D : H : M : S / Rem Time
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($trades as $trade)
                    <tr>
                        <td class="text-sm">
                            <a class="text-secondary" target="_blank"
                               href="{{ route('mantis.trades.show', $trade->hash) }}">
                                <u>{{ $trade->hash }}</u>
                            </a>

                        </td>
                        <td class="text-sm">
                            <a class="text-secondary" target="_blank" href="{{ route('users.show', $trade->trader->id) }}">{{ $trade->trader->username }}</a>
                        </td>
                        <td class="text-sm">
                            <a class="text-secondary" target="_blank" href="{{ route('users.show', $trade->offer->offerer->id) }}">{{ $trade->offer->offerer->username }}</a>
                        </td>
                        <td class="text-sm">
                            {{ $trade->offer_data->is_buy ? 'BUY' : 'SELL' }}
                            <br>
                        </td>
                        <td class="text-sm text-center">
                            ${{ $trade->amount }}
                            <br>
                        </td>
                        <td>
                            @if($trade->offer_data->currency == 'usdt')
                                <img width="15px" height="15px" src="{{ asset('currency_logo/usdt_logo.png') }}">
                            @else
                                <img width="15px" height="15px" src="{{ asset('currency_logo/bitcoin_logo.png') }}">
                            @endif
                            {{ strtoupper($trade->offer_data->currency) }}
                        </td>
                        <td class="text-sm text-center">
                            {{ $trade->offer->paymentMethod->name }}
                        </td>
                        <td>
                        <span class="badge badge-{{ trans("Mantis::trade.color.{$trade->status}") }}">
                        {{ $trade->status }}
                        </span>
                        </td>
                        <td class="text-sm" data-toggle="tooltip" title="{{ $trade->created_at->toDateTimeString() }}">
                            {{ $trade->created_at->format('M d H:i') }}
                            <br>
                            <small>{{ $trade->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($trade->isStatus(\Bitoff\Mantis\Application\Models\Trade::STATUS_ACTIVE))
                                <span class="text-sm text-center" id="remTime_{!! $trade->id !!}"></span>
                            @endif
                        </td>
                    <td class="project-actions">
                        @if ($trade->status == \Bitoff\Mantis\Application\Models\Trade::STATUS_DISPUTE)
                        <a data-toggle="tooltip" title="Trade disputed" class="text-warning" href="#"
                            class="small-box-footer"><i class="fas fa-circle"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>



    @include('layouts.pagination', ['data' => $trades])

@endsection

@section('script')

@endsection
