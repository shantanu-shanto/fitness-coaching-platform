@extends('layouts.app')

@section('title', 'My Workout Plans')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="bi bi-dumbbell"></i> My Workout Plans
        </h1>
    </div>
</div>

@if ($workoutPlans->count() > 0)
    <div class="row">
        @foreach ($workoutPlans as $plan)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-2">{{ $plan->plan_name }}</h5>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst($plan->difficulty_level) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            {{ $plan->description ?? 'No description provided' }}
                        </p>
                        <div class="row text-center text-muted small">
                            <div class="col-6">
                                <p class="mb-1">
                                    <i class="bi bi-list-check"></i>
                                </p>
                                <p>{{ $plan->getTotalExercisesCount() }} Exercises</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1">
                                    <i class="bi bi-clock"></i>
                                </p>
                                <p>{{ $plan->getTotalDuration() }} min</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('client.workout-plans.show', $plan->id) }}" class="btn btn-success w-100">
                            <i class="bi bi-eye"></i> View Workout
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            {{ $workoutPlans->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No workout plans assigned yet. Your coach will create workout plans for you soon.
    </div>
@endif

@endsection
