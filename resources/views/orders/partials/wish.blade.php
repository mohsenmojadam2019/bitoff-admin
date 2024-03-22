@if(!$order->wishes->count())
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> There is no wish list operation.</h5>
    </div>
@else
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Status</th>
            <th>server id</th>
            <th>Started at</th>
            <th>Callback</th>
            <th>Detail</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->wishes as $wish)
            <tr>
                <td>
                    @if($wish->response)
                        <a href="" data-toggle="modal" data-target="#modal-{{ $wish->id }}">{{ $wish->id }}</a>
                    @else
                        {{ $wish->id }}
                    @endif
                </td>
                <td>{{ Str::humanize($wish->status) }}</td>
                <td>
                    @if($wish->server_id)
                        <a target="_blank" href="{{ env("WISH_URL") }}{{ $wish->server_id }}">{{ $wish->server_id }}</a>
                    @endif
                </td>
                <td>{{ $wish->created_at->format('d M. y - h:m') }}</td>
                <td>{{ $wish->callback_at ? $wish->callback_at->format('d M. y - h:m') : '' }}</td>
                <td>
                    @if($wish->status == 'submit_fail')
                        {{ @$wish->response['status'] }}
                    @else
                        {{ @$wish->response['message'] }}
                    @endif
                </td>
            </tr>

            <div class="modal fade" id="modal-{{ $wish->id }}" tabindex="-1" role="dialog" aria-labelledby="daste tabar" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Response info of #{{ $wish->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                @if($wish->response)
                    @foreach($wish->response as $key => $value)
                        @if(is_array($value))
                        @elseif($value)
                        <dl class="row">
                            <dt class="col-sm-3">{{ Str::humanize($key) }}</dt>
                            @if($key == 'link')
                                <dd class="col-sm-9">
                                    <a target="_blank" href="{{ $value }}">{{ Str::limit($value) }}</a>
                                </dd>
                            @else
                                <dd class="col-sm-9">{{ $value }}</dd>
                            @endif
                        </dl>
                        @if(!$loop->last)
                        <hr>
                        @endif
                        @endif
                    @endforeach
                @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>

        @endforeach
        </tbody>
    </table>

@endif
