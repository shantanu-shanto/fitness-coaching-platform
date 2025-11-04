@extends('layouts.app')

@section('title', 'Edit Meal Plan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h1>
            <i class="bi bi-egg-fried"></i> Edit Meal Plan
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('coach.meal-plans.update', $mealPlan->id) }}" method="POST" id="mealPlanForm">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info mb-3">
                        <strong>Client:</strong> {{ $mealPlan->client->name }}
                    </div>

                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name *</label>
                        <input type="text" class="form-control @error('plan_name') is-invalid @enderror" 
                               id="plan_name" name="plan_name" value="{{ old('plan_name', $mealPlan->plan_name) }}" 
                               placeholder="e.g., Weight Loss Plan" required>
                        @error('plan_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Plan details and goals">{{ old('description', $mealPlan->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="mb-3">Meal Schedule</h5>

                    <div id="mealsContainer">
                        @php 
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $mealsByDay = [];
                            foreach ($mealItems as $item) {
                                if (!isset($mealsByDay[$item->day])) {
                                    $mealsByDay[$item->day] = [];
                                }
                                $mealsByDay[$item->day][] = $item;
                            }
                        @endphp
                        @foreach ($days as $dayIndex => $day)
                            <div class="card mb-3 meal-day-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ $day }}</h6>
                                </div>
                                <div class="card-body">
                                    @foreach (['breakfast', 'lunch', 'dinner'] as $mealTypeIndex => $mealType)
                                        @php
                                            $existingMeal = null;
                                            if (isset($mealsByDay[$day])) {
                                                foreach ($mealsByDay[$day] as $meal) {
                                                    if ($meal->meal_type === $mealType) {
                                                        $existingMeal = $meal;
                                                        break;
                                                    }
                                                }
                                            }
                                            $index = $dayIndex * 3 + $mealTypeIndex;
                                        @endphp
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label class="form-label">
                                                    <strong>{{ ucfirst($mealType) }}</strong>
                                                </label>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="hidden" name="meals[{{ $index }}][day]" value="{{ $day }}">
                                                <input type="hidden" name="meals[{{ $index }}][meal_type]" value="{{ $mealType }}">
                                                <input type="text" class="form-control" 
                                                       name="meals[{{ $index }}][description]" 
                                                       value="{{ old('meals.' . $index . '.description', $existingMeal?->description) }}"
                                                       placeholder="What to eat" required>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <input type="number" class="form-control" 
                                                       name="meals[{{ $index }}][calories]" 
                                                       value="{{ old('meals.' . $index . '.calories', $existingMeal?->calories) }}"
                                                       placeholder="Calories" min="0" required>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Update Meal Plan
                        </button>
                        <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-header">
                <h6 class="mb-0">Danger Zone</h6>
            </div>
            <div class="card-body">
                <p class="small">Delete this meal plan permanently</p>
                <form action="{{ route('coach.meal-plans.destroy', $mealPlan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-light w-100" 
                            onclick="return confirm('Are you sure you want to delete this meal plan?')">
                        <i class="bi bi-trash"></i> Delete Plan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
