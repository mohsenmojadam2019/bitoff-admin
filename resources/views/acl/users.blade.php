<div class="modal" id="role-{{ $role->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Users with <b>{{ $role->name }}</b> role</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <ul>
                    @foreach($role->users as $user)
                        <li>
                            <a target="_blank" href="{{ route('users.show', $user->id) }}">{{ $user->identifier }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
