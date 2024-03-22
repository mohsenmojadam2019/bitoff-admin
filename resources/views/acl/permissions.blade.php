<div class="card" style="margin-top: 30px">
    <div class="card-body p-0">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Uri</th>
                <th>Meaning</th>
            </tr>
            @foreach($permissions as $route)
                <tr>
                    <td>{{ $route->getName() }}</td>
                    <td>
                        <code>{{ $route->uri() }}</code>
                    </td>
                    <td>
                        @lang("permissions.{$route->getName()}")
                    </td>
                </tr>
            @endforeach
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
