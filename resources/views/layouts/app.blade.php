<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fitness Coaching Platform')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #2c3e50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }
        .nav-link {
            color: #ecf0f1 !important;
            margin: 0 5px;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #3498db !important;
        }
        .sidebar {
            background-color: #34495e;
            min-height: 100vh;
            padding: 20px 0;
        }
        .sidebar a {
            color: #ecf0f1;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background-color: #2c3e50;
            border-left-color: #3498db;
            color: #3498db;
        }
        .sidebar a.active {
            background-color: #2c3e50;
            border-left-color: #3498db;
            color: #3498db;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-heart-pulse"></i> Fitness Coaching
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">
                                {{ auth()->user()->name }}
                                <small class="badge bg-info">{{ auth()->user()->role }}</small>
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="nav-link btn btn-link" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <strong>Errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            @auth
                <div class="col-md-2">
                    <div class="sidebar">
                        @if (auth()->user()->isCoach())
                            <a href="{{ route('coach.dashboard') }}" class="@if (request()->routeIs('coach.dashboard')) active @endif">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a href="{{ route('coach.clients.list') }}" class="@if (request()->routeIs('coach.clients.*')) active @endif">
                                <i class="bi bi-people"></i> Clients
                            </a>
                            <a href="{{ route('coach.meal-plans.create') }}" class="@if (request()->routeIs('coach.meal-plans.*')) active @endif">
                                <i class="bi bi-egg-fried"></i> Meal Plans
                            </a>
                            <a href="{{ route('coach.workout-plans.create') }}" class="@if (request()->routeIs('coach.workout-plans.*')) active @endif">
                                <i class="bi bi-dumbbell"></i> Workout Plans
                            </a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="@if (request()->routeIs('client.dashboard')) active @endif">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a href="{{ route('client.meal-plans') }}" class="@if (request()->routeIs('client.meal-plans*')) active @endif">
                                <i class="bi bi-egg-fried"></i> Meal Plans
                            </a>
                            <a href="{{ route('client.workout-plans') }}" class="@if (request()->routeIs('client.workout-plans*')) active @endif">
                                <i class="bi bi-dumbbell"></i> Workouts
                            </a>
                            <a href="{{ route('client.progress-photos') }}" class="@if (request()->routeIs('client.progress-photos')) active @endif">
                                <i class="bi bi-image"></i> Photos
                            </a>
                            <a href="{{ route('client.progress-tracking') }}" class="@if (request()->routeIs('client.progress-tracking')) active @endif">
                                <i class="bi bi-graph-up"></i> Progress
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-10">
                    <div style="padding: 20px;">
                        @yield('content')
                    </div>
                </div>
            @else
                <div class="col-12">
                    @yield('content')
                </div>
            @endauth
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    @yield('scripts')
</body>
</html>
