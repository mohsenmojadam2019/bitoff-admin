@extends('layouts.app')
@section('title', 'Fee & Tags')
@section('content')
@include('layouts.errors')

<section class="content">
    <div class="row col-2 mb-2">
        <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#addTag"><b>Add Tag</b></a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 20%">
                            Name
                        </th>
                        <th style="width: 20%">
                            Fee
                        </th>
                        <th style="width: 50%" class="text-center">
                            Tags
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ $paymentMethod->name }}
                        </td>
                        <td class="project-state">
                            <form action="{{ route('mantis.payment_methods.update', $paymentMethod) }}" method="post">
                                @csrf
                                @method('patch')
                                <div class="input-group input-group-sm">
                                    <input type="number" name="fee" class="form-control text-center"
                                        value="{{ $paymentMethod->fee }}">
                                    <span class="input-group-append">
                                        <input type="submit" value="Update" class="btn btn-info btn-flat">
                                    </span>
                                </div>
                            </form>
                        </td>
                        <td class="project-state">
                            <form action="{{ route('mantis.payment_methods.update', $paymentMethod) }}" method="post">
                                @csrf
                                @method('patch')
                                <div class="input-group input-group-sm">
                                    <div class="col-9 select2-purple">
                                        <select name="tags[]" class="select2" multiple="multiple"
                                            data-placeholder="Add a tag" style="width: 100%;">
                                            @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ $paymentMethod->hasTag($tag->id) ?
                                                'selected' : '' }} >{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-info"> submit </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
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

    </div>

</section>
@endsection