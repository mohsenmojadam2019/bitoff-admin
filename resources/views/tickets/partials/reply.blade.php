<div class="mt-3 p-4" style="max-height: 300px;min-height: 100px;overflow-y: scroll">
    @foreach($ticket->replies as $reply)
        <div
            class="row @if($ticket->user_id == $reply->user_id) justify-content-start @else justify-content-end text-right @endif">
            <div class="mb-2 col-8">
                <div
                    class="p-2 d-inline-block radius-4 text-left @if($ticket->user_id == $reply->user_id) bg-light @else bg-primary text-white @endif">
                    @if($ticket->user_id != $reply->user_id)
                        <p class="mb-2">{{$reply->user->identifier}}</p>
                    @endif

                    <p class="mb-2">{!! $reply->body!!}</p>

                    <p class="font-10 mb-0 mt-2">
                        {{$reply->created_at->ago()}}
                        - {{$reply->created_at}}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
