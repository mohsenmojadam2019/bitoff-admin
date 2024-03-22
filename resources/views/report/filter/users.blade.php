@extends('layouts.app')

@section('title','Transactions Report')

@section('content')
    @include('report.categories')

    <div class="container">
        <form id="filter" data-target="{{route('report.filter',$category)}}">
            <div class="row mt-4">
                @csrf
                <div class="col-md-3">
                    <select name="admin" class="form-control text-capitalize">
                        <option value="">-- Admin --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="active" class="form-control text-capitalize">
                        <option value="">-- Active --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="provider" class="form-control text-capitalize">
                        <option value="">-- Provider --</option>
                        <option value="facebook">Facebook</option>
                        <option value="google">Google</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-block" onclick="submitFilter()">
                        Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="result"></div>
@endsection
