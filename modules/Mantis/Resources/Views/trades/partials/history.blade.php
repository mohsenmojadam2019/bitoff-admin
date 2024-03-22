<div class="row">
    @if ($activities)
        <div class="timeline w-100">
            @foreach($activities->sortByDesc('id')->groupBy(function ($l) {return $l->created_at->format('d-M-y');}) as $g => $logs)
                <div class="time-label">
                    <span class="bg-red">{{ $logs[0]->created_at->format('d M. Y') }}</span>
                </div>
                @foreach($logs->sortByDesc('id') as $log)
                    <div>
                        <i class="fas d-flex justify-content-center align-content-center align-items-center" style="background: #dee2e6">
                            @if(isset($log->causer) && $log->causer instanceof \App\Models\User)
                                @if($log->causer->is($trade->trader))
                                    <img style="width: 100%; height: 100%" src="{{ asset("img/letter-t.png") }}">
                                @elseif($log->causer->is($trade->offer->offerer))
                                    <img style="width: 100%; height: 100%" src="{{ asset("img/letter-o.png") }}">
                                @else
                                    <img style="width: 80%; height: 80%" src="{{ asset("img/trade-admin.png") }}">
                                @endif
                            @else
                                <img style="width: 80%; height: 80%" src="{{ asset("img/security.png") }}">
                            @endif
                        </i>
                        <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i>
                                    {{ $log->created_at->toTimeString() }}
                                </span>

                            <h4 class="timeline-header" style="font-size: 15px !important;">
                                @if(isset($log->causer) && $log->causer instanceof \App\Models\User)
                                    <a href="{{ route('users.show', $log->causer->id) }}">{{ $log->causer->identifier }}</a>
                                @else
                                    System
                                @endif
                                {{ $log->description }}
                                @if($log->description === 'updated')
                                    @if($log->properties['attributes']['status'])
                                        status from {{ $log->properties['old']['status'] }} to
                                        <span class="badge-success badge badge-{{ trans("Mantis::trade.color.{$log->properties['attributes']['status']}") }}">
                                            {{ $log->properties['attributes']['status'] }}
                                        </span>
                                    @endif
                                @endif
                                @if($log->description === 'feedback')
                                        <i style="color: {{ $log->properties['feedback']['is_positive'] ? '#28a745' : '#dc3545' }}"
                                           class="fas {{ $log->properties['feedback']['is_positive'] ? 'fa-thumbs-up' : 'fa-thumbs-down' }} ml-2"></i>
                                @endif
                            </h4>
                            @include('Mantis::trades.partials.history-body')
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
