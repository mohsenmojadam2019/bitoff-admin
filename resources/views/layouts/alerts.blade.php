@foreach(session()->get('flash-message', []) as $message)
    <div class="alert alert-{{ $message['type'] }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon {{ $message['icon'] }}"></i> {{ $message['title'] }}</h5>
        {{ $message['message'] }}
    </div>
@endforeach
