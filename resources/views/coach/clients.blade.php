@extends('layouts.app')

@section('title', 'Manage Clients')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="bi bi-people"></i> My Clients
        </h1>
    </div>
</div>

@if ($clients->count() > 0)
    <div class="row">
        @foreach ($clients as $client)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">{{ $client->user->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-envelope"></i> {{ $client->user->email }}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-telephone"></i> {{ $client->user->phone }}
                                </p>
                                <p class="mb-2">
                                    <strong>Age:</strong> {{ $client->age }} years
                                </p>
                                <p class="mb-2">
                                    <strong>Height:</strong> {{ $client->height }} cm
                                </p>
                                <p class="mb-2">
                                    <strong>Weight:</strong> {{ $client->getLatestWeight() }} kg
                                </p>
                                <p class="mb-3">
                                    <strong>Goal:</strong> {{ $client->fitness_goal }}
                                </p>
                            </div>
                            @if ($client->user->avatar_url)
                                <img src="{{ Storage::url($client->user->avatar_url) }}" 
                                     alt="{{ $client->user->name }}" 
                                     class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-person text-white" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="{{ route('coach.clients.view', $client->user_id) }}" class="btn btn-primary">
                                <i class="bi bi-eye"></i> View Profile
                            </a>
                            <a href="{{ route('coach.clients.progress', $client->user_id) }}" class="btn btn-success">
                                <i class="bi bi-graph-up"></i> View Progress
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            {{ $clients->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> You don't have any clients assigned yet.
    </div>
@endif
@endsection
