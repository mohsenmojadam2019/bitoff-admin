@extends('layouts.app')
@section('title', sprintf('Users [%s]', $users->total()))
@section('content')
    @include('layouts.alerts')
    @include('layouts.errors')
    @include('users.partials.filter')


    <div class="row">
        @foreach($users as $user)
            <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column">
                <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0"></div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-7">
                                <h2 class="lead">
                                    <a href="{{ route('users.show', $user->id) }}" class="text-black">
                                        {{ @$user->username }}
                                    </a>
                                </h2>
                                <p class="text-muted text-sm">
                                </p>
                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                    <li class="small mt-1">
                                        <span class="fa-li">
                                            @if ($user->active)
                                                <i title="Email verified" data-toggle="tooltip"
                                                   class="fas fa-check-circle"
                                                   style="color: #007bff"></i>
                                            @else
                                                <i class="far fa-envelope"></i>
                                            @endif
                                        </span>{{ $user->email }}
                                    </li>
                                    <li class="small mt-1"><span class="fa-li"><i
                                                class="fas fa-money-check-alt"></i></span>
                                        @if($user->earner_count)
                                            <b>{{ $user->earner_count }}</b> times earned.
                                        @else
                                            No order as earner.
                                        @endif
                                    </li>
                                    <li class="small mt-1"><span class="fa-li"><i
                                                class="fas fa-shopping-bag"></i></span>
                                        @if($user->shopper_count)
                                            <b>{{ $user->shopper_count }}</b> times shopped.
                                        @else
                                            No order as shopper.
                                        @endif
                                    </li>

                                    <li class="small mt-1"><span class="fa-li"><i class="fas fa-coins"></i></span>
                                        @if($user->btc_amount)
                                            <span><b>{{ number_format($user->btc_amount, 8) }}</b> BTC</span>
                                        @else
                                            <span>No BTC credit</span>
                                        @endif
                                        <span>|</span>
                                        @if($user->usdt_amount)
                                            <span><b>{{ number_format($user->usdt_amount, 2) }}</b> USDT</span>
                                        @else
                                            <span>No USDT credit</span>
                                        @endif
                                    </li>
                                    <li class="small mt-1"><span class="fa-li"><i class="fas fa-hand-peace"></i></span>
                                        Joined {{ $user->created_at->diffForHumans() }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-5 text-center">
                                <div class=" flex flex-column">
                                    <div class="mb-2">
                                        @if($user->fast_release)
                                            <img title="VIP Account" data-toggle="tooltip"
                                                 src="{{ asset('img/bitoff.jpg') }}"
                                                 alt="user-avatar"
                                                 class="img-circle img-size-64 img-bordered-sm {{ $user->fast_release ? 'border-warning' : '' }}">
                                        @else
                                            <img src="{{ asset('img/bitoff.jpg') }}" alt="user-avatar"
                                                 class="img-circle img-size-64 img-bordered-sm">
                                        @endif
                                    </div>
                                    <div>
                                        @if($user->blocked)
                                            <label class="bg-gradient-red rounded-lg text-white px-3 py-1">
                                                Blocked
                                            </label>
                                        @endif
                                    </div>

                                </div>
                                <p>
                                    @if($user->admin)
                                        <span class="badge badge-pill badge-primary">Admin</span>
                                    @endif
                                    @if($user->fast_release)
                                        <span class="badge badge-pill bg-yellow">VIP</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            @can('sync.vip.wallet')
                                @if($user->fast_release)
                                    <a title="Sync Vip Level"
                                       data-url="{{ route('sync.vip.wallet').'?user_id='.$user->id }}"
                                       data-toggle="tooltip" class="btn btn-sm bg-success sync-vip-wallet">
                                        <i class="fa fa-level-up-alt"></i>&nbsp;
                                    </a>
                                @endif
                            @endcan
                            @if($user->blocked)
                                <form class="d-inline-block"
                                    action="{{route('user.block.update', ['user' => $user->id, 'status' => 'unblock'])}}"
                                    method="POST">
                                    @method('PATCH')
                                    @CSRF
                                    <button class="btn btn-sm bg-gradient-green" title="Unblock this user"
                                            data-toggle="tooltip">
                                        <i class="fas fa-user"></i>&nbsp;
                                    </button>
                                </form>
                            @else
                                <form class="d-inline-block"
                                    action="{{route('user.block.update', ['user' => $user->id, 'status' => 'block'])}}"
                                    method="POST">
                                    @method('PATCH')
                                    @CSRF
                                    <button class="btn btn-sm bg-gradient-red" title="Block this user"
                                            data-toggle="tooltip"
                                            href="{{ route('user.block.update', ['user' => $user->id]) }}">
                                        <i class="fas fa-user-lock"></i>&nbsp;
                                    </button>
                                </form>
                            @endif

                            @if($user->send_wallet_notif)
                                <button title="DeActive admin/credit pending notification"
                                        data-url="{{ route('send.wallet.notif') }}" data-check="false"
                                        data-user="{{ $user->id }}" type="button" class="btn btn-danger send-email"><i
                                        class="fa fa-bell-slash"></i></button>
                            @else
                                <button title="Active admin/credit pending notification"
                                        data-url="{{ route('send.wallet.notif') }}" data-check="true"
                                        data-user="{{ $user->id }}" type="button" class="btn btn-dark send-email">
                                    <i class="fa fa-bell"></i></button>
                            @endif

                            <a class="btn btn-sm bg-teal" title="Send a ticket" data-toggle="tooltip"
                               href="{{ route('tickets.create', ['user_id' => $user->id]) }}">
                                <i class="fas fa-inbox"></i>&nbsp;
                            </a>
                            <a href="{{ route('users.show', $user->id) }}" title="View full profile"
                               data-toggle="tooltip" class="btn btn-sm bg-primary">
                                <i class="fas fa-user"></i>&nbsp;
                            </a>
                            <a data-url="{{ route('usernames.index') . '?user_id=' . $user->id }}"
                               title="create sub username" data-toggle="tooltip"
                               class="btn btn-sm bg-info  add-usernames">
                                <i class="fas fa-users"></i>&nbsp;
                            </a>
                            <a data-url="{{ route('sync.wallet.bitcoin').'?user_id='.$user->id }}"
                               class=" btn btn-sm btn-warning sync-wallet">
                                Sync BTC wallet
                            </a>
                            <a data-url="{{ route('sync.wallet.usdt').'?user_id='.$user->id }}"
                               class="text-white btn btn-sm btn-danger sync-wallet">
                                Sync USDT wallet
                            </a>
                            @if(!$user->active)
                                <a href="{{route('verify.email').'?user_id='.$user->id}}"
                                   class="text-white btn btn-sm btn-success">Verify Email</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    @include('layouts.pagination', ['data' => $users])


    <div class="modal" id="general-modal">
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

        $(document).on('click', '.add-usernames', function () {
            httpGetRequest($(this).attr('data-url')).done(function (response) {

                if (response.status === 200) {
                    showModal({
                        title: 'Add Username',
                        body: response.data
                    })
                }
            });
        });

        $(document).on('click', '.store-username', function () {
            setModalLoading();
            httpFormPostRequest($(this)).done(function (response) {
                if (response.status === 200) {
                    successAlert(response.msg);
                    httpGetRequest(response.data.url).done(function (response) {
                        $('.modal-body').html(response.data)
                    })
                }
                removeModalLoading();
            });
        });

        $(document).on('click', '.sync-wallet', function () {
            var buttonClick = $(this);
            var captionButton = buttonClick.html();
            buttonClick.html(" <i class='fa fa-spinner fa-spin'></i>Waiting...")
            httpGetRequest(buttonClick.attr('data-url')).done(function (response) {
                if (response.status === 200) {
                    successAlert(response.msg)
                } else if (response.status === 422) {
                    errorAlert(response.msg)
                }
                buttonClick.html(captionButton);
            });
        });

        $(document).on('click', '.send-email', function () {
            var targetCheckBox = $(this);
            var typeCheck = 0;
            url = targetCheckBox.attr('data-url');
            httpPostRequest(url, {
                user_id: targetCheckBox.attr('data-user'),
                notif: targetCheckBox.attr('data-check')
            }).done(function (response) {
                if (response.status === 200) {
                    successAlert(response.msg);
                    setTimeout(function () {
                        window.location.reload()
                    }, 2000);
                }
                if (response.status === 403) {
                    errorAlert(response.msg);
                    targetCheckBox.prop('checked', false)
                }
            });
        });

        $(document).on('click', '.sync-vip-wallet', function () {
            httpGetRequest($(this).attr('data-url')).done(function (response) {
                if (response.status === 200) {
                    successAlert(response.msg);
                }
                if (response.status === 202) {
                    errorAlert(response.msg);
                }
            })
        })
    </script>
@endsection
