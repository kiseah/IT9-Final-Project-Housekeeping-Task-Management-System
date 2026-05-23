<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Hotel Housekeeping') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .nav-link.active {
            font-weight: 600;
            border-bottom: 2px solid #fff;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .main-content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        }

        .card-stat {
            border-left: 5px solid;
            border-radius: 8px;
        }

        .badge-pending   { background-color: #ffc107; color: #000; }
        .badge-progress  { background-color: #0d6efd; }
        .badge-completed { background-color: #198754; }
        .badge-cancelled { background-color: #dc3545; }
    </style>

    @stack('styles')
</head>
<body>

{{-- ✅ TOP NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-building"></i> HotelKeep
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- LEFT SIDE LINKS --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Dashboard (all roles) --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                {{-- Tasks (Admin & Housekeeper) --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isHousekeeper())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}"
                       href="{{ route('tasks.index') }}">
                        <i class="bi bi-list-check"></i> Tasks
                    </a>
                </li>
                @endif

                {{-- Requests (Admin & Guest) --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isGuest())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('requests.*') ? 'active' : '' }}"
                       href="{{ route('requests.index') }}">
                        <i class="bi bi-bell"></i> Requests
                    </a>
                </li>
                @endif

                {{-- Rooms (Admin only) --}}
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}"
                       href="{{ route('rooms.index') }}">
                        <i class="bi bi-door-closed"></i> Rooms
                    </a>
                </li>
                @endif

                {{-- Users (Admin only) --}}
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                       href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                @endif

            </ul>

            {{-- RIGHT SIDE: User Info + Logout --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle text-white" href="#"
                       data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        {{ auth()->user()->full_name }}
                        <span class="badge bg-secondary ms-1">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>

{{-- ✅ MAIN CONTENT AREA --}}
<div class="container-fluid">
    <div class="row">
        <main class="col-12 main-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')

        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>