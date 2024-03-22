@extends('layouts.app')
@section('title', 'The orders')
@section('content')
    @include('orders.partials.filter')
    <div class="card">
        <div class="card-body p-0">
            {!! $view !!}
        </div>
    </div>

@endsection



