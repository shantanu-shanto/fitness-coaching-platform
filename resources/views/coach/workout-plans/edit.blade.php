@extends('layouts.app')

@section('title', 'Edit Workout Plan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('coach.dashboard') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h1>
            <i class="bi bi-dumbbell"></i> Edit Workout Plan
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('coach.workout-plans.update', $workoutPlan->id) }}" method="POST" id="workoutPlanForm">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info mb-3">
                        <strong>Client:</strong> {{ $workoutPlan->client->name }}
                    </div>

                    <div class="mb-3">
                        <label for="plan_name" class="form-label">Plan Name *</label>
                        <input type="text" class="form-control @error('plan_name') is-invalid @enderror" 
                               id="plan_name" name="plan_name" value="{{ old('plan_name', $workoutPlan->plan_name) }}" 
                               placeholder="e.g., Full Body Strength" required>
                        @error('plan_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Plan details and goals">{{ old('description', $workoutPlan->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="difficulty_level" class="form-label">Difficulty Level *</label>
                        <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                id="difficulty_level" name="difficulty_level" required>
                            <option value="beginner" {{ old('difficulty_level', $workoutPlan->difficulty_level) === 'beginner' ? 'selected' : '' }}>
                                Beginner
                            </option>
                            <option value="intermediate" {{ old('difficulty_level', $workoutPlan->difficulty_level) === 'intermediate' ? 'selected' : '' }}>
                                Intermediate
                            </option>
                            <option value="advanced" {{ old('difficulty_level', $workoutPlan->difficulty_level) === 'advanced' ? 'selected' : '' }}>
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
                        @foreach ($exercises as $index => $exercise)
                            <div class="exercise-item card mb-3" data-index="{{ $index }}">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Exercise {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-exercise">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Exercise Name *</label>
                                            <input type="text" class="form-control" 
                                                   name="exercises[{{ $index }}][exercise_name]" 
                                                   value="{{ old('exercises.' . $index . '.exercise_name', $exercise->exercise_name) }}"
                                                   placeholder="e.g., Bench Press" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Sets *</label>
                                            <input type="number" class="form-control" 
                                                   name="exercises[{{ $index }}][sets]" 
                                                   value="{{ old('exercises.' . $index . '.sets', $exercise->sets) }}"
                                                   min="1" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Reps *</label>
                                            <input type="number" class="form-control" 
                                                   name="exercises[{{ $index }}][reps]" 
                                                   value="{{ old('exercises.' . $index . '.reps', $exercise->reps) }}"
                                                   min="1" required>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Duration (min) *</label>
                                            <input type="number" class="form-control" 
                                                   name="exercises[{{ $index }}][duration]" 
                                                   value="{{ old('exercises.' . $index . '.duration', $exercise->duration) }}"
                                                   min="1" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Rest Period (sec) *</label>
                                            <input type="number" class="form-control" 
                                                   name="exercises[{{ $index }}][rest_period]" 
                                                   value="{{ old('exercises.' . $index . '.rest_period', $exercise->rest_period) }}"
                                                   min="0" required>
                                        </div>
                                        <div class="col-md-9 mb-0">
                                            <label class="form-label">Video URL (optional)</label>
                                            <input type="url" class="form-control" 
                                                   name="exercises[{{ $index }}][video_url]" 
                                                   value="{{ old('exercises.' . $index . '.video_url', $exercise->video_url) }}"
                                                   placeholder="https://youtube.com/...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary" id="addExercise">
                            <i class="bi bi-plus-circle"></i> Add Another Exercise
                        </button>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Update Workout Plan
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
                <p class="small">Delete this workout plan permanently</p>
                <form action="{{ route('coach.workout-plans.destroy', $workoutPlan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-light w-100" 
                            onclick="return confirm('Are you sure you want to delete this workout plan?')">
                        <i class="bi bi-trash"></i> Delete Plan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let exerciseCount = {{ count($exercises) }};

document.getElementById('addExercise').addEventListener('click', function() {
    const container = document.getElementById('exercisesContainer');
    const template = `
        <div class="exercise-item card mb-3" data-index="${exerciseCount}">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Exercise ${exerciseCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger remove-exercise">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Exercise Name *</label>
                        <input type="text" class="form-control" 
                               name="exercises[${exerciseCount}][exercise_name]" 
                               placeholder="e.g., Bench Press" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Sets *</label>
                        <input type="number" class="form-control" 
                               name="exercises[${exerciseCount}][sets]" 
                               placeholder="3" min="1" value="3" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Reps *</label>
                        <input type="number" class="form-control" 
                               name="exercises[${exerciseCount}][reps]" 
                               placeholder="8" min="1" value="8" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Duration (min) *</label>
                        <input type="number" class="form-control" 
                               name="exercises[${exerciseCount}][duration]" 
                               placeholder="30" min="1" value="30" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Rest Period (sec) *</label>
                        <input type="number" class="form-control" 
                               name="exercises[${exerciseCount}][rest_period]" 
                               placeholder="60" min="0" value="60" required>
                    </div>
                    <div class="col-md-9 mb-0">
                        <label class="form-label">Video URL (optional)</label>
                        <input type="url" class="form-control" 
                               name="exercises[${exerciseCount}][video_url]" 
                               placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', template);
    exerciseCount++;
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
