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
                            <input name="id" type="text" class="form-control" placeholder="Transaction id"
                                   value="{{ request('id') }}">
                        </div>

                        <div class="input-group col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input name="user" type="text" class="form-control"
                                   placeholder="User email, mobile or username" value="{{ request('user') }}">
                        </div>

                        <div class="input-group col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-dollar-sign"></i>
                                </span>
                            </div>
                            <input name="from_amount" type="number" min="0" class="form-control"
                                   placeholder="From Amount" value="{{ request('from_amount') }}">
                        </div>

                        <div class="input-group col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-funnel-dollar"></i>
                                </span>
                            </div>
                            <input name="to_amount" type="number" min="0" class="form-control"
                                   placeholder="To Amount" value="{{ request('to_amount') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <select name="status" class="form-control text-capitalize">
                                <option value="">-- Status --</option>
                                @foreach(\App\Models\Transaction::STATUS as $status)
                                    <option value="{{$status}}"
                                            @if(request('status') == $status) selected @endif>{{str_replace('_' , ' ' , $status)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="type" class="form-control text-capitalize">
                                <option value="">-- Type --</option>
                                @foreach(\App\Models\Transaction::TYPES as $type)
                                    <option value="{{$type}}"
                                            @if(request('type') == $type) selected @endif>{{str_replace('_' , ' ' , $type)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="text" class="form-control" name="dates" value="{{request('dates')}}"
                                   placeholder="Date Between" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="currency">
                                <option value>-- Currency --</option>
                                <option @if(request()->query('currency') == \App\Models\Credit::CURRENCY_BTC) selected
                                        @endif value="{{ \App\Models\Credit::CURRENCY_BTC }}">bitcoin
                                </option>
                                <option
                                    @if(request()->query('currency') == \App\Models\Credit::CURRENCY_USDT) @endif value="{{ \App\Models\Credit::CURRENCY_USDT }}">
                                    usdt
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('transactions') }}" class="btn btn-danger btn-block">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>
        $('input[name="dates"]').daterangepicker({
            showDropdowns: true,
            opens: 'right',
            minYear: 2019,
            maxYear: 2030,
            autoUpdateInput: false
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
    </script>
@endsection
