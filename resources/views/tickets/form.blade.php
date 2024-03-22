@extends('layouts.app')
@section('title', 'New Ticket')

@section('content')
    <div class="card card-primary card-outline" style="margin-top: 30px">
        <div class="card-body">
            <h3>New Ticket</h3>
            <hr>
            <form action="{{ route('tickets.store') }}">
                <div class="row">
                    <div class="col">
                        <label>User</label>
                        <span class="form-control text-center">{{ $user->identifier }}</span>
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                    </div>
                    <div class="col">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Body</label>
                        <textarea class="form-control" name="body"></textarea>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <input type="submit" class="btn btn-success ajax-form-request" value="Create Ticket">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
