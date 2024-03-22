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
                            <input name="id" type="text" class="form-control" placeholder="Trade ID"
                                value="{{ request('id') }}">
                        </div>

                        <div class="input-group col-sm-4 col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input name="trader" type="text" class="form-control"
                                placeholder="Trader email, mobile or username" value="{{ request('trader') }}">
                        </div>

                        <div class="input-group col-sm-4 col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input name="offerer" type="text" class="form-control"
                                placeholder="Offerer email, mobile or username" value="{{ request('offerer') }}">
                        </div>

                        <div class="col-sm-4 col-md-3">
                            <select name="status" class="form-control text-capitalize">
                                <option value="">-- Status --</option>
                                @foreach(\Bitoff\Mantis\Application\Models\Trade::status() as $status)
                                <option value="{{$status}}" {{ (request('status') !== null && request('status') == $status) ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <select class="form-control" name="isBuy">
                                <option value = "">Type</option>
                                <option value="1" {{ (request('isBuy') !== null && request('isBuy') == 1) ? 'selected' : '' }}> Buy </option>
                                <option value="0" {{ (request('isBuy') !== null && request('isBuy') == 0) ? 'selected' : '' }}> Sell </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        From Date
                                    </span>
                                </div>
                                <input autocomplete="off" name="from_date" type="date" class="form-control datepicker"
                                    placeholder="From Date" value="{{ request()->query('from_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        To Date
                                    </span>
                                </div>
                                <input autocomplete="off" name="to_date" type="date" class="form-control datepicker"
                                    placeholder="To Date" value="{{ request()->query('to_date') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select class="form-control" name="currency">
                                <option value>Currency</option>
                                <option @if(request()->query('currency') == $usdt =
                                    \Bitoff\Mantis\Application\Models\Offer::CURRENCY_USDT) selected
                                    @endif value="{{ $usdt }}">{{ $usdt }}</option>
                                <option @if(request()->query('currency') == $btc
                                    =\Bitoff\Mantis\Application\Models\Offer::CURRENCY_BTC) selected
                                    @endif value="{{ $btc }}">{{ $btc }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('mantis.trades.index') }}" class="btn btn-danger btn-block">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>