<form action="{{ route('settings.update', $setting->key) }}" method="post">
    @csrf
    @method('patch')
    <div class="jumbotron">
        @foreach($setting->value as $username)
            <div class="row sample-input">
                <div class="col-10">
                    <div class="form-group">
                        <label for="">username <span class="number-username">{{ $loop->iteration }}</span></label>
                        <input name="username[]" type="text" class="form-control text-center"
                               value="{{ $username }}">
                    </div>
                </div>
                <div class="col-2 fa-pull-left" style="margin-top: 34px;">
                    <div class="form-group">
                        <button type="button" class="btn btn-danger delete-input"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
        @endforeach
        <input type="submit" value="Update {{ Str::humanize($setting->key) }}" class="btn btn-primary ajax-form-request">
        <button class="btn btn-success add-input" type="button"><i class="fa fa-plus"></i> Add Username</button>
    </div>
</form>
