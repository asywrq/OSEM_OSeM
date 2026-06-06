<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OSEM – Office of Security Management</title>
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Sidebar background */
        .sidebar,
        .sidebar .sidebar-content,
        .js-simplebar,
        .simplebar-content-wrapper,
        .simplebar-content {
            background: #1b4c2a !important;
        }

        /* Sidebar menu label */
        .sidebar .sidebar-header {
            color: rgba(255,255,255,0.4) !important;
        }

        /* Remove AdminKit default backgrounds and borders */
        .sidebar-item,
        .sidebar-item:hover,
        .sidebar-item.active,
        .sidebar-link,
        a.sidebar-link {
            background: transparent !important;
            border-left: none !important;
        }

        /* Hover state */
        .sidebar .sidebar-item .sidebar-link:hover {
            background: rgba(255,255,255,0.1) !important;
            border-radius: 4px;
        }

        /* Active state */
        .sidebar .sidebar-item.active > .sidebar-link {
            background: rgba(23,160,96,0.35) !important;
            border-radius: 4px;
        }

        /* Main area */
        .main, .content, body {
            background: #f5f7f5 !important;
        }

        /* Top navbar */
        .navbar.navbar-bg {
            background: #ffffff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
            border-bottom: 1px solid #e5ede7 !important;
        }

        /* Footer */
        .footer {
            background: #ffffff !important;
            border-top: 1px solid #e5ede7 !important;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- SIDEBAR --}}
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">

            {{-- Logo --}}
            <div class="text-center py-3">
                <img src="{{ asset('static/img/logo/logo.png') }}" alt="OSEM Logo" style="width: 100px; max-width: 60%;">
                <div style="color: rgba(255,255,255,0.9); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; margin-top: 0.5rem;">
                    ONLINE SUMMONS AND ENFORCEMENT MANAGEMENT
                </div>
            </div>

            {{-- Menu --}}
            <ul class="sidebar-nav">

                @if(auth()->user()->role === 'admin')
                    <li class="sidebar-header">Menu</li>
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

                @if(auth()->user()->role === 'officer')
                    <li class="sidebar-header">Menu</li>
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

                @if(auth()->user()->role === 'user')
                    <li class="sidebar-header">Menu</li>
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

            {{-- Bottom --}}
            <div style="padding: 0 1rem 1.5rem; margin-top: auto;">
                <div style="border-top: 1px solid rgba(255,255,255,0.15); padding-top: 1rem;">
                    <div style="font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.85); padding: 0 0.5rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn w-100 text-start"
                            style="background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; font-size: 0.82rem; padding: 0.5rem 0.75rem; transition: background 0.2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.15)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                            <i data-feather="log-out" style="width:14px;height:14px;" class="me-2"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>

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
                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
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
<script>
    function applyColors() {
        document.querySelectorAll('.sidebar-link').forEach(function (el) {
            var isActive = el.closest('.sidebar-item').classList.contains('active');
            el.style.setProperty('color', isActive ? '#ffffff' : '#b8c6c0', 'important');

            el.onmouseenter = function () {
                this.style.setProperty('color', '#ffffff', 'important');
            };
            el.onmouseleave = function () {
                var active = this.closest('.sidebar-item').classList.contains('active');
                this.style.setProperty('color', active ? '#ffffff' : '#b8c6c0', 'important');
            };
        });
    }

    var observer = new MutationObserver(function () {
        applyColors();
    });

    document.addEventListener('DOMContentLoaded', function () {
        applyColors();
        observer.observe(document.getElementById('sidebar'), {
            childList: true,
            subtree: true
        });
    });
</script>
</body>
</html>