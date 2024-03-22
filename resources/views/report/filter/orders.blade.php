@extends('layouts.app')

@section('title','Transactions Report')

@section('content')
    @include('report.categories')

    <div class="container">
        <form id="filter" data-target="{{route('report.filter',$category)}}">
            <div class="row mt-4">
                @csrf
                <div class="col-md-3">
                    <select name="status" class="form-control text-capitalize">
                        <option value="">-- Status --</option>
                        @foreach(\App\Models\Order::STATUS as $status)
                            <option value="{{$status}}">
                                {{str_replace('_' , ' ' , $status)}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="prime" class="form-control text-capitalize">
                        <option value="">-- Prime --</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="level" class="form-control text-capitalize">
                        <option value="">-- Level --</option>
                        @for($level = 1; $level <= 10;$level++)
                            <option value="{{$level}}">Level {{$level}}</option>
                        @endfor
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
