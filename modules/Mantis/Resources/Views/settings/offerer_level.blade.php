<div class="container">
    <form action="{{ route('mantis.settings.update', $setting->key) }}" method="post">
        @csrf
        @method('patch')
        @foreach($setting->value as $item)
            <div class="jumbotron setting-item">
                <h2 class="text-muted text-center">Role {{ $loop->index+1 }}</h2>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">From</label>
                            <input name="min[]" type="number" class="form-control text-center" value="{{ $item->min }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">To</label>
                            <input name="max[]" type="number" class="form-control text-center" value="{{ $item->max }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Level</label>
                            <input name="level[]" type="number" class="form-control text-center"
                                   value="{{ $item->level }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Max offers</label>
                            <input name="max_offer[]" type="number" class="form-control text-center"
                                   value="{{ $item->max_offer }}">
                        </div>
                    </div>
{{--                    todo--}}
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Max off percent</label>
                            <input name="max_percent[]" type="number" class="form-control text-center"
                                   value="{{ $item->max_percent }}">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <input type="submit" value="Update {{ Str::humanize($setting->key) }}"
               class="btn btn-primary ajax-form-request">
    </form>


</div>
