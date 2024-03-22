<form action="{{ route('username.store') }}">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="">username</label>
                <input type="text" name="username" class="form-control" value="{{ optional($username)->username }}">
                <input type="hidden" name="username_id" value="{{ optional($username)->id }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="main_username" value="{{ $user->username }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">main user</label>
                <label for="" class="form-control">{{ $user->username }}</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group float-right" style="margin-top: 32px;">
                <button type="button" class="btn btn-primary store-username">create username</button>
            </div>
        </div>
    </div>
</form>
@if($usernames->count())
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <table class="table table-bordered">
            <thead>
            <tr class="table-info">
                <th>Row</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($usernames as $name)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $name->username }}</td>
                <td>
                    <a data-url="{{ route('usernames.index').'?username_id='.$name->id.'&user_id='.$name->user_id }}" 
                        class="pointer add-usernames fa fa-pencil-alt"></a>
                </td>
            </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
@else 
<div class="row">
    <div class="col-md-12"> 
        <div class="alert alert-warning text-center">
            Empty Result 
        </div>
    </div>
</div>
@endif