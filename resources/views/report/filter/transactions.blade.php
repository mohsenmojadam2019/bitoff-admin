@extends('layouts.app')

@section('title','Transactions Report')

@section('content')
    @include('report.categories')

    <div class="container">
        <form id="filter" data-target="{{route('report.filter',$category)}}">
            <div class="row mt-4">
                @csrf
                <div class="col-md-5">
                    <select name="status" class="form-control text-capitalize">
                        <option value="">-- Status --</option>
                        @foreach(\App\Models\Transaction::STATUS as $status)
                            <option value="{{$status}}">{{str_replace('_' , ' ' , $status)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <select name="type" class="form-control text-capitalize">
                        <option value="">-- Type --</option>
                        @foreach(\App\Models\Transaction::TYPES as $type)
                            <option value="{{$type}}">{{str_replace('_' , ' ' , $type)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-block" onclick="submitFilter()">
                        Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="result"></div>
@endsection
