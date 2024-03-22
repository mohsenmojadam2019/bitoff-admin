@extends('layouts.app')
@section('title', 'Area | ' . $state)
@section('content')

    @include('layouts.alerts')

    <ol class="breadcrumb" style="margin-top: 20px">
        <li class="breadcrumb-item"><a href="{{ route('areas.index') }}">Areas</a></li>
        <li class="breadcrumb-item">{{ $state }}</li>
        <li class="breadcrumb-item">Cities</li>
    </ol>

    <div class="card" style="margin-top: 50px">
        <div class="card-body p-0">


            <div class="row">
                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        @foreach($areas->groupBy('city') as $city => $items)
                            <a class="nav-link" data-toggle="pill" href="#{{ str_replace(' ', '-', $city) }}" role="tab" aria-controls="vert-tabs-home" aria-selected="false">
                                {{ $city }}
                                <span class="badge badge-secondary">{{ $items->count() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-7 col-sm-9">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        @foreach($areas->groupBy('city') as $city => $items)
                            <div class="tab-pane text-left fade" id="{{ str_replace(' ', '-', $city) }}" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Zip code</th>
                                            <th>Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $item->zip_code }}</td>
                                            <td>{{ $item->rate }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
