<div id="accordion" class="mt-3">
    <div class="card card-gray">
        <div class="card-header">
            <h4 class="card-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#filter">Filter</a>
            </h4>
        </div>
        <div id="filter" class="panel-collapse collapse in">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="input-group col-sm-4 col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                            </div>
                            <input name="id" type="text" class="form-control" placeholder="Order ID"
                                   value="{{ request('id') }}">
                        </div>

                        <div class="input-group col-sm-4 col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input name="shopper" type="text" class="form-control"
                                   placeholder="Shopper email, mobile or username" value="{{ request('shopper') }}">
                        </div>

                        <div class="input-group col-sm-4 col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                            </div>
                            <input name="earner" type="text" class="form-control"
                                   placeholder="Earner email, mobile or username" value="{{ request('earner') }}">
                        </div>

                        <div class="col-sm-4 col-md-3">
                            <select name="status" class="form-control text-capitalize">
                                <option value="">-- Status --</option>
                                @foreach(\App\Models\Order::STATUS as $status)
                                    <option value="{{$status}}" @if(request('status') == $status) selected @endif>
                                        {{ __('order.translate.'.$status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                           <input autocomplete="off" name="from_date" type="date" class="form-control datepicker"
                                placeholder="From Date" value="{{ request()->query('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="currency">
                                <option value>select...</option>
                                <option @if(request()->query('currency') == $usdt) selected
                                        @endif value="{{ $usdt }}">{{ $usdt }}</option>
                                <option @if(request()->query('currency') == $btc) selected
                                        @endif value="{{ $btc }}">{{ $btc }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Amazon Id" name="amazon_id" value="{{ request()->query('amazon_id') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Amazon Title" name="amazon_title" value="{{ request()->query('amazon_title') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="support" {{ request('support') ? 'checked' : '' }}>
                                <label for="support">Support</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="fast" {{ request('fast') ? 'checked' : '' }}>
                                <label for="vip">Fast Release</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('orders') }}" class="btn btn-danger btn-block">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
