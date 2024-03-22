@extends('layouts.app')
@section('title', 'Trade')
@section('content')
    @include('layouts.alerts')
    @include('layouts.errors')
    <div class="row">
        <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
            <ul class="nav nav-pills">
                <li class="nav-item actions-trade">
                    <a class="pointer action-trade-link nav-link
                    {{ request()->query('action') == 'Overview' || !request()->query('action') ? 'active' : '' }}"
                    data-url="{{ route('mantis.trades.overview',$trade->hash) }}">Overview</a>
                </li>
                <li class="nav-item actions-trade">
                    <a class="pointer action-trade-link nav-link
                    {{ request()->query('action') == 'Offerer' ? 'active' : '' }}"
                    data-url="{{ route('mantis.trades.offerer',$trade->hash) }}">Offerer</a>
                </li>
                <li class="nav-item actions-trade">
                    <a class="pointer action-trade-link nav-link
                    {{ request()->query('action') == 'Trader' ? 'active' : '' }}"
                    data-url="{{ route('mantis.trades.trader',$trade->hash) }}">Trader</a>
                </li>
                <li class="nav-item actions-trade">
                    <a class="pointer action-trade-link nav-link
                    {{ request()->query('action') == 'History' ? 'active' : '' }}"
                    data-url="{{ route('mantis.trades.history',$trade->hash) }}">History</a>
                </li>
                <li class="nav-item actions-trade">
                    <a class="pointer action-trade-link nav-link
                    {{ request()->query('action') == 'Credits' ? 'active' : '' }}"
                    data-url="{{ route('mantis.trades.credits',$trade->hash) }}">Credits</a>
                </li>
                <li class="nav-item actions-trade">
                    <a class="pointer action-trade-link nav-link
                    {{ request()->query('action') == 'Tickets' ? 'active' : '' }}"
                    data-url="{{ route('mantis.trades.tickets',$trade->hash) }}">Tickets</a>
                </li>
            </ul>
            </div>
            <div class='card-body show-trade-data' id="show-trade-content">

            </div>
        </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function(){
            function showOfferData(){
                    $('.show-trade-data').html('');
                    url = $('.actions-trade').find('.active').eq(0).attr('data-url');
                    httpGetRequest(url,false).done(function(response){
                    $('.show-trade-data').html(response.data);
                });
            }

            showOfferData();

            $(document).on('click','.action-trade-link',function(){
              var targetClick = $(this);
              $('.action-trade-link').each(function($key){
                  $('.action-trade-link').eq($key).removeClass('active')
              })

              targetClick.addClass('active');
              window.history.pushState('', 'Trades', updateQueryStringParameter("{{ route('mantis.trades.show',$trade->hash) }}",'action',targetClick.html()));
              showOfferData();
          })


          $(document).on('change','.detail-track',function(){
              getDetailTrack();
          });
          function getDetailTrack(){
            var url = $('.detail-track').find(':selected').attr('data-url');
            httpGetRequest(url).done(function(response){
                $('.show-detail-tracks').html(response.data);
            });
          }

        // Handle pagination clicks
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var activeTab = $('.actions-trade').find('.active').eq(0).attr('data-url');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('.show-trade-data').html(response.data);
                }
            });
        });

        })

            $(document).on('click','#resolve', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Resolve dispute request?`,
                    text: "You won't be able to revert this!",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonColor: '#3085d6',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        let $e = $(this);
                        $.ajax({
                            url: $e.data('target'),
                            method: 'POST',
                            success: function(r) {
                                location.reload();
                            },
                            error: function(e) {
                                Swal.fire({
                                    title: 'Operation failed',
                                    icon: 'error'
                                })
                            }
                        })
                    }
                })
            });
    </script>
@endsection
