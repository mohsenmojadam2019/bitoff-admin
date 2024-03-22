<div class="container">
    <div class="row justify-content-center">
        {!! $data->appends(request()->query())->render() !!}
    </div>
</div>
