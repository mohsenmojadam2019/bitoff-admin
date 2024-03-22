@if($trades->isEmpty())
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> There is no trades.</h5>
    </div>
@else
    <div class="tab-pane active">
        <div class="card">

            <div class="card-body p-0">
                <div class="show-grid">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Trade Id </th>
                                <th>trader</th>
                                <th>price</th>
                                <th>status</th>
                                <th>created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trades as $trade)
                            <tr>
                                <td>
                                    <a class="text-secondary" target="_blank"
                                       href="{{ route('mantis.trades.show', $trade->hash) }}">
                                        <u>{{ $trade->hash }}</u>
                                    </a>
                                </td>
                                <td>{{ $trade->trader->username }}</td>
                                <td>{{ $trade->amount }}</td>
                                <td>{{ $trade->status }}</td>
                                <td>{{ $trade->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $trades->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
