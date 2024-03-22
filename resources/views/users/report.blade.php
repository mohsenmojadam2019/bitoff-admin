@extends('layouts.app')
@section('title', 'Users | ' . $user->identifier)
@section('content')
    <section class="content" style="margin-top: 50px">
        <div class="container-fluid">
            @include('layouts.alerts')
            @include('layouts.errors')
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset('img/bitoff.jpg') }}">
                            </div>
                            <h3 class="profile-username text-center">
                                {{ $user->username }}
                            </h3>
                            <p class="text-muted text-center">
                                <span>A bitoff user</span>
                                @if ($user->admin)
                                    <span>|</span>
                                    <span>Admin</span>
                                @endif
                                @if ($user->fast_release)
                                    <span>|</span>
                                    <span>VIP</span>
                                @endif
                            </p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <i class="fab fa-bitcoin text-blue"></i>
                                    <a class="float-right">
                                        {{ number_format($user->getCreditSum(\App\Models\Credit::CURRENCY_BTC), 8) }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-tenge text-yellow"></i>
                                    <a class="float-right">
                                        {{ number_format($user->getCreditSum(\App\Models\Credit::CURRENCY_USDT), 2) }}
                                    </a>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-primary btn-block" data-toggle="modal"
                               data-target="#editProfile"><b>Edit</b></a>
                            @if($user->fast_release)
                                <button type="button" class="btn btn-danger btn-block remove-vip"
                                        data-url="{{ route('remove_vip').'?user_id='.$user->id }}">
                                    Remove VIP
                                </button>
                                <hr>
                            @endif
                        </div>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About user</h3>
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-mail-bulk mr-1"></i> Email</strong>
                            <p class="text-muted">
                                {{ $user->email }}
                            </p>
                            <hr>
                            <strong><i class="fas fa-at mr-1"></i> Username</strong>
                            <p class="text-muted">{{ $user->username }}</p>
                            <hr>
                            <strong><i class="fas fa-id-card mr-1"></i> Name</strong>
                            <p class="text-muted">{{ $user->first_name }} {{ $user->last_name }}</p>
                            <hr>
                            <strong><i class="fas fa-trophy mr-1"></i> Trophies</strong>
                            <p class="text-muted">
                                @if ($user->active)
                                    <i data-toggle="tooltip" data-placement="left" class="fas fa-check-circle"
                                       title="Active user"></i>
                                @endif
                                @foreach ($user->roles as $role)
                                    @if ($loop->first)
                                        <b>|</b>
                                    @endif
                                    <span class="badge badge-secondary">{{ $role->name }}</span>
                            @endforeach
                            <hr>
                            @if ($user->fast_release)
                                <img data-toggle="tooltip" data-placement="right" title="VIP"
                                     style="width: 17px;height: 17px; color: gold; font-weight: bold;"
                                     src="{{ asset('img/round-logo.svg') }}" alt="">
                                <span style="color: gold; font-weight: bold;">VIP</span>
                            @endif


                            <hr>

                            @foreach(\Bitoff\Feedback\Application\Enum\FeedbackRole::values() as $roleName)
                                @php $levelMethodName =  $roleName . "Level" @endphp
                                <span class="text-muted" style="color: red;"> {{ \Illuminate\Support\Str::ucfirst($roleName) }}  :
                                    <span class="text-bold" style="color: #000">{{ $$levelMethodName }}</span>
                                    <span><i class="fas fa-medal" style="color: brown;"></i></span>
                                </span>
                                <br>
                            @endforeach

                            <hr>

                            @foreach(\Bitoff\Feedback\Application\Enum\FeedbackRole::values() as $roleName)
                                <span class="text-muted" style="color: red;"> {{ \Illuminate\Support\Str::ucfirst($roleName) }}  :
                                    <span style="color: #000;" class="text-bold">{{ $$roleName->where('is_positive', true)->count() }}  </span>
                                    <span class="fa fa-thumbs-up" style="color: #42bd67;"></span>
                                    <span style="color: #000;" class="text-bold">{{ $$roleName->where('is_positive', false)->count() }}  </span>
                                    <span class="fa fa-thumbs-down" style="color: red;"></span>
                                </span>
                                <br>
                            @endforeach

                            <hr>
                            <strong><i class="fas fa-calendar-alt mr-1"></i> Registered AT</strong>
                            <p class="text-muted">
                                {{ $user->created_at }} | {{ $user->created_at->diffForHumans() }}
                            </p>
                            <hr>

                            {{--                            <strong><i class="fa fa-star-half-alt"></i> Level As Shopper : {{ $shopperLevel }}</strong>--}}
                            {{--                            <p class="text-muted">--}}
                            {{--                                @for ($i = 0; $i < 5; $i++)--}}
                            {{--                                    <i class="fa fa-star {{ $i < (int) $shopperAverage ? 'star-active' : '' }}"></i>--}}
                            {{--                                @endfor--}}
                            {{--                            </p>--}}
                            {{--                            <hr>--}}
                            {{--                            <strong><i class="fa fa-star-half-alt"></i> Level As Earner : {{ $earnerLevel }}</strong>--}}
                            {{--                            <p class="text-muted">--}}
                            {{--                                @for ($i = 0; $i < 5; $i++)--}}
                            {{--                                    <i class="fa fa-star {{ $i < (int) $earnerAverage ? 'star-active' : '' }}"></i>--}}
                            {{--                                @endfor--}}
                            {{--                            </p>--}}
                            {{--                            <hr>--}}
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-1">
                            <ul class="nav nav-pills font-14 font-weight-bold">
                                @foreach ($links as $link)
                                    @php
                                        $active = "";
                                        $fullRoute = $baseRoute.'?action='.$link['action'];
                                        if(request('action') == $link['action'])
                                        $active= 'active';
                                    @endphp
                                    <li class="nav-item">
                                        <a href="{{ $fullRoute }}"
                                           class="nav-link {{ $active }}">{{ $link['caption'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-pane active">
                                <div class="card">

                                    {{--                                    @if ($data&&$data->get()->count() == 0)--}}
                                    @if ($data->get()->count() == 0)
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="alert alert-warning">
                                                    <h5><i class="icon fas fa-exclamation-triangle"></i> No shop until
                                                        now!
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-body p-0">
                                            {!! $view !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>


    <!-- The Modal -->
    <div class="modal" id="editProfile">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    {{-- <h6 class="modal-title">Edit <b>{{ $user->identifier }}</b></h6>
                    --}}
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <form action="{{ route('users.update', $user->id) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <label for="amount">First Name</label>
                            <input autocomplete="off" type="text" name="first_name" class="form-control"
                                   value="{{ $user->first_name }}">
                        </div>
                        <div class="form-group">
                            <label for="amount">Last Name</label>
                            <input autocomplete="off" type="text" name="last_name" class="form-control"
                                   value="{{ $user->last_name }}">
                        </div>
                        <div class="form-group">
                            <label for="amount">Username</label>
                            <input autocomplete="off" type="text" name="username" class="form-control"
                                   value="{{ $user->username }}">
                        </div>
                        <div class="form-group">
                            <label for="amount">Email</label>
                            <input autocomplete="off" type="email" required name="email" class="form-control"
                                   value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label for="amount">Password</label>
                            <input autocomplete="off" type="password" name="password" class="form-control"
                                   placeholder="If you want no change, Just leave it empty.">
                        </div>
                        <div class="form-group">
                            <label for="amount">Roles</label>
                            <select name="roles[]" class="select2" multiple="multiple" data-placeholder="Select a Role"
                                    style="width: 100%;">
                                @foreach ($roles as $role)
                                    <option {{ $user->hasRole($role) ? 'selected' : '' }} value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox float-left">
                                <input name="active" class="custom-control-input" type="checkbox" id="customCheckbox2"
                                    {{ $user->active ? 'checked' : '' }}>
                                <label for="customCheckbox2" class="custom-control-label">Active</label>
                            </div>
                            <div class="custom-control custom-checkbox float-left">
                                <input name="blocked" class="custom-control-input" type="checkbox" id="customCheckbox3"
                                    {{ $user->blocked ? 'checked' : '' }}>
                                <label for="customCheckbox3" class="custom-control-label">Blocked</label>
                            </div>
                            <button type="submit" class="btn btn-info float-right">Update user</button>
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
