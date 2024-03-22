<div class="container">
    <form action="{{ route('settings.update', $setting->key) }}" method="post">
        @csrf
        @method('patch')
        @foreach($setting->value as $item)
            <div class="jumbotron setting-item">
                <h2 class="text-muted text-center">Role {{ $loop->index+1 }}</h2>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">From</label>
                            <input name="min[]" type="number" class="form-control text-center" value="{{ $item->min }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">To</label>
                            <input name="max[]" type="number" class="form-control text-center" value="{{ $item->max }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Level</label>
                            <input name="level[]" type="number" class="form-control text-center" value="{{ $item->level }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Max orders</label>
                            <input name="max_order[]" type="number" class="form-control text-center" value="{{ $item->max_order }}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Max total USD</label>
                            <input name="max_tp[]" type="number" class="form-control text-center" value="{{ $item->max_tp }}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">VIP ratio</label>
                            <input name="fast_ratio[]" type="number" class="form-control text-center" value="{{ optional($item)->fast_ratio }}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">VIP Amount</label>
                            <input name="vip[]" type="number" class="form-control text-center" value="{{ optional($item)->vip }}">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <input type="submit" value="Update {{ Str::humanize($setting->key) }}" class="btn btn-primary ajax-form-request">
    </form>
</div>
