@extends('layouts.app')

@section('title', 'Create Meal Plan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h1>
            <i class="bi bi-egg-fried"></i> Create Meal Plan
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('coach.meal-plans.store') }}" method="POST" id="mealPlanForm">
                    @csrf

                    <div class="mb-3">
                        <label for="client_id" class="form-label">Select Client *</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                id="client_id" name="client_id" required>
                            <option value="">-- Choose a client --</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->user_id }}" {{ old('client_id') == $client->user_id ? 'selected' : '' }}>
                                    {{ $client->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name *</label>
                        <input type="text" class="form-control @error('plan_name') is-invalid @enderror" 
                               id="plan_name" name="plan_name" value="{{ old('plan_name') }}" 
                               placeholder="e.g., Weight Loss Plan" required>
                        @error('plan_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Plan details and goals">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="mb-3">Meal Schedule</h5>

                    <div id="mealsContainer">
                        @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; @endphp
                        @foreach ($days as $dayIndex => $day)
                            <div class="card mb-3 meal-day-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ $day }}</h6>
                                </div>
                                <div class="card-body">
                                    @foreach (['breakfast', 'lunch', 'dinner'] as $mealType)
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label class="form-label">
                                                    <strong>{{ ucfirst($mealType) }}</strong>
                                                </label>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="hidden" 
                                                       name="meals[{{ $dayIndex * 3 + ($mealType === 'breakfast' ? 0 : ($mealType === 'lunch' ? 1 : 2)) }}][day]" 
                                                       value="{{ $day }}">
                                                <input type="hidden" 
                                                       name="meals[{{ $dayIndex * 3 + ($mealType === 'breakfast' ? 0 : ($mealType === 'lunch' ? 1 : 2)) }}][meal_type]" 
                                                       value="{{ $mealType }}">
                                                <input type="text" class="form-control" 
                                                       name="meals[{{ $dayIndex * 3 + ($mealType === 'breakfast' ? 0 : ($mealType === 'lunch' ? 1 : 2)) }}][description]" 
                                                       placeholder="What to eat" required>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <input type="number" class="form-control" 
                                                       name="meals[{{ $dayIndex * 3 + ($mealType === 'breakfast' ? 0 : ($mealType === 'lunch' ? 1 : 2)) }}][calories]" 
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
                            <i class="bi bi-check-circle"></i> Create Meal Plan
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
        <div class="card bg-light">
            <div class="card-header">
                <h6 class="mb-0">Tips</h6>
            </div>
            <div class="card-body">
                <ul class="small">
                    <li>Create detailed meal descriptions</li>
                    <li>Include calorie information for tracking</li>
                    <li>Consider client's fitness goals</li>
                    <li>Ensure nutritional balance</li>
                    <li>Update plans regularly</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('mealPlanForm').addEventListener('submit', function(e) {
    // Simple validation - ensure at least one meal is filled
    const inputs = document.querySelectorAll('input[name*="[description]"]');
    const anyFilled = Array.from(inputs).some(input => input.value.trim() !== '');
    
    if (!anyFilled) {
        e.preventDefault();
        alert('Please add at least one meal');
    }
});
</script>
@endsection
