@if($log->description === 'created')
    <div class="timeline-body p-4 font-13">
        @foreach($log->properties['attributes'] as $key => $value)
            {{ $key }} : <b>{{ $value }}</b><br>
        @endforeach
    </div>
@elseif($log->description === 'updated')
    @if(
            isset($log->properties['attributes']['status']) &&
            in_array(
                $log->properties['attributes']['status'],
                [\Bitoff\Mantis\Application\Models\Trade::STATUS_DISPUTE, \Bitoff\Mantis\Application\Models\Trade::STATUS_CANCELED]
                )
            )
        <div class="timeline-body p-4 font-13">
            @if(isset($log->properties['reason']))Reason : {{ $log->properties['reason'] }}@endif
        </div>
    @endif
@elseif($log->description === 'feedback')
    @if(isset($log->properties['feedback']['comment']))
        <div class="timeline-body p-4 font-13">Comment : {{ $log->properties['feedback']['comment'] }}</div>
    @endif
@endif
