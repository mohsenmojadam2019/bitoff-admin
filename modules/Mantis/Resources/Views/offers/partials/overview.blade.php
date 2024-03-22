<div class="table-responsive">
    <table class="table table-bordered" style="background: #9c8d8d0d">
        <tbody>
        <tr>
            <th>At</th>
            <td>
                <b data-toggle="tooltip"
                   title="{{ $offer->created_at }}">{{ $offer->created_at->format('M d H:i') }}</b>
                |
                <span>{{ $offer->created_at->diffForHumans() }}</span>
            </td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <b class="badge badge-{{ $offer->active ? 'success' : 'danger' }}">{{ $offer->active ? 'active' : 'inactive' }}</b>
            </td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td><b>{{ $offer->paymentMethod->name }}</b></td>
        </tr>
        <tr>
            <th>Type</th>
            <td>
                <b>{{ $offer->isBuy() ? 'Buy' : 'Sell' }}</b>
            </td>
        </tr>
        <tr>
            <th>Currency</th>
            <td><b>{{ $offer->currency }}</b></td>
        </tr>
        <tr>
            <th>Rate</th>
            <td><b>{{ $offer->rate }}</b></td>
        </tr>
        <tr>
            <th>Price Limit</th>
            @if($offer->min === $offer->max)
                <td><b>${{ $offer->min }}</b></td>
            @else
                <td><b>${{ $offer->min }} - {{ $offer->max }}</b></td>
            @endif
        </tr>

        <tr>
            <th>Time Limit</th>
            <td><b>{{ $offer->time }}</b> min</td>
        </tr>

        <tr>
            <th>Fee</th>
            <td><b>{{ $offer->fee }}</b></td>
        </tr>

        <tr>
            <th>Terms</th>
            <td>{{ $offer->terms }}</td>
        </tr>

        <tr>
            <th>Tags</th>
            <td>
                @foreach($offer->tags->pluck('name') as $tagName)
                    <span class="badge badge-info">{{ $tagName }}</span>
                @endforeach
            </td>
        </tr>

        <tr>
            <th>Local id</th>
            <td><b>{{ $offer->id }}</b></td>
        </tr>
        </tbody>
    </table>
</div>
