@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar navbar-expand {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">
    <div class="container-fluid">
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('tickets.index') }}">Tickets</a></li>
        </ul>

        <ul class="navbar-nav ml-auto">
            @auth
                <li class="nav-item dropdown">
                    <a id="navbarUser" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">{{ auth()->user()->name }}</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>
            @else
                <li class="nav-item"><a class="btn btn-sm btn-outline-primary mr-2" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="btn btn-sm btn-primary" href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>