@extends('layouts.app')

@section('title', 'Client Profile - ' . $client->user->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('coach.clients.list') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Clients
        </a>
        <h1>
            <i class="bi bi-person"></i> {{ $client->user->name }}
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        @if ($client->user->avatar_url)
            <img src="{{ Storage::url($client->user->avatar_url) }}" 
                 alt="{{ $client->user->name }}" class="img-fluid rounded" style="width: 100%; max-width: 250px;">
        @else
            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                 style="width: 100%; max-width: 250px; height: 250px;">
                <i class="bi bi-person text-white" style="font-size: 4rem;"></i>
            </div>
        @endif
    </div>

    <div class="col-md-9">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p>{{ $client->user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p>{{ $client->user->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Age:</strong>
                        <p>{{ $stats['age'] }} years</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Height:</strong>
                        <p>{{ $stats['height'] }} cm</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Current Weight:</strong>
                        <p>{{ $stats['current_weight'] }} kg</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>BMI:</strong>
                        <p>
                            @if ($stats['bmi'])
                                {{ $stats['bmi'] }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <strong>Fitness Goal:</strong>
                        <p>{{ $client->fitness_goal }}</p>
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
                <h5 class="mb-0">Recent Progress Photos</h5>
            </div>
            <div class="card-body">
                @if ($progressPhotos->count() > 0)
                    <div class="row">
                        @foreach ($progressPhotos->take(6) as $photo)
                            <div class="col-md-2 mb-3">
                                <div class="card">
                                    <img src="{{ Storage::url($photo->photo_url) }}" 
                                         alt="Progress Photo" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">
                                            {{ $photo->upload_date->format('M d, Y') }}
                                        </small>
                                        <p class="mb-0">
                                            <strong>{{ $photo->weight }} kg</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No progress photos yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Progress Tracking</h5>
            </div>
            <div class="card-body">
                @if ($progressTracking->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Weight (kg)</th>
                                    <th>Body Fat %</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($progressTracking->take(10) as $progress)
                                    <tr>
                                        <td>{{ $progress->log_date->format('M d, Y') }}</td>
                                        <td><strong>{{ $progress->weight }}</strong></td>
                                        <td>{{ $progress->body_fat_percentage ?? 'N/A' }}</td>
                                        <td>{{ $progress->notes ? Str::limit($progress->notes, 50) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No progress tracking data yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <a href="{{ route('coach.meal-plans.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-circle"></i> Create Meal Plan for this Client
        </a>
        <a href="{{ route('coach.workout-plans.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Workout Plan for this Client
        </a>
    </div>
</div>
@endsection
