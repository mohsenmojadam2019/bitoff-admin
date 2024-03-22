<div class="row">
    <div class="col-md-8">
        @if ($order->logs->count())
            <div class="timeline">
                @foreach($order->logs->groupBy(function ($l) {return $l->created_at->format('d-M-y');}) as $g => $logs)
                    <div class="time-label">
                        <span class="bg-red">{{ $logs[0]->created_at->format('d M. Y') }}</span>
                    </div>
                    @foreach($logs->sortByDesc('id') as $log)
                        <div>
                            <i class="fas" style="background: #dee2e6">
                            @if($log->role == 'earner' || $log->role == 'shopper')
                                <img style="width: 100%; height: 100%" src="{{ asset("img/{$log->role}.png") }}">
                            @else
                                <img style="width: 100%; height: 100%" src="{{ asset("img/round-logo.svg") }}">
                            @endif
                            </i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i>
                                    {{ $log->created_at->toTimeString() }}
                                </span>

                                <h3 class="timeline-header">
                                    @if($log->role && $log->user_id)
                                        <a href="{{ route('users.show', $log->user_id) }}">
                                            {{ $log->user->identifier }}
                                        </a>
                                    @else
                                        System
                                    @endif
                                    @include('logs.title')
                                </h3>
{{--     todo--}}
                                @if(in_array($log->type, ['support', 'score', 'off', 'item.purchase', 'item.purchase.edit', 'item.ship', 'item.ship.edit', 'cancel']))
                                    @include('logs.contents')
                                @endif
                                @if($log->type == 'status' && $log->role)
                                    @include('logs.contents')
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i>There is no changes.</h5>
            </div>
        @endif
    </div>
</div>
