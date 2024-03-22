
    <div class="container">
        @if($order->reserve_id)
        <div class="row justify-content-center">
            <button type="button" class="btn btn-outline-secondary btn-lg" data-toggle="modal"
                    data-target="#newMessageModal">New message</button>
        </div>
        <hr>
        @endif
        @if($chats)

            @foreach($chats as $message)
                <div class="row">
                    <div class="col-12 align-content-center">
                        <div class="callout callout-info">
                            <h5>
                                <span>{{ $message['from'] }} : {{ $message['username'] }}</span>
                                <small class="float-right">{{ $message['created_at'] }}</small>
                            </h5>
                            <hr>
                            <p class="text-center">{!! $message['message'] !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
    </div>
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> No active chat now!</h5>
    </div>
@endif

<div class="modal" id="newMessageModal">
    <div class="modal-dialog">
        <div class="modal-content">

            @if($order->reserve_id)
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New message</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{ route('orders.chat.store', [$order->hash, $order->reserve_id]) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Text</label>
                        <input placeholder="Text" autocomplete="off" required type="text" name="message" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>receiver</label>
                        <select name="receiver" class="form-control">
                            <option value="{{ $order->shopper_id }}">{{ $order->shopper->username }}</option>
                            <option value="{{ $order->earner_id }}"> {{ $order->earner->username }}
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info float-right">Add to chat</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
