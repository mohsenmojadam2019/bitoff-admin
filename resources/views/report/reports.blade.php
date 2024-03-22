@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="row mt-3">
        {!! $view !!}
    </div>
@endsection

@section('script')
    @if(request()->route()->parameter('type') == 'user')
        @include('report.partials.user_report_script')
    @elseif(request()->route()->parameter('type') == 'order')
        @include('report.partials.order_report_script')
    @endif
@endsection


