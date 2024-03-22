@extends('layouts.app')
@section('content')

    @include('layouts.errors')
    <div class="card card-primary" style="margin-top: 50px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('acl.roles') }}">Roles</a></li>
            <li class="breadcrumb-item active">{{ $role->name }}</li>
        </ol>
        <form role="form" method="post" action="{{ route('acl.roles.update', $role->id) }}">
            @method('patch')
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $role->name }}">
                </div>
                <div class="form-group">
                    <div class="select2-purple">
                        <label>Permissions</label>
                        <select name="permissions[]" class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
                            @foreach($permissions as $item)
                                <option value="{{ $item->id }}" {{ $role->hasPermissionTo($item->name) ? 'selected' : '' }}>{{ __("permissions.{$item->name}") }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('acl.roles') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>



@endsection
