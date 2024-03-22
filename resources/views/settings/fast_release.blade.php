<form action="{{ route('settings.update', $setting->key) }}" method="post">
    @csrf
    @method('patch')
    <div class="jumbotron">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="">Amount</label>
                    <input name="amount[]" type="number" class="form-control text-center" value="{{ $setting->value->amount }}">
                </div>
            </div>
{{--            <div class="col-6">--}}
{{--                <div class="form-group">--}}
{{--                    <label for="">Ratio</label>--}}
{{--                    <input name="ratio[]" type="number" class="form-control text-center" value="{{ $setting->value->ratio }}">--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
        <input type="submit" value="Update {{ Str::humanize($setting->key) }}" class="btn btn-primary ajax-form-request">
    </div>
</form>
