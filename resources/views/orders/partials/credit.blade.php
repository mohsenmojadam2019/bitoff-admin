@if($credits)
<div class="card">
    <div class="card-body p-0">
        <table class="table">
            <thead>
            <tr>
                <th>Amount</th>
                <th>User</th>
                <th>Activity</th>
                <th>At</th>
            </tr>
            </thead>
            <tbody>
                @foreach($credits as $credit)
                    <tr>
                        <td class="{{ $credit->amount > 0 ? 'text-green' : 'text-red' }}">
                            @if($credit->amount > 0)
                                +{{ floor($credit->amount*pow(10,8)) / pow(10,8) }}
                            @else
                                {{ floor($credit->amount*pow(10,8)) / pow(10,8)}}
                            @endif
                        </td>
                        <td>
                            <a target="_blank" href="{{ route('users.show', $credit->user_id)  }}">
                                @if($credit->user_id == $order->shopper_id)
                                    Shopper
                                @else
                                    Earner
                                @endif
                            </a>
                        </td>
                        <td>
                            {{ $credit->type }}
                            @if(isset($credit['extra']['order_item_id']))
                                <kbd>#{{ $credit['extra']['order_item_id'] }}</kbd>
                            @endif
                        </td>
                        <td>{{ $credit->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> There is no credit changes.</h5>
    </div>
@endif
