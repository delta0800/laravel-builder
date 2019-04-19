<aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
    <div class="main-navbar">
        <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom">
            <a class="navbar-brand w-100 mr-0" href="#">
                <div class="d-table m-auto">
                    <img class="d-inline-block align-top mr-1" src="{{ asset('images/logo.png') }}" height="25">
                    <span class="d-none d-md-inline ml-1">{{ config('app.name', 'BinBytes') }}</span>
                </div>
            </a>
            <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons"></i>
            </a>
        </nav>
    </div>
    <div class="nav-wrapper">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Home</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
