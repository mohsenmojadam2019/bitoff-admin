@if($credits->isEmpty())
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> There is no credits.</h5>
    </div>
@else
    <div class="tab-pane active">
        <div class="card">
            <div class="card-body p-0">
                <div class="show-grid">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Trade Id</th>
                                <th>trader</th>
                                <th>amount</th>
                                <th>created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($credits as $credit)
                                <tr>
                                    <td>{{ $credit->creditable->hash }}</td>
                                    <td>
                                        <a target="_blank" href="{{ route('users.show', $credit->user->id) }}">{{ $credit->user->username }}</a>
                                    </td>
                                    <td>{{ $credit->amount }}</td>
                                    <td>{{ $credit->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $credits->links()}}
                </div>
            </div>
        </div>
    </div>
@endif