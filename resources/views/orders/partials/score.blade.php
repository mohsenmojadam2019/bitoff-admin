@foreach($order->feedbacks as $feedback)
    <tr>
        <th>{{ Str::humanize($feedback->role == 'earner' ? 'shopper' : 'earner') }} submitted feedback</th>
        <td>
{{-- todo change for feedback--}}
        @for($i=0;$i<5;$i++)
            <i class="fa fa-star {{ $i < $feedback->score ? 'star-active' : '' }}"></i>
        @endfor
        </td>
    </tr>
@endforeach
