@extends('layouts.app')

@section('title', 'Meal Plan - ' . $mealPlan->plan_name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('client.meal-plans') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Plans
        </a>
        <h1>
            <i class="bi bi-egg-fried"></i> {{ $mealPlan->plan_name }}
        </h1>
        <p class="text-muted">{{ $mealPlan->description }}</p>
    </div>
</div>

<div class="row">
    @foreach ($mealsByDay as $day => $meals)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $day }}</h5>
                </div>
                <div class="card-body">
                    @if ($meals->count() > 0)
                        @foreach ($meals as $meal)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <span class="badge bg-info">{{ ucfirst($meal->meal_type) }}</span>
                                        </h6>
                                        <p class="mb-1">{{ $meal->description }}</p>
                                    </div>
                                    <span class="badge bg-success">{{ $meal->calories }} cal</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="alert alert-info mb-0">
                            <strong>Daily Total:</strong> {{ $mealPlan->getTotalCaloriesByDay($day) }} calories
                        </div>
                    @else
                        <p class="text-muted mb-0">No meals scheduled for this day</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="mb-3">Weekly Summary</h5>
                <p class="mb-0">
                    Follow this plan consistently for the best results. Feel free to reach out to your coach 
                    if you have any questions or dietary restrictions that need to be accommodated.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
