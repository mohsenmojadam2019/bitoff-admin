@extends('layouts.app')
@section('title', 'Administration place')
@section('content')

    <style>
        section {
            padding-top: 30px;
        }
    </style>

    <section>
        <div style="text-align: center">
            <button class="btn btn-primary clear-cache">Clear cache</button>
        </div>
        <hr/>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $rate['usd'] }}</h3>
                        <p>Rate as USD</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $rate['btc'] }}</h3>
                        <p>Rate as BTC</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $balance['usd'] }}</h3>
                        <p>Balance as USD</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $balance['btc'] }}</h3>
                        <p>Balance as BTC</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $gap }}</h3>
                        <p>Deposit account gap</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $usdt }}</h3>
                        <p>USDT</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $tron }}</h3>
                        <p>TRON</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @CSRF
@endsection

@section('script')
    <script>
        $('.clear-cache').on('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, clear the cache!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'refresh',
                        method: 'POST',
                        success: function (result) {
                            Swal.fire(
                                'Cleared!',
                                result.message,
                                'success'
                            ).then(()=> {
                                location.reload()
                            })
                        }
                    });
                }
            })
        })
    </script>
@endsection
