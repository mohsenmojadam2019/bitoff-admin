@if($disputeData)
    <div class="alert alert-warning">
        <h6>
            <i class="icon fas fa-exclamation-triangle"></i>
            Trade disputed by ({{ $disputeData->causer }}) <b> {{ $disputeData->user->username }} </b>
            at {{ $disputeData->created_at->format('Y-M-d - h:m') }}
            <button data-target="{{ route('mantis.trades.resolve', $trade->hash) }}" id="resolve" class="btn btn-xs"
                    style="background-color: #bf9825;">Resolve
            </button>
        </h6>
    </div>
@endif
<div class="table-responsive">
    <table class="table table-bordered" style="background: #9c8d8d0d">
        <tbody>
        <tr>
            <th>At</th>
            <td>
                <b data-toggle="tooltip"
                   title="{{ $trade->created_at }}">{{ $trade->created_at->format('M d H:i') }}</b>
                |
                <span>{{ $trade->created_at->diffForHumans() }}</span>
            </td>
        </tr>
        @if($trade->isStatus(\Bitoff\Mantis\Application\Models\Trade::STATUS_ACTIVE))
            <tr>
                <th>D : H : M : S / Rem Time</th>
                <td class="text-bold" id="remTime"></td>
            </tr>
        @endif
        <tr>
            <th>Status</th>
            <td>
                <span class="badge text-bold badge-{{ trans("Mantis::trade.color.{$trade->status}") }}">
                    {{ $trade->status }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td><b>{{ $trade->offer->paymentMethod->name }}</b></td>
        </tr>
        <tr>
            <th>Type</th>
            <td>
                <b>{{ $trade->offer_data->is_buy ? 'Buy' : 'Sell' }}</b>
            </td>
        </tr>
        <tr>
            <th>Currency</th>
            <td><b>{{ $trade->offer_data->currency }}</b></td>
        </tr>
        <tr>
            <th>Price Limit</th>
            <td><b>${{ number_format($trade->amount, 2) }}</b></td>
        </tr>


        <tr>
            <th>Rate</th>
            <td><b>{{ $trade->offer_data->rate }}</b></td>
        </tr>

        <tr>
            <th>Fee ( % )</th>
            <td><b>{{ number_format($trade->fee, 8) }}</b> ( {{ $trade->offer_data->fee }} )</td>
        </tr>

        <tr>
            <th>USD Fee</th>
            @if($trade->offer_data->currency === 'btc')
                <td><b>{{ number_format($trade->fee / $trade->bitcoin_rate, 2) }}</b></td>
            @else
                <td><b>{{ number_format($trade->fee, 2) }}</b></td>
            @endif
        </tr>

        <tr>
            <th>USD net</th>
            <td><b>{{ $trade->net_amount_in_usd ? number_format($trade->net_amount_in_usd, 2) : null}}</b></td>
        </tr>

        @if($trade->offer_data->currency === 'btc')
            <tr>
                <th>BTC net</th>
                <td><b>{{ $trade->net_amount ? number_format($trade->net_amount, 8) : null}}</b></td>
            </tr>

            <tr>
                <th>BTC Rate</th>
                <td><b>{{ $trade->bitcoin_rate ? number_format($trade->bitcoin_rate, 8) : null}}</b></td>
            </tr>
        @endif

        <tr>
            <th>Terms</th>
            <td>{{ $trade->offer_data->terms }}</td>
        </tr>
        <tr>
            <th>Local id</th>
            <td><b>{{ $trade->id }}</b></td>
        </tr>
        </tbody>
    </table>
@if($disputeData)
    <hr>
    <div class="btn-group">
        <div>
            <button title="Cancel trade" type="button" class="btn btn-danger" onclick="cancelTradeBtnClink()" style="margin-right: 10px;">Cancel trade</button>
        </div>
        <form action="{{ route('mantis.trades.send_crypto',$trade->hash) }}" method="POST">
            @csrf
            @method('patch')
            <button title="enable" type="submit" class="btn btn-info ajax-form-request" style="margin-left: 10px;">Release trade</button>
        </form>
    </div>
@endif

</div>

<script>
    function cancelTradeBtnClink() {
        Swal.fire({
            title: 'Submit cancel reason',
            input: 'textarea',
            inputPlaceholder: 'Type your reason here...',
            showCancelButton: true,
            confirmButtonText: 'Cancel',
            confirmButtonColor: '#ff0000',
            cancelButtonText: "Never mind",
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                let headers = new Headers();
                headers.append("Content-Type", "application/json");
                headers.append("Accept", "application/json");
                headers.append("X-CSRF-TOKEN", "{{ csrf_token() }}");
                headers.append("X-Requested-With", "XMLHttpRequest");

                let requestData = {
                    method: 'PATCH',
                    headers: headers,
                    body: JSON.stringify({
                        "reason": reason
                    }),
                    redirect: 'follow'
                }

                return fetch("{{ route('mantis.trades.cancel',$trade->hash) }}", requestData)
                    .then(response => {
                        if (!response.ok) { throw response}
                        return response.json()
                    })
                    .catch(async (error) => {
                        const errorData = await error.json()
                        Swal.showValidationMessage(
                            'Error : ' +
                            errorData.errors['reason'][0]
                            ?? errorData.message
                            ?? error.statusText
                        )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Well done',
                    result.value.msg ?? "Trade canceled successfully",
                    'success'
                )

                toOverviewTabContent()
            }
        })
    }

    document.addEventListener('ajaxStoreOnSuccess', () => {
        toOverviewTabContent()
    })

    function toOverviewTabContent() {
        fetch("{{ route('mantis.trades.overview', $trade->hash) }}")
            .then(response => {
                return response.json()
            })
            .then(html => {
                document.getElementById('show-trade-content').innerHTML = html.data;
            })
            .catch(() => {
                window.location.reload()
            })
    }
</script>

@if($trade->isStatus(\Bitoff\Mantis\Application\Models\Trade::STATUS_ACTIVE))
    <script>
        var countDownDate = new Date("{{ $trade->remainingTime() }}").getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("remTime").innerHTML = days + " : " + hours + " : "
                + minutes + " : " + seconds;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("remTime").innerHTML = "expired";
            }
        }, 1000);
    </script>
@endif
