<div id="accordion" style="margin-top:20px">
    <div class="card card-gray">
        <div class="card-header">
            <h4 class="card-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#filter">Filter</a>
            </h4>
        </div>
        <div id="filter" class="panel-collapse collapse in">
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="input-group col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                            </div>
                            <input name="id" type="text" class="form-control" placeholder="ID"
                                   value="{{ request('id') }}">
                        </div>

                        <div class="input-group col-md-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-shopping-basket"></i></span>
                            </div>
                            <input name="order_id" type="text" class="form-control" placeholder="Order ID"
                                   value="{{ request('order_id') }}">
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

                        <div class="col-md-3">
                            <select name="status" class="form-control text-capitalize">
                                <option value="">-- Status --</option>
                                @foreach(\App\Models\Ticket::STATUS as $status)
                                    <option value="{{$status}}"
                                            @if(request('status') == $status) selected @endif>{{str_replace('_' , ' ' , $status)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('tickets') }}" class="btn btn-danger btn-block">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
