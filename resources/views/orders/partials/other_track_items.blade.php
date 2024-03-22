@if($items)
<div class="col-md-12 mt-3">
    <div class="timeline">
        @foreach($items as $item)
        <div>
            <i class="fa fa-check bg-blue"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> {{ date('Y-m-d H:i',strtotime($item->payload['timestamp'])) }}</span>
                <h3 class="timeline-header"><a href="#">Location : </a> {{ $item->payload['location'] }}</h3>

                <div class="timeline-body">
                    {{ $item->status }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="col-md-12 mt-3">
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i>
            No available update!
        </h5>
    </div>
</div>
@endif