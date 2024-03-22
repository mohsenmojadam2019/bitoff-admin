@extends('layouts.app')
@section('title', 'Payment Method')
@section('content')
@include('layouts.alerts')
@include('layouts.errors')

<div class="card">

    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#" data-toggle="modal"
                    data-target="#addPaymentMethod"><i class="fa fa-plus"></i> Add New Method</a></li>

            <li class="nav-item"><a class="nav-link ml-2 secondary" href="#" data-toggle="modal"
                                    data-target="#addTag"><i class="fa fa-plus"></i> Add New Tag</a></li>
        </ul>
    </div>


    @if($paymentMethods->count())
    <div class="card-body">

        <div class="row">
            @foreach ($paymentMethods as $parent)

            <div class="col-2">

                <div class="card">
                    <div class="card-header bg-secondary pl-2">
                        <h3 class="card-title">{{ $parent->name }}</h3>
                        <div class="card-tools" style="margin-right:12px">
                            <a style="color:rgb(31, 27, 22)" title="edit" href="{{ route('mantis.payment_methods.update.show', $parent) }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @if ($parent->children->count())
                            @foreach ($parent->children as $child)
                            <li class="nav-item active">
                                <div class="row align-items-center">
                                    <div class="col-6 nav-link ">
                                        {{ $child->name }}
                                    </div>

                                    <div class="col-2 nav-link">

                                        @if($child->active)
                                        <form id="toggle" action="{{ route('mantis.payment_methods.toggle',$child) }}"
                                            method="POST">
                                            @csrf
                                            @method('patch')
                                            <button title="enable" type="submit" class="btn pt-3" style="color:royalblue">
                                                <i class="fa fa-toggle-on"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form id="toggle" action="{{ route('mantis.payment_methods.toggle',$child) }}"
                                            method="POST">
                                            @csrf
                                            @method('patch')
                                            <button title="disable" type="submit" class="btn pt-3" style="color:royalblue">
                                                <i class="fa fa-toggle-off"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>

                                    <div class="col-2 nav-link">

                                        <a class="btn pt-4" style="color:rgb(224, 129, 21)" title="edit"
                                           href="{{ route('mantis.payment_methods.update.show', ['paymentMethod' => $child->id]) }}">
                                            <i class="fas fa-edit"></i>&nbsp;
                                        </a>

                                    </div>
                                </div>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>

                </div>

            </div>

            @endforeach
        </div>
    </div>

    @else
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> There is no payment method.</h5>
    </div>
    @endif
</div>

<div class="modal" id="addPaymentMethod">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">New Payment Method</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('mantis.payment_methods.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="amount">Name</label>
                        <input autocomplete="off" type="text" name="name" class="form-control" value=""
                            placeholder="write a name">
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="addTag">
    <div class="modal-dialog">
        <div class="modal-content">


            <div class="modal-header">
                <h4 class="modal-title">New Tag</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mantis.tags.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Name</label>
                        <input autocomplete="off" type="text" name="name" class="form-control" value="">
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Tag</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@endsection
