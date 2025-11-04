@extends('layouts.app')

@section('title', 'Create Workout Plan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h1>
            <i class="bi bi-dumbbell"></i> Create Workout Plan
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('coach.workout-plans.store') }}" method="POST" id="workoutPlanForm">
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
                               placeholder="e.g., Full Body Strength" required>
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

                    <div class="mb-3">
                        <label for="difficulty_level" class="form-label">Difficulty Level *</label>
                        <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                id="difficulty_level" name="difficulty_level" required>
                            <option value="">-- Select Level --</option>
                            <option value="beginner" {{ old('difficulty_level') === 'beginner' ? 'selected' : '' }}>
                                Beginner
                            </option>
                            <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>
                                Intermediate
                            </option>
                            <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>
                                Advanced
                            </option>
                        </select>
                        @error('difficulty_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="mb-3">Exercises</h5>

                    <div id="exercisesContainer">
                        <div class="exercise-item card mb-3" data-index="0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Exercise 1</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-exercise" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Exercise Name *</label>
                                        <input type="text" class="form-control" 
                                               name="exercises[0][exercise_name]" 
                                               placeholder="e.g., Bench Press" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Sets *</label>
                                        <input type="number" class="form-control" 
                                               name="exercises[0][sets]" 
                                               placeholder="3" min="1" value="3" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Reps *</label>
                                        <input type="number" class="form-control" 
                                               name="exercises[0][reps]" 
                                               placeholder="8" min="1" value="8" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Duration (min) *</label>
                                        <input type="number" class="form-control" 
                                               name="exercises[0][duration]" 
                                               placeholder="30" min="1" value="30" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Rest Period (sec) *</label>
                                        <input type="number" class="form-control" 
                                               name="exercises[0][rest_period]" 
                                               placeholder="60" min="0" value="60" required>
                                    </div>
                                    <div class="col-md-9 mb-0">
                                        <label class="form-label">Video URL (optional)</label>
                                        <input type="url" class="form-control" 
                                               name="exercises[0][video_url]" 
                                               placeholder="https://youtube.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary" id="addExercise">
                            <i class="bi bi-plus-circle"></i> Add Another Exercise
                        </button>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Create Workout Plan
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
                    <li>Start with basic exercises</li>
                    <li>Include warm-up exercises</li>
                    <li>Proper rest periods are important</li>
                    <li>Add video links for guidance</li>
                    <li>Match difficulty to client level</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let exerciseCount = 1;

document.getElementById('addExercise').addEventListener('click', function() {
    const container = document.getElementById('exercisesContainer');
    const newExercise = document.querySelector('.exercise-item').cloneNode(true);
    
    // Update all input names
    newExercise.querySelectorAll('input').forEach(input => {
        const oldName = input.name;
        input.name = oldName.replace(/\[\d+\]/, `[${exerciseCount}]`);
        input.value = '';
    });
    
    // Update exercise number
    newExercise.querySelector('.card-header h6').textContent = `Exercise ${exerciseCount + 1}`;
    newExercise.setAttribute('data-index', exerciseCount);
    
    // Show remove button
    newExercise.querySelector('.remove-exercise').style.display = 'inline-block';
    
    container.appendChild(newExercise);
    exerciseCount++;
    
    // Re-attach remove listeners
    attachRemoveListeners();
});

function attachRemoveListeners() {
    document.querySelectorAll('.remove-exercise').forEach(btn => {
        btn.removeEventListener('click', removeExercise);
        btn.addEventListener('click', removeExercise);
    });
}

function removeExercise(e) {
    e.preventDefault();
    e.target.closest('.exercise-item').remove();
    
    // Update exercise numbers
    document.querySelectorAll('.exercise-item').forEach((item, index) => {
        item.querySelector('.card-header h6').textContent = `Exercise ${index + 1}`;
    });
}

// Initial setup
attachRemoveListeners();
</script>
@endsection
