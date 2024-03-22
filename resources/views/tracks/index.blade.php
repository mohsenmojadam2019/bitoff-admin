@extends('layouts.app')
@section('title', 'Tracking')
@section('content')

    @if($tracks)

        <div id="accordion" class="mt-3">
            <div class="card card-gray">
                <div class="card-header">
                    <h4 class="card-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#filter">Filter</a>
                    </h4>
                </div>
                <div id="filter" class="panel-collapse">
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="input-group col-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                    </div>
                                    <input name="origin" autocomplete="off" type="text" class="form-control" placeholder="Track ID" value="{{ request()->get('origin') }}">
                                </div>
                                <div class="col-3">
                                    <select name="sort" class="form-control text-capitalize">
                                        <option {{ request()->get('sort') === 'id' ? 'selected' : '' }} value="id">Order by latest added</option>
                                        <option {{ request()->get('sort') === 'last_inception' ? 'selected' : '' }} value="last_inception">Order by latest updated</option>
                                    </select>
                                </div>
                                <div class="input-group col-md-1">
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>

                                <div class="input-group col-md-1">
                                    <a href="{{ route('tracks.index') }}" class="btn btn-danger btn-block">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline" style="margin-top: 30px">
        <div class="card-body">


        <div class="row">
            <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                    @foreach($tracks as $track)
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="vert-tabs-home-tab" data-toggle="pill" href="#track-{{ $track->id }}" role="tab" aria-controls="vert-tabs-home" aria-selected="true">
                            {{ $track->origin }}
                            @if($track->state === 'new')
                                <small class="badge badge-info">new</small>
                            @endif
                            @if($track->state === 'progress')
                                <small class="badge badge-warning">In progress</small>
                            @endif
                            @if($track->state === 'done')
                                <small class="badge badge-success">Done</small>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-tabContent">
                    @foreach($tracks as $track)
                        <div class="tab-pane text-left fade {{ $loop->first ? 'show active' : '' }}" id="track-{{ $track->id }}" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="timeline">
                                        @foreach($track->items as $item)
                                            <div>
                                                <i class="fas fa-circle bg-cyan"></i>
                                                <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i>
                                            {{ $item->created_at->format('M d - H:i') }}
                                        </span>
                                                    <h3 class="timeline-header">
                                                        @if(isset($item->payload['status']) && $item->payload['status'])
                                                            {{ $item->payload['status'] }}
                                                        @else
                                                            Unknown status
                                                        @endif
                                                    </h3>
                                                    @if(isset($item->payload['progress']) && $item->payload['progress'])
                                                        <div class="timeline-body">
                                                            <ul class="todo-list">
                                                                @foreach($item->payload['progress'] as $progress)
                                                                    <li>
                                                                        <span class="handle ui-sortable-handle"></span>
                                                                        <div class="icheck-primary d-inline ml-2">
                                                                            @if($progress['reached'])
                                                                                <i class="fas fa-check-square text-blue"></i>
                                                                            @else
                                                                                <i class="far fa-square"></i>
                                                                            @endif
                                                                        </div>
                                                                        <span class="text">{{ $progress['title'] }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(!$track->items)
                                            <div class="alert alert-warning">
                                                <h5><i class="icon fas fa-exclamation-triangle"></i>
                                                    No available update!
                                                </h5>
                                            </div>
                                        @endif
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-12">
                                    <blockquote class="quote-secondary" style="background: #f8f9fa">
                                        @if($track->last_inception)
                                            Updated {{ $track->last_inception->diffForHumans() }}.
                                        @endif
                                        <a target="_blank" href="https://www.amazon.com/progress-tracker/package/ref=ppx_od_dt_b_track_package?_encoding=UTF8&itemId=koqllokotispon&orderId={{ $track->origin }}">Checkout</a>
                                        <code>{{ $track->origin }}</code> in amazon.com

                                        @if($item = $track->getPackage())
                                            <hr>
                                            @if($item->payload['tracking']['title'])
                                                <p>{{ $item->payload['tracking']['title'] }}</p>
                                            @endif
                                            @if($item->payload['tracking']['ship'])
                                                <small>
                                                    {{ $item->payload['tracking']['ship'] }}
                                                </small><br>
                                            @endif
                                            <small>
                                                <cite>
                                                    <a target="_blank" href="https://t.17track.net/en#nums={{ $item->payload['tracking']['id'] }}">
                                                        {{ $item->payload['tracking']['id'] }}
                                                    </a>
                                                </cite>
                                            </small>
                                            @if($item->payload['description'])
                                                <br>
                                                <small>
                                                    {{ $item->payload['description'] }}
                                                </small>
                                            @endif
                                        @endif


                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        </div>
</div>

        <div class="text-center">
            {!! $tracks->links() !!}
        </div>
    @else
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i>
                No available update!
            </h5>
        </div>
    @endif


@endsection
