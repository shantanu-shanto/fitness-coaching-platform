@extends('layouts.app')

@section('title', 'Workout - ' . $workoutPlan->plan_name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('client.workout-plans') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Plans
        </a>
        <h1>
            <i class="bi bi-dumbbell"></i> {{ $workoutPlan->plan_name }}
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-muted mb-0">Difficulty</p>
                        <h5 class="mb-0">
                            <span class="badge bg-warning">
                                {{ ucfirst($workoutPlan->difficulty_level) }}
                            </span>
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-0">Total Exercises</p>
                        <h5 class="mb-0">{{ $workoutPlan->getTotalExercisesCount() }}</h5>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-0">Total Duration</p>
                        <h5 class="mb-0">{{ $workoutPlan->getTotalDuration() }} minutes</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p>{{ $workoutPlan->description }}</p>
            </div>
        </div>
    </div>
</div>

@if ($exercises->count() > 0)
    <div class="row">
        @foreach ($exercises as $index => $exercise)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $exercise->exercise_name }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Sets</p>
                                <h5>{{ $exercise->sets }}</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Reps</p>
                                <h5>{{ $exercise->reps }}</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Duration</p>
                                <h5>{{ $exercise->duration }} min</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Rest Period</p>
                                <h5>{{ $exercise->rest_period }} sec</h5>
                            </div>
                        </div>
                        <div class="alert alert-info mb-2">
                            <strong>Total Volume:</strong> {{ $exercise->getTotalVolume() }} reps
                        </div>
                        @if ($exercise->video_url)
                            <a href="{{ $exercise->video_url }}" target="_blank" class="btn btn-sm btn-danger w-100">
                                <i class="bi bi-youtube"></i> Watch Video
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        No exercises in this plan yet.
    </div>
@endif

<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="mb-3">Tips for Success</h5>
                <ul>
                    <li>Warm up before starting</li>
                    <li>Follow the recommended sets and reps</li>
                    <li>Rest for the specified period between sets</li>
                    <li>Watch the video tutorials for proper form</li>
                    <li>Stay consistent and progressive</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
