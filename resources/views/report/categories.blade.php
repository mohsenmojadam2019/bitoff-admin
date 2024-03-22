<div class="row pt-3">
    @foreach(['users','transactions','orders','tickets'] as $report_category)
        <div class="col-md-3 p-4">
            <a href="{{route('report.show',$report_category)}}"
               class="bg-white p-3 btn text-center text-capitalize font-20 w-100 d-block @if($category == $report_category) btn-outline-primary @else btn-outline-dark @endif">
                {{str_replace('_',' ',$report_category)}}
            </a>
        </div>
    @endforeach
</div>

<hr>

@section('script')
    <script>
        submitFilter();

        function submitFilter() {
            $('#result').html('<div class="d-flex justify-content-center"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span> </div></div>');

            $.ajax({
                url: $('#filter').data('target'),
                method: 'POST',
                data: $('#filter').serialize(),
                success: function (result) {
                    $('#result').html(result);
                },
                error: function (err) {
                    alert(err.responseJSON.message);
                }
            });
        }
    </script>
@endsection

