@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="bi bi-speedometer2"></i> Your Fitness Journey
        </h1>
    </div>
</div>

@if ($coach)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Coach</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if ($coach->avatar_url)
                            <img src="{{ Storage::url($coach->avatar_url) }}" 
                                 alt="{{ $coach->name }}" class="rounded-circle me-3" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-person text-white" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                        <div>
                            <h5>{{ $coach->name }}</h5>
                            <p class="text-muted mb-0">
                                {{ $coach->coachProfile->specialization }}
                            </p>
                            <small class="text-muted">
                                {{ $coach->coachProfile->experience_years }} years experience
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Your Progress</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <p class="text-muted mb-1">Current Weight</p>
                            <h4>{{ $stats['current_weight'] }} kg</h4>
                        </div>
                        <div class="col-6 mb-3">
                            <p class="text-muted mb-1">Fitness Goal</p>
                            <h6>{{ $stats['fitness_goal'] }}</h6>
                        </div>
                        <div class="col-6 mb-0">
                            <p class="text-muted mb-1">BMI</p>
                            <h5>
                                @if ($stats['bmi'])
                                    {{ $stats['bmi'] }}
                                @else
                                    N/A
                                @endif
                            </h5>
                        </div>
                        <div class="col-6 mb-0">
                            <p class="text-muted mb-1">Started</p>
                            <small>{{ \Carbon\Carbon::parse($stats['start_date'])->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('client.meal-plans') }}" class="btn btn-primary me-2">
                        <i class="bi bi-egg-fried"></i> View Meal Plans
                    </a>
                    <a href="{{ route('client.workout-plans') }}" class="btn btn-primary me-2">
                        <i class="bi bi-dumbbell"></i> View Workouts
                    </a>
                    <a href="{{ route('client.progress.upload-form') }}" class="btn btn-success me-2">
                        <i class="bi bi-image"></i> Upload Photo
                    </a>
                    <a href="{{ route('client.progress.log-form') }}" class="btn btn-info">
                        <i class="bi bi-graph-up"></i> Log Progress
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($recentPhotos->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Progress Photos</h5>
                            <a href="{{ route('client.progress-photos') }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($recentPhotos as $photo)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="{{ Storage::url($photo->photo_url) }}" 
                                             alt="Progress Photo" class="card-img-top" 
                                             style="height: 250px; object-fit: cover;">
                                        <div class="card-body">
                                            <p class="text-muted small mb-2">
                                                {{ $photo->upload_date->format('M d, Y') }}
                                            </p>
                                            <h6>{{ $photo->weight }} kg</h6>
                                            @if ($photo->body_notes)
                                                <small class="text-muted">{{ Str::limit($photo->body_notes, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">
            <i class="bi bi-exclamation-triangle"></i> Coach Not Assigned
        </h4>
        <p>You haven't been assigned to a coach yet. Please wait for your coach to assign you or contact them directly.</p>
    </div>
@endif

@endsection
