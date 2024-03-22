@if($earner)
    <div class="table-responsive">
        <table class="table" style="background: #9c8d8d0d">
            <tbody>
            <tr>
                <th>Username</th>
                <td>
                    <a target="_blank" href="{{ route('users.show', $earner->id) }}">{{ $earner->username }}</a>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <a target="_blank" href="{{ route('users.show', $earner->id) }}">{{ $earner->email }}</a>
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <td><b>{{ $earner->first_name }} {{ $earner->last_name }}</b></td>
            </tr>
            <tr>
                <th>Register date</th>
                <td>
                    {{ $earner->created_at }}
                    &nbsp;|&nbsp;
                    <b>{{ $earner->created_at->diffForHumans() }}</b>
                </td>
            </tr>
            <tr>
                <th>Accepted at</th>
                <td>
                    {{ $order->reservation->created_at }}
                    &nbsp;|&nbsp;
                    <b>{{ $order->reservation->created_at->diffForHumans() }}</b>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <button data-target="{{ route('orders.cancel.earner', $order->hash) }}" id="rm-earner" {{ !$order->isState('reserve', 'purchase', 'partial_ship', 'ship', 'partial_deliver') ? 'disabled' : '' }} class="btn btn-warning">
        Cancel transaction
    </button>
@else
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> No one reserved the order yet!</h5>
    </div>
@endif
