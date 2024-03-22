<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .star-active {
            color: #bf9825;
        }
        .pointer {cursor: pointer;}
        .custom-font-size{
            font-size: 20px;
        }
    </style>
    @yield('css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

@include('layouts.nav')

@include('layouts.side')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
        </div>
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    @include('layouts.footer')

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<div class="modal" id="general-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/sweet.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/func.js') }}"></script>
@include('layouts.func')
<script>
    $(document).on('click', '.page-item', function (e) {
        elementClick = $(this);

        if (elementClick.closest('.ajax-grid').attr('data-url') !== undefined) {

            e.preventDefault();

            url = elementClick.closest('.ajax-grid').attr('data-url');

            numebrPage = (elementClick.find('a').html());

            $.get(url, {page: numebrPage}, function (response) {

                checkUrl = url.split('?');

                addressBar = (checkUrl.length > 1) ? url + "&page=" + numebrPage : url + "?page=" + numebrPage;

                if (response.status == 100) {
                    $(elementClick.closest('.ajax-grid').attr('data-class-name')).html(response.data);
                    window.history.pushState("", "", addressBar);

                }

            });
        }
    })


</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@yield('script')

</body>
</html>
