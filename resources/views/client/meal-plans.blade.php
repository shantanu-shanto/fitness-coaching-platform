@extends('layouts.app')

@section('title', 'My Meal Plans')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="bi bi-egg-fried"></i> My Meal Plans
        </h1>
    </div>
</div>

@if ($mealPlans->count() > 0)
    <div class="row">
        @foreach ($mealPlans as $plan)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $plan->plan_name }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            {{ $plan->description ?? 'No description provided' }}
                        </p>
                        <p class="text-muted small">
                            <i class="bi bi-calendar"></i> 
                            Created: {{ $plan->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('client.meal-plans.show', $plan->id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-eye"></i> View Plan
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            {{ $mealPlans->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No meal plans assigned yet. Your coach will create meal plans for you soon.
    </div>
@endif

@endsection
