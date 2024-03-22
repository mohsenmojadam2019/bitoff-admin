@if($tracks && $tracks->count())
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <label for="">Track Number</label>
        <select name="" class="form-control detail-track">
            <option value>select...</option>
            @foreach($tracks as $track)
                <option data-url="{{ route('orders.trackItems',[$orderId,$track->origin]) }}" value="{{ $track->origin }}">{{ $track->origin }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row show-detail-tracks">

</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i>
                No available update!
            </h5>
        </div>
    </div>
</div>
@endif
