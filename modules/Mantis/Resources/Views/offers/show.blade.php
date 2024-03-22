@extends('layouts.app')
@section('title', 'Offer')
@section('content')
@include('layouts.alerts')
@include('layouts.errors')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <ul class="nav nav-pills">
                    <li class="nav-item actions-offer">
                        <a class="pointer action-offer-link nav-link {{ request()->query('action') == 'Overview' || !request()->query('action') ? 'active' : '' }}"
                            data-url="{{ route('mantis.offers.overview',$offer->hash) }}">Overview</a>
                    </li>
                    <li class="nav-item actions-offer">
                        <a class="pointer action-offer-link nav-link
                                {{ request()->query('action') == 'Offerer' ? 'active' : '' }}" data-url="{{ route('mantis.offers.offerer',$offer->hash) }}">Offerer</a>
                    </li>
                    <li class="nav-item actions-offer">
                        <a class="pointer action-offer-link nav-link
                                {{ request()->query('action') == 'Trades' ? 'active' : '' }}" data-url="{{ route('mantis.offers.trades',$offer->hash) }}">TradesList</a>
                    </li>
                    <li class="nav-item actions-offer">
                        <a class="pointer action-offer-link nav-link
                                {{ request()->query('action') == 'History' ? 'active' : '' }}" data-url="{{ route('mantis.offers.history',$offer->hash) }}">History</a>
                    </li>
                    <li class="nav-item actions-offer">
                        <a class="pointer action-offer-link nav-link
                                {{ request()->query('action') == 'Credits' ? 'active' : '' }}" data-url="{{ route('mantis.offers.credits',$offer->hash) }}">CreditList</a>
                    </li>
                </ul>
            </div>
            <div class='card-body show-offer-data'>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        function showOfferData() {
            $('.show-offer-data').html('');
            url = $('.actions-offer').find('.active').eq(0).attr('data-url');
            httpGetRequest(url, false).done(function(response) {
                $('.show-offer-data').html(response.data);
            });
        }

        showOfferData();

        $(document).on('click', '.action-offer-link', function() {
            var targetClick = $(this);
            $('.action-offer-link').each(function($key) {
                $('.action-offer-link').eq($key).removeClass('active')
            })
            targetClick.addClass('active');
            window.history.pushState('', 'Offers', updateQueryStringParameter("{{ route('mantis.offers.show',$offer->hash) }}", 'action', targetClick.html()));
            showOfferData();
        })

        $(document).on('change', '.detail-track', function() {
            getDetailTrack();
        });

        function getDetailTrack() {
            var url = $('.detail-track').find(':selected').attr('data-url');
            httpGetRequest(url).done(function(response) {
                $('.show-detail-tracks').html(response.data);
            });
        }

        // Handle pagination clicks
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var activeTab = $('.actions-offer').find('.active').eq(0).attr('data-url');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('.show-offer-data').html(response.data);
                }
            });
        });
    })
</script>
@endsection
