<div class="container">
    <form action="{{ route('settings.update', $setting->key) }}" method="post">
        @csrf
        @method('patch')
        <div class="jumbotron">
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label class="text-center">1 Star</label>
                        <input name="star_1[]" type="number" class="form-control" value="{{ $setting->value->star_1 }}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="text-center">2 Stars</label>
                        <input name="star_2[]" type="number" class="form-control" value="{{ $setting->value->star_2 }}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="text-center">3 Stars</label>
                        <input name="star_3[]" type="number" class="form-control" value="{{ $setting->value->star_3 }}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="text-center">4 Stars</label>
                        <input name="star_4[]" type="number" class="form-control" value="{{ $setting->value->star_4 }}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="text-center">5 Stars</label>
                        <input name="star_5[]" type="number" class="form-control" value="{{ $setting->value->star_5 }}">
                    </div>
                </div>
            </div>
            <input type="submit" value="Update {{ Str::humanize($setting->key) }}" class="btn btn-primary ajax-form-request">

        </div>
    </form>

</div>
