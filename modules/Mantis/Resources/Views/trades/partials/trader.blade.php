<p class="lead">Info</p>
<div class="table-responsive">
    <table class="table" style="background: #9c8d8d0d">
        <tbody>
            <tr>
                <th>Username</th>
                <td>
                    <a target="_blank" href="{{ route('users.show', $trader->id) }}">{{ $trader->username }}</a>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <a target="_blank" href="{{ route('users.show', $trader->id) }}">{{ $trader->email }}</a>
                </td>
            </tr>
            <tr>
                <th>Active</th>
                <td>
                    @if($trader->active)
                        <i class="fas fa-check text-blue"></i>
                    @else
                        <i class="fas fa-times"></i>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Register date</th>
                <td>
                    {{ $trader->created_at }}
                    &nbsp;|&nbsp;
                    <b>{{ $trader->created_at->diffForHumans() }}</b>
                </td>
            </tr>
        </tbody>
    </table>
</div>
