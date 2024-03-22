<p class="lead">Info</p>
<div class="table-responsive">
    <table class="table" style="background: #9c8d8d0d">
        <tbody>
            <tr>
                <th>Username</th>
                <td>
                    <a target="_blank" href="{{ route('users.show', $shopper->id) }}">{{ $shopper->username }}</a>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <a target="_blank" href="{{ route('users.show', $shopper->id) }}">{{ $shopper->email }}</a>
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <td><b>{{ $shopper->first_name }} {{ $shopper->last_name }}</b></td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td><b>{{ $shopper->mobile }}</b></td>
            </tr>
            <tr>
                <th>Active</th>
                <td>
                    @if($shopper->active)
                        <i class="fas fa-check text-blue"></i>
                    @else
                        <i class="fas fa-times"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Prime</th>
                <td>
                    @if($shopper->prime)
                        <i class="fas fa-check text-blue"></i>
                    @else
                        <i class="fas fa-times"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Register date</th>
                <td>
                    {{ $shopper->created_at }}
                    &nbsp;|&nbsp;
                    <b>{{ $shopper->created_at->diffForHumans() }}</b>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<p class="lead">Address</p>
<div class="table-responsive">
    <table class="table" style="background: #9c8d8d0d">
        <tbody>
            <tr>
                <th>Show full address</th>
                <td>
                    @if(isset($order->address['can_see_address']) && $order->address['can_see_address'])
                        <i class="fas fa-check text-blue"></i>
                    @else
                        <i class="fas fa-times"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <th>State</th>
                <td>{{ $order->address['state'] }}</td>
            </tr>
            <tr>
                <th>City</th>
                <td>{{ $order->address['city'] }}</td>
            </tr>
            <tr>
                <th>Street</th>
                <td>{{ $order->address['street'] }}</td>
            </tr>
            <tr>
                <th>Building</th>
                <td>{{ $order->address['building'] }}</td>
            </tr>
            <tr>
                <th>Zip code</th>
                <td>{{ $order->address['zip_code'] }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $order->address['phone'] }}</td>
            </tr>
            <tr>
                <th>First Name</th>
                <td>{{ $order->address['first_name'] }}</td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td>{{ $order->address['last_name'] }}</td>
            </tr>
        </tbody>
    </table>
</div>
<hr>

