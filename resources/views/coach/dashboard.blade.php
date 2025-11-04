@extends('layouts.app')

@section('title', 'Coach Dashboard')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Coach Dashboard</h1>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Clients</h6>
                    <h2>{{ $stats['total_clients'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Meal Plans</h6>
                    <h2>{{ $stats['total_meal_plans'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Workout Plans</h6>
                    <h2>{{ $stats['total_workout_plans'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Years Experience</h6>
                    <h2>{{ Auth::user()->coachProfile->experience_years ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Client Section -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Assign New Client</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('coach.clients.assign') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-9">
                    <select name="client_user_id" class="form-control" required>
                        <option value="">-- Select Unassigned Client --</option>
                        @forelse($unassignedClients ?? [] as $unassigned)
                            <option value="{{ $unassigned->user_id }}">
                                {{ $unassigned->user->name }} ({{ $unassigned->user->email }})
                            </option>
                        @empty
                            <option value="">No unassigned clients available</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Assign Client</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Your Clients</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Fitness Goal</th>
                        <th>Current Weight</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->user->name }}</td>
                            <td>{{ $client->age }} years</td>
                            <td>{{ $client->fitness_goal }}</td>
                            <td>{{ $client->getLatestWeight() }} kg</td>
                            <td>
                                <a href="/coach/clients/{{ $client->user_id }}" class="btn btn-sm btn-info">View Progress</a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No clients assigned yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
