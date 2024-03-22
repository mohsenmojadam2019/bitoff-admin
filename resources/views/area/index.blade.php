@extends('layouts.app')
@section('content')

    @include('layouts.alerts')

    <div class="card" style="margin-top: 50px">
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>State Iso</th>
                    <th>State Alias</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($areas as $area)
                    <tr>
                        <td>
                            {{ $area->state_iso }}
                        </td>
                        <td>{{ $area->state }}</td>
                        <td>
                            <a href="{{ route('areas.cities', $area->state_iso) }}">
                                Cities
                                <i class="fas fa-city"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
