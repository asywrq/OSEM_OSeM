<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OSEM – Office of Security Management</title>
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="wrapper">

    {{-- SIDEBAR --}}
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="{{ route('dashboard') }}">
                <span class="align-middle">OSEM</span>
            </a>

            <ul class="sidebar-nav">

                {{-- ADMIN LINKS --}}
                @if(auth()->user()->role === 'admin')
                    <li class="sidebar-header">Admin</li>

                    <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('dashboard') }}">
                            <i class="align-middle" data-feather="sliders"></i>
                            <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.users') }}">
                            <i class="align-middle" data-feather="users"></i>
                            <span class="align-middle">User Management</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.offences') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.offences') }}">
                            <i class="align-middle" data-feather="list"></i>
                            <span class="align-middle">Offence Types</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.vehicles') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.vehicles') }}">
                            <i class="align-middle" data-feather="truck"></i>
                            <span class="align-middle">All Vehicles</span>
                        </a>
                    </li>
                @endif

                {{-- OFFICER LINKS --}}
                @if(auth()->user()->role === 'officer')
                    <li class="sidebar-header">Officer</li>

                    <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('dashboard') }}">
                            <i class="align-middle" data-feather="sliders"></i>
                            <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('officer.vehicle-applications') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('officer.vehicle-applications') }}">
                            <i class="align-middle" data-feather="check-square"></i>
                            <span class="align-middle">Vehicle Applications</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('officer.appeal-reviews') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('officer.appeal-reviews') }}">
                            <i class="align-middle" data-feather="shield"></i>
                            <span class="align-middle">Appeal Reviews</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('officer.issue-compound') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('officer.issue-compound') }}">
                            <i class="align-middle" data-feather="file-text"></i>
                            <span class="align-middle">Issue Compound</span>
                        </a>
                    </li>
                @endif

                {{-- USER LINKS --}}
                @if(auth()->user()->role === 'user')
                    <li class="sidebar-header">My Account</li>

                    <li class="sidebar-item {{ request()->routeIs('user.my-vehicle') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('user.my-vehicle') }}">
                            <i class="align-middle" data-feather="truck"></i>
                            <span class="align-middle">My Vehicle</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('user.my-compounds') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('user.my-compounds') }}">
                            <i class="align-middle" data-feather="file-text"></i>
                            <span class="align-middle">My Compounds</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('user.appeal') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('user.appeal') }}">
                            <i class="align-middle" data-feather="message-square"></i>
                            <span class="align-middle">Appeal</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </nav>

    {{-- MAIN --}}
    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block"
                           href="#" data-bs-toggle="dropdown">
                            <span class="text-dark">{{ auth()->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="align-middle me-1" data-feather="log-out"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <p class="mb-0"><strong>OSEM</strong> &copy; {{ date('Y') }} Office of Security Management, IIUM</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('static/js/app.js') }}"></script>
</body>
</html>