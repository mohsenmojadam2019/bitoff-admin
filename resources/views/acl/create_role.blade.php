@extends('layouts.app')
@section('content')

    @include('layouts.errors')
    <div class="card card-primary" style="margin-top: 50px">
        <div class="card-header">
            <h3 class="card-title">Add new role</h3>
        </div>
        <form role="form" method="post" action="{{ route('acl.roles.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Role name">
                </div>
                <div class="form-group">
                    <div class="select2-purple">
                        <label>Permissions</label>
                        <select name="permissions[]" class="select2" multiple="multiple" data-placeholder="Select permissions" style="width: 100%;">
                            @foreach($permissions as $group => $items)
                                <optgroup label="{{ ucfirst($group) }}">
                                    @foreach($items as $permission)
                                        <option value="{{ $permission->id }}">{{ __("permissions.{$permission->name}") }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Create new role</button>
            </div>
        </form>
    </div>



@endsection
