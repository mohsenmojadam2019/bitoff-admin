@extends('layouts.app')
@section('title', 'Report Users')
@section('content')
    <div class="row mt-3">
        <div class="col-md-6 col-sm-6 col-12">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Number Of Vip User</span>
                    <span class="info-box-number">{{ $numberOfVipUser }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-12">
            <div class="info-box bg-primary">
                <span class="info-box-icon"><i class="fa fa-money-bill"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Money Taken ( vip user )</span>
                    <span class="info-box-number">{{ number_format($sumCreditVip,8) }}</span>
                </div>
            </div>
        </div>
        @foreach($tickets as $ticket)
            <div class="col-md col-sm-6 col-12">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fa fa-ticket-alt"></i></span>
                    <h3>Ticket</h3>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $ticket->status }}</span>
                        <span class="info-box-number">{{ $ticket->ticketCount }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Transaction</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" class="form-control" name="from_date"
                                        value="{{ request()->query('from_date') }}">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" class="form-control" name="to_date"
                                        value="{{ request()->query('to_date') }}">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value>select...</option>
                                        @foreach(\App\Models\Transaction::STATUS as $status)
                                            <option @if($status == request()->query('status')) selected
                                                    @endif  value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <select class="form-control" name="currency">
                                        <option @if(request()->query('currency') == 'btc') selected @endif value="btc">BTC</option>
                                        <option @if(request()->query('currency') == 'usdt') selected @endif value="usdt">USDT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="type">
                                        <option value>select...</option>
                                        @foreach(\App\Models\Transaction::TYPES as $type)
                                            <option
                                                @if(request()->query('type') == $type) selected @endif value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <button class="btn btn-primary btn-block">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-danger btn-block" href="{{ request()->url() }}">refresh</a>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td>Transaction Dollar</td>
                                    <td>Transaction Currency</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $transaction->dollar }}</td>
                                    <td>{{ request()->query('currency') == 'usdt' ? $transaction->dollar : $transaction->currency }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <!-- LINE CHART -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">User Registration Chart</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="lineChart" style="height:250px; min-height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Top earners</h3>
                </div>
                <div class="card-body">
                    <ul class="nav flex-column">
                        @foreach($ordersAsEarners as $ordersAsEarner)
                            <li class="nav-item">
                                <a href="{{ route('users.show',$ordersAsEarner->id) }}" class="nav-link text-danger">
                                    {{ '@'.$ordersAsEarner->username }} <span
                                        class="float-right badge bg-danger ">{{ $ordersAsEarner->earnCount }}</span>
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Top shoppers</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <ul class="nav flex-column">
                        @foreach($ordersAsShoppers as $ordersAsShopper)
                            <li class="nav-item">
                                <a href="{{ route('users.show',$ordersAsShopper->id) }}" class="nav-link text-primary">
                                    {{ '@'.$ordersAsShopper->username }} <span
                                        class="float-right badge bg-primary ">{{ $ordersAsShopper->shopCount }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $.get("{{ route('register.user.report') }}", function (response) {

        $(function () {
            var areaChartData = {
                labels: response.data.month,
                datasets: [
                    {
                        label: 'Digital Goods',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: response.data.countReg
                    }
                ]
            }

            var areaChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }]
                }
            }


            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
            var lineChartData = jQuery.extend(true, {}, areaChartData)
            lineChartData.datasets[0].fill = false;
            lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: lineChartData,
                options: lineChartOptions
            })


        })
    })
    /**
     * End User Report
     */

</script>

@endsection
