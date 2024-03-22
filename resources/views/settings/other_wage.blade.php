<div class="container">
    <form action="{{ route('settings.update', $setting->key ) }}" method="post">
        <div class="row justify-content-center" style="font-size: 20px">
            <code>exp( ( $off - <b>A</b> ) / <b>B</b> ) + <b>C</b> </code>
            &nbsp;&nbsp; <code>=></code>&nbsp;&nbsp;
            <code>exp( ( $off - <b>{{ $setting->value->a }}</b> ) / <b>{{ $setting->value->b }}</b> ) + <b>{{ $setting->value->c }}</b> </code>
        </div>
        <hr>
        @csrf
        @method('patch')
        @foreach($setting->value as $name => $value)
            <div class="setting-item">
                <div class="row">
                    <div class="col-6 jumbotron">
                        <h2 class="text-muted text-center"><b>{{ strtoupper($name) }}</b></h2>
                        <input name="{{ $name }}[]" required step="0.01" type="number" class="form-control text-center" value="{{ $value }}">
                    </div>
                </div>
            </div>
        @endforeach
        <input type="submit" value="Update {{ Str::humanize($setting->key) }}" class="btn btn-primary ajax-form-request">
    </form>
</div>
