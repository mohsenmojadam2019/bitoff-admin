@if($order->tickets->count())
    <div class="container" style="padding: 30px">
        @foreach($order->tickets as $ticket)
            <div class="card direct-chat direct-chat-primary collapsed-card">
                <div class="card-header ui-sortable-handle" style="cursor: move;background: #9c8d8d0d;">
                    <h3 class="card-title">{{ $ticket->subject ?: 'No subject submitted' }}</h3>
                    <div class="card-tools">
                    <span
                        class="badge {{ $ticket->status == 'pending' ? 'bg-gradient-white' : ($ticket->status == 'close' ? 'bg-gradient-red' : 'bg-gradient-yellow') }}">
                        {{ $ticket->status }}
                    </span>
                        <span data-toggle="tooltip" title="3 New Messages" class="badge bg-white">
                        {{ $ticket->replies->count() }}
                    </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="direct-chat-messages" style="height: auto">
                        @foreach($ticket->replies as $reply)
                            @if(!$reply->admin)
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">
                                    <a class="text-muted" target="_blank" href="">{{ $reply->user->identifier }}</a>
                                </span>
                                        <span
                                            class="direct-chat-timestamp float-right">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ asset('img/boxed-bg.jpg') }}">
                                    <div class="direct-chat-text">{!! $reply->body !!}</div>
                                </div>
                            @else
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">
                                    <a target="_blank" href="">{{ $reply->user->identifier }}</a>
                                </span>
                                        <span
                                            class="direct-chat-timestamp float-left">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ asset('img/bitoff.jpg') }}">
                                    <div class="direct-chat-text">{!! $reply->body !!}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <form action="" method="post">
                        @csrf
                        <div class="input-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="close" class="custom-control-input"
                                       id="customCheck{{$ticket->id}}" checked>
                                <label class="custom-control-label text-danger" for="customCheck{{$ticket->id}}">Close
                                    the ticket after answer</label>
                            </div>
                        </div>
                        <div class="input-group">
                            <input autocomplete="off" type="text" name="body" placeholder="Type Message ..."
                                   class="form-control">
                            <span class="input-group-append">
                        <button type="submit" class="btn btn-primary">Answer</button>
                    </span>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> There is no ticket.</h5>
    </div>
@endif
