@extends('layouts.app')
@section('content')

    @include('layouts.errors')
    <div class="card card-primary" style="margin-top: 50px">
        <div class="card-header">
            <h3 class="card-title">Edit <b>{{ $tax->zip }}</b> tax</h3>
        </div>
        <form role="form" method="post" action="{{ route('taxes.update', $tax->state) }}">
            @method('patch')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>State</label>
                    <input type="email" class="form-control" readonly value="{{ $tax->state }}">
                </div>
                <div class="form-group">
                    <label>Rate</label>
                    <input name="rate" class="form-control" value="{{ $tax->rate }}">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>



@endsection
