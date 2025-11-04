@extends('layouts.app')

@section('title', 'Manage Clients')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Your Clients</h1>

    <!-- Assign Client Form -->
    <div class="card mb-4 bg-light">
        <div class="card-header">
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
                    <button type="submit" class="btn btn-primary w-100">Assign</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Clients List -->
    <div class="row">
        @forelse($clients as $client)
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $client->user->name }}</h5>
                        <p class="card-text">
                            <small class="text-muted">{{ $client->user->email }}</small><br>
                            <small class="text-muted">{{ $client->user->phone }}</small>
                        </p>
                        <p class="mb-2">
                            <strong>Age:</strong> {{ $client->age }} years<br>
                            <strong>Height:</strong> {{ $client->height }} cm<br>
                            <strong>Weight:</strong> {{ $client->getLatestWeight() }} kg<br>
                            <strong>Goal:</strong> {{ $client->fitness_goal }}
                        </p>
                        <a href="/coach/clients/{{ $client->user_id }}" class="btn btn-sm btn-primary">View Profile</a>

                        <a href="{{ route('coach.meal-plans.create') }}?client_id={{ $client->user_id }}" class="btn btn-sm btn-success">Create Plan</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No clients assigned yet. Start by assigning a client above!</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($clients instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection
