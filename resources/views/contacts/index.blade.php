@extends('layouts.app')
@section('title', sprintf("Contact [%s]", $contacts->total()))
@section('content')

    @include('layouts.alerts')
    @include('layouts.errors')
    <div id="accordion" style="margin-top:20px">
        <div class="card card-gray">
        <div class="card-header">
            <h4 class="card-title" style="padding-top: 10px">
                <a data-toggle="collapse" data-parent="#accordion" href="#filter">Filter</a>
            </h4>
        </div>
        <div id="filter" class="panel-collapse collapse in">
            <div class="card-body">
               <form>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Id</label>
                            <input type="text" class="form-control" name="id" value="{{ request()->query('id') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="">Contact Name</label>
                            <input type="text" class="form-control" name="contact_name" value="{{ request()->query('contact_name') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="">User</label>
                            <input type="text" class="form-control" name="user" value="{{ request()->query('user') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ request()->query('email') }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-danger btn-block" href="{{ request()->url() }}">Reset</a>
                        </div>
                    </div>
               </form>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            @foreach($contacts as $contact)
            <div class="col-12 col-sm-12 col-md-12 d-flex align-items-stretch flex-column">
                <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-primary border-bottom-0">
                    Contact Name : {{$contact->name }}
                    </div>
                    <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                        @if(optional($contact->user)->first_name)
                        <h2 class="lead"><b>User : {{ optional($contact->user)->first_name.' '.optional($contact->user)->last_name }}</b></h2>
                        @endif
                        <ul class="ml-4 mb-0 fa-ul">
                            <li class="small"><span class="fa-li"><i class="fa fa-hashtag text-dark"></i></span> {{ $contact->id }}</li>
                            <li class="small"><span class="fa-li"><i class="fa fa-envelope"></i></span> {{ $contact->email }}</li>
                            <li class="small"><span class="fa-li"><i class="fa fa-calendar"></i></span> {{ $contact->created_at->format('d M. y') }}</li>
                            <li class="small"><span class="fa-li"><i class="fa fa-file"></i></span> {{ $contact->message }}</li>
                        </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @include('layouts.pagination', ['data' => $contacts])
    </div>
</div>
@endsection
