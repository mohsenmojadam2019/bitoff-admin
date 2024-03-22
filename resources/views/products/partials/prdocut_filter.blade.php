<div id="accordion" style="margin-top:20px">
    <div class="card card-gray">
        <div class="card-header">
            <h4 class="card-title" style="padding-top: 10px">
                <a data-toggle="collapse" data-parent="#accordion" href="#filter">Filter</a>
            </h4>
        </div>
        <div id="filter" class="panel-collapse collapse in" @if (request()->has('amazon_id')) style="display: block" @endif>
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col">
                            <input autocomplete="off" type="text" class="form-control" name="amazon_id"
                                value="{{ request()->query('amazon_id') }}" placeholder="Amazon Id">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('products') }}" class="btn btn-danger btn-block">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
