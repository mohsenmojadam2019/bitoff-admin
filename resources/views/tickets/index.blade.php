@inject('hash', 'App\Support\Hash\HashId')
@extends('layouts.app')
@section('content')
    @include('layouts.alerts')

    @if($tickets->count())
        @include('tickets.partials.filter')

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-responsive-sm">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th width="50%">Subject</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Last Update</th>
                    </tr>

                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{$ticket->id}}</td>
                            <td>
                                <a href="{{route('users.show',$ticket->user_id)}}" target="_blank" class="text-muted">
                                    {{$ticket->user->identifier}}
                                </a>
                            </td>
                            <td>
                                <a href="#" class="text-dark" data-toggle="modal" data-target="#replyModal"
                                   onclick="replyModal({{$ticket->id}})">
                                    {{$ticket->subject}}
                                </a>
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm disabled btn-block text-capitalize @switch($ticket->status) @case('pending') btn-warning @break @case('review') btn-primary @break @case('close') btn-dark @break @default btn-default @break @endswitch">
                                    {{$ticket->status}}
                                </button>
                            </td>
                            <td>
                                @isset($ticket->order)
                                    <a href="{{ route('orders.show',$hash->encode($ticket->order_id)) }}" target="_blank"
                                       class="btn btn-outline-dark btn-block btn-sm">
                                        {{ $ticket->order->hash }}
                                    </a>
                                @endisset
                            </td>
                            <td>{{$ticket->updated_at}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

            @include('layouts.pagination', ['data' => $tickets, 'append' => request()->all()])
        </div>
    @else
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> There is no ticket.</h5>
        </div>
    @endif
    @include('tickets.partials.modal')
@endsection

@section('script')
    <script>
        function replyModal(ticket_id) {
            $('#result').html('<div class="d-flex justify-content-center"><div class="spinner-grow text-primary" role="status"> <span class="sr-only">Loading...</span> </div></div>');

            $('#replyModalTicketNumber').html(ticket_id);
            $('#replyTicketId').val(ticket_id);

            $.ajax({
                url: 'ticket/replies/' + ticket_id,
                method: 'GET',
                success: function (result) {
                    $('#result').html(result);
                },
                error: function (err) {
                    $('#result').html(err.responseJSON.message);
                    alert(err.responseJSON.message);
                }
            });
        }
    </script>
@endsection

