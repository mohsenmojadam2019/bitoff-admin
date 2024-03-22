@if ($images)
    <div class="row">
        @foreach ($images as $image)

            <div class="col-sm-1">
                <div class="show-big-image"  data-big-image="{{ $image->image_url }}">
                    <img width="130px" src="{{ $image->thumbnail() }}" class="img-thumbnail img-fluid img-fluid mb-2" alt="white sample">
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="tab-pane fade active show" id="ticket-tab" role="tabpanel" aria-labelledby="ticket-tab">
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> There are no images </h5>
        </div>
    </div>
@endif
