@extends('layouts.app')
@section('title', 'Settings')
@section('content')
    @include('layouts.alerts')
    <div class="card card-primary card-outline" style="margin-top: 30px">
        <div class="card-body">
            <div class="row">
                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" role="tablist" aria-orientation="vertical">
                        @foreach($settings as $setting)
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="pill"
                               href="#{{ $setting->key }}" role="tab"
                               aria-controls="{{ $setting->key }}">{{ Str::humanize($setting->key) }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="col-7 col-sm-9">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        @foreach($settings as $setting)
                            <div class="tab-pane text-left fade {{ $loop->first ? 'show active' : '' }}"
                                 id="{{ $setting->key }}" role="tabpanel">
                                @include('settings.' . $setting->key)
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.remove-setting').on('click', function () {
            if (confirm('Delete this row? This operation will done when you submit the form.')) {
                let form = $(this).closest('form');
                $(this).closest('.setting-item').remove();
                $.each(form.find('.setting-item'), function (i, e) {
                    let $e = $(e);
                    $e.find('h2').text('Role ' + (i + 1));
                    $.each($e.find('input[type=number]'), function () {
                        let $this = $(this), name = $this.attr('name');
                        $this.attr('name', name.substring(0, name.length - 1) + i);
                    })
                })
            }
        });

        /**
         * Create dynamic input form
         *
         * @author Reza Sarlak
         */
        $(document).on('click', '.add-input', function () {
            var sampleInput = $('.sample-input:first').clone();
            $('.sample-input:last').after(sampleInput);
            $('.sample-input:last').find('input').val('');
        });
        $(document).on('click', '.delete-input', function () {
            var check = confirm('Are you sure ?');
            if (check)
                $(this).closest('.row').remove();
        });
    </script>
@endsection
