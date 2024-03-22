@foreach($reports as $parameter => $report)
    <h4 class="text-center pt-4 text-capitalize">{{$parameter}}: {{$report['all']}}</h4>
    @php unset($report['all']) @endphp
    <div class="row">
        @foreach($report as $time => $value)
            <div class="col-md-3 p-3">
                <div class="row text-center border">
                    <div class="col-8 bg-primary p-2 text-capitalize">
                        {{str_replace('_',' ',$time)}}
                    </div>
                    <div class="col-4 bg-white p-2">
                        {{$value}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
