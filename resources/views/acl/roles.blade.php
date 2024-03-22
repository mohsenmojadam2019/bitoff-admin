@extends('layouts.app')
@section('content')
    <div style="padding-top: 40px">
        @include('layouts.alerts')
        <div class="card">
            <div class="card-header">
                <a href="{{ route('acl.roles.create') }}" class="btn btn-outline-dark float-right">Add new role</a>
                <h3 class="card-title">
                    <i class="fas fa-text-width"></i>
                    Roles
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <ul>
                    @foreach($roles as $role)
                        <li><a href="{{ route('acl.roles.edit', $role->id) }}">{{ $role->name }}</a></li>
                        <ul>
                            @foreach($role->permissions as $permission)
                                <li>{{ __("permissions." . $permission->name) }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

@endsection
