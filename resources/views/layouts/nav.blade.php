<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
        @if(!config('permission.enable') || auth()->user()->can('users.show'))
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('users.show', auth()->id()) }}" class="nav-link">Profile</a>
            </li>
        @endif
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('logout') }}" class="nav-link">Logout</a>
        </li>
    </ul>
</nav>
