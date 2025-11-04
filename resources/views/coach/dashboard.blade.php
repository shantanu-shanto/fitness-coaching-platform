@extends('layouts.app')

@section('title', 'Coach Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="bi bi-speedometer2"></i> Coach Dashboard
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_clients'] }}</h3>
            <p class="mb-0">Total Clients</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3>{{ $stats['total_meal_plans'] }}</h3>
            <p class="mb-0">Meal Plans</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3>{{ $stats['total_workout_plans'] }}</h3>
            <p class="mb-0">Workout Plans</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h3>{{ auth()->user()->coachProfile->experience_years }}</h3>
            <p class="mb-0">Years Experience</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('coach.meal-plans.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> Create Meal Plan
                </a>
                <a href="{{ route('coach.workout-plans.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> Create Workout Plan
                </a>
                <a href="{{ route('coach.clients.list') }}" class="btn btn-outline-primary">
                    <i class="bi bi-people"></i> View All Clients
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Clients</h5>
            </div>
            <div class="card-body">
                @if ($clients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Fitness Goal</th>
                                    <th>Current Weight</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>
                                            <strong>{{ $client->user->name }}</strong>
                                        </td>
                                        <td>{{ $client->age }} years</td>
                                        <td>{{ $client->fitness_goal }}</td>
                                        <td>{{ $client->getLatestWeight() }} kg</td>
                                        <td>
                                            <a href="{{ route('coach.clients.view', $client->user_id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="{{ route('coach.clients.progress', $client->user_id) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="bi bi-graph-up"></i> Progress
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No clients assigned yet. 
                        <a href="{{ route('coach.clients.list') }}">Manage clients</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
