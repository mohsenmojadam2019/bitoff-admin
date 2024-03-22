<div class="timeline-body">
    @if ($log->type == 'item.purchase' || $log->type == 'item.purchase.edit')
        <pre>{!!  nl2br($log->changes['id']) !!}</pre>
    @endif
    @if ($log->type == 'item.ship' || $log->type == 'item.ship.edit')
        <a href="{{ $log->changes['track'] }}" target="_blank">{{ $log->changes['track'] }}</a>
    @endif
    @if ($log->type == 'off')
        Previous Off: <b>{{ $log->changes['previous_off'] }}</b>
    @endif
    @if ($log->type == 'image' && isset($log->changes['path']))
                <div class=" ml-3 mt-2 mb-3">
                    @if ($log->imageThumbnail($log->changes['id']))
                        <div class="show-big-image" bit-image="{{ $storageUrl .'/'.$log->changes['path'] }}">
                            <img class="img-thumbnail" width="150px" height="150px"
                                src="{{ $log->imageThumbnail($log->changes['id'])['path'] }}" alt=""></div>
                    @endif
                </div>
    @endif
    @if (isset($log->changes['description']))
        <blockquote class="blockquote">
            <footer class="blockquote-footer"><cite title="Source Title">{{ $log->changes['description'] }}</cite>
            </footer>
        </blockquote>
    @endif

</div>
