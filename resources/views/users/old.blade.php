@extends('layouts.app')
@section('title', sprintf('Users [%s]', $users->total()))
@section('content')

    @include('layouts.alerts')
    @include('layouts.errors')
    @include('users.partials.filter')
    <div class="card" style="margin-top: 20px">
        <div class="card-body p-0">


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Credit ( Usdt )</th>
                        <th>Credit ( btc )</th>
                        <th>As Shoper</th>
                        <th>As Earner</th>
                        <th>Register At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <a class="text-dark" href="{{ route('users.show', $user->id) }}">
                                <b>
                                    @if ($user->admin)
                                        <i class="fa fa-user text-danger"></i>
                                    @else
                                        <i class="fa fa-user text-danger" style="visibility: hidden"></i>
                                    @endif
                                    @if ($user->fast_release)
                                        <img style="width: 17px;height: 17px;" src="{{ asset('img/round-logo.svg') }}"
                                             alt="">
                                    @else
                                        <i class="fas fa-at"></i>
                                    @endif
                                    {{ $user->username }}
                                </b>
                            </a>
                        </td>
                        <td>
                            @if ($user->blocked)
                                <del>{{ $user->email }}</del>
                            @else
                                @if ($user->active)
                                    <i title="Email verified" data-toggle="tooltip" class="fas fa-check-circle"
                                       style="color: #007bff"></i>
                                @else
                                    <i title="Verified" data-toggle="tooltip" class="fas fa-check-circle"
                                       style="color: #007bff;visibility: hidden"></i>
                                @endif
                                {{ $user->email }}
                            @endif
                        </td>
                        <td>
                            @if ($user->sumAmount)
                                <i class="fab fa-bitcoin"></i>
                            @else
                                <i class="fab fa-bitcoin" style="visibility: hidden"></i>
                            @endif
                            {{ number_format($user->sumAmount, 8) }}
                        </td>
                        <td>{{ number_format($user->shop) }}</td>
                        <td>{{ number_format($user->ern) }}</td>
                        <td data-toggle="tooltip" title="{{ $user->created_at->diffForHumans() }}">
                            {{ $user->created_at->format('y M d - H:i') }}
                        </td>
                        <td>
                            <a title="create ticket" href="{{ route('tickets.create') . '?user_id=' . $user->id }}">
                                <i class="fa fa-ticket-alt"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('layouts.pagination', ['data' => $users])


    <div class="modal" id="createUser">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add new user</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <form action="{{ route('users.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="amount">First Name</label>
                            <input autocomplete="off" type="text" name="first_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="amount">Last Name</label>
                            <input autocomplete="off" type="text" name="last_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="amount">Username</label>
                            <input autocomplete="off" type="text" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="amount">Email</label>
                            <input autocomplete="off" type="email" required name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="amount">Mobile</label>
                            <input autocomplete="off" type="text" name="mobile" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="amount">Password</label>
                            <input autocomplete="off" required type="password" name="password" class="form-control">
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input name="active" class="custom-control-input" type="checkbox" id="customCheckbox2">
                                <label for="customCheckbox2" class="custom-control-label">Active</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input name="admin" class="custom-control-input" type="checkbox" id="customCheckbox3">
                                <label for="customCheckbox3" class="custom-control-label">Admin</label>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-info">Create user</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).on('click', '.remove-vip', function () {
            var elementClick = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get(elementClick.attr('data-url'), function (response) {
                        if (response.status === 200) {
                            Swal.fire({
                                title: '<h4>' + response.msg + '</h4>',
                                icon: 'success'
                            })
                        } else {
                            Swal.fire({
                                title: '<h4>' + 'action fail' + '</h4>',
                                icon: 'error'
                            })
                        }
                    })
                }
            })

        });
    </script>
@endsection
