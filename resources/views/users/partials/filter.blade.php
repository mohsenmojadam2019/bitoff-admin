<div id="accordion" style="margin-top:20px">
    <div class="card card-gray">
        <div class="card-header">
            <h4 class="card-title" style="padding-top: 10px">
                <a data-toggle="collapse" data-parent="#accordion" href="#filter">Filter</a>
            </h4>
        </div>
        <div id="filter" class="panel-collapse collapse in">
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="input-group col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input name="user" type="text" class="form-control" autocomplete="off"
                                   placeholder="email or username" value="{{ request('user') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="order_by" class="form-control">
                                <option value>selected...</option>
                                <option value="usdt_amount|desc"
                                        @if(request('order_by') == 'usdt_amount|desc') selected @endif>
                                    Most credit usdt
                                </option>
                                <option value="btc_amount|desc"
                                        @if(request('order_by') == 'btc_amount|desc') selected @endif>
                                    Most credit btc
                                </option>
                                <option value="created_at|asc"
                                        @if(request('order_by') == 'created_at|asc') selected @endif>
                                    Oldest
                                </option>
                                <option value="created_at|desc"
                                        @if(request('order_by') == 'created_at|desc') selected @endif>
                                    Newest
                                </option>
                                <option value="shopper_count|desc" @if(request('order_by') == 'shopper_count|desc') selected @endif>
                                    Most shop
                                </option>
                                <option value="earner_count|desc" @if(request('order_by') == 'earner_count|desc') selected @endif>
                                    Most earn
                                </option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <input type="checkbox" value="1" name="vip" {{ request()->query('vip') == 1 ? 'checked' : '' }}>
                            <label for="vip">VIP</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" value="1" name="unverify"  {{ request()->query('unverify') == 1 ? 'checked' : ''}}>
                            <label>Unverifed User</label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('users') }}" class="btn btn-danger btn-block">Reset</a>
                        </div>
                        <div class="col-md-9">
                            <a href="{{ route('earner.countOrderOfEarner') }}" class="btn btn-success"><i class="fa fa-file-excel"></i> Export Earner</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
