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
                                <img style="width: 100%; height: 100%" src="{{ asset("img/shopper.png") }}">
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
                                @if($log->description === 'created')
                                    <div class="timeline-body p-4 font-13">
                                        @foreach($log->properties['attributes'] as $key => $value)
                                            {{ $key }} : <b>{{ $value }}</b><br>
                                        @endforeach
                                    </div>
                                @endif
                                @if($log->description === 'updated')
                                    @if(isset($log->properties['attributes']['active']))
                                        from 
                                        <span class="badge badge-{{ trans("Mantis::offer.color.{$log->properties['old']['active']}") }}">
                                            {{ $log->properties['old']['active'] ? 'active' : 'inactive' }}
                                        </span>
                                        to
                                        <span class="badge badge-{{ trans("Mantis::offer.color.{$log->properties['attributes']['active']}") }}">
                                            {{ $log->properties['attributes']['active'] ? 'active' : 'inactive' }}
                                        </span>
                                    @endif
                                    @if(!isset($log->properties['attributes']['active']))
                                        <div class="timeline-body p-4 font-13">
                                            @foreach($log->properties['old'] as $key => $value)
                                                {{ $key }} : <b>{{ $value }} => {{$log->properties['attributes'][$key]}}</b><br>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </h4>
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
