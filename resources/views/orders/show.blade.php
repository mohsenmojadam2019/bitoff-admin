
@extends('layouts.app')
@section('title', 'Orders')
@section('content')
    @include('layouts.alerts')
    @include('layouts.errors')
    <div class="row">
        <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
            <ul class="nav nav-pills">
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Overview' || !request()->query('action') ? 'active' : '' }}" data-url="{{ route('orders.overview',$id) }}">Overview</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Products' ? 'active' : '' }}" data-url="{{ route('orders.products',$id) }}">Products</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Shopper' ? 'active' : '' }}" data-url="{{ route('orders.shopper',$id) }}">Shopper</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Earner' ? 'active' : '' }}" data-url="{{ route('orders.earner',$id) }}">Earner</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Tickets' ? 'active' : '' }}" data-url="{{ route('orders.tickets',$id) }}">Tickets</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'History' ? 'active' : '' }}" data-url="{{ route('orders.history',$id) }}">History</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Credit' ? 'active' : '' }}" data-url="{{ route('orders.credit',$id) }}">Credit</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Chat' ? 'active' : '' }}" data-url="{{ route('orders.chat',$id) }}">Chat</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Wish' ? 'active' : '' }}" data-url="{{ route('orders.wish',$id) }}">Wish</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Tracks' ? 'active' : '' }}" data-url="{{ route('orders.tracks',$id) }}">Tracks</a>
                </li>
                <li class="nav-item actions-order">
                    <a class="pointer action-order-link nav-link {{ request()->query('action') == 'Images' ? 'active' : '' }}" data-url="{{ route('orders.images',$id) }}">Images</a>
                </li>
            </ul>
            </div>
            <div class='card-body show-order-data'>

            </div>
        </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function(){
            function showOrderData(){
                    $('.show-order-data').html('');
                    url = $('.actions-order').find('.active').eq(0).attr('data-url');
                    httpGetRequest(url,false).done(function(response){
                    $('.show-order-data').html(response.data);
                });
            }

            showOrderData();

            $(document).on('click','.action-order-link',function(){
              var targetClick = $(this);
              $('.action-order-link').each(function($key){
                  $('.action-order-link').eq($key).removeClass('active')
              })

              targetClick.addClass('active');
              window.history.pushState('', 'Orders', updateQueryStringParameter("{{ route('orders.show',$id) }}",'action',targetClick.html()));
              showOrderData();
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
        })


        //-------------------------------------------------------------------------------------------------



        $(document).on('click','a.c-item', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Remove&nbsp;<kbd>#${$e.attr('data-id')}</kbd> ?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#3085d6',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, Remove it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: $e.data('target'),
                            method: 'DELETE',
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

            $(document).on('click','a.d-item', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Deliver&nbsp;<kbd>#${$e.attr('data-id')}</kbd> ?`,
                    text: "You won't be able to revert this!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.value) {
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

            $(document).on('click','.t-item', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Add tracking link for&nbsp;<kbd>#${$e.attr('data-id')}</kbd> ?`,
                    icon: 'info',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off',
                        placeholder: 'https://amazon.com/tracking/some/thing'
                    },
                    showCancelButton: true,
                    cancelButtonColor: 'gray',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                }).then((result) => {

                    if (result.isConfirmed && result.value) {
                        let $e = $(this);
                        $.ajax({
                            url: $e.data('target'),
                            method: 'POST',
                            data: {
                                'tracking': result.value
                            },
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

            $(document).on('click','#rm-shopper', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Cancel the order?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off',
                        placeholder: 'Tell the shopper why?!'
                    },
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
                            method: 'DELETE',
                            data: {
                                'description': result.value
                            },
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

            $(document).on('click','#resolve', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Resolve support request?`,
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

            $(document).on('click','#rm-earner', function() {
                let $e = $(this);
                Swal.fire({
                    title: `Cancel transaction?`,
                    html: 'We can remove <b>only</b> Items that have not been <b>delivered</b></br> <small><i>Note that if there is delivered item, We keep earner in the order</i></small>',
                    icon: 'warning',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off',
                        placeholder: 'Tell the users why?!'
                    },
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
                            method: 'DELETE',
                            data: {
                                'description': result.value
                            },
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

        $(document).on('click','.show-issue-message',function(){
            $('.issue-message').slideToggle()
        });

        $(document).on('click','.show-big-image',function(){
            htmlImage = "<img src='" + $(this).attr('data-big-image') + "'>";
            showModal({
                title:'image',
                body:htmlImage
            })
        })
    </script>
@endsection
