@extends('layouts.app')

@section('title', 'Log Progress')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('client.progress-tracking') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Progress
        </a>
        <h1>
            <i class="bi bi-graph-up"></i> Log Your Progress
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('client.progress.store-tracking') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg) *</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                               id="weight" name="weight" step="0.1" min="20" 
                               value="{{ old('weight') }}" required>
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body_fat_percentage" class="form-label">Body Fat % (optional)</label>
                        <input type="number" class="form-control @error('body_fat_percentage') is-invalid @enderror" 
                               id="body_fat_percentage" name="body_fat_percentage" step="0.1" 
                               min="0" max="100" value="{{ old('body_fat_percentage') }}">
                        <small class="form-text text-muted d-block mt-2">
                            If you have access to a scale or measurement tool
                        </small>
                        @error('body_fat_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (optional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="How do you feel? Any changes? Energy levels?">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Log Progress
                        </button>
                        <a href="{{ route('client.progress-tracking') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card bg-light">
            <div class="card-header">
                <h6 class="mb-0">Progress Tips</h6>
            </div>
            <div class="card-body">
                <ul class="small mb-0">
                    <li>Log progress at the same time each day (preferably morning)</li>
                    <li>Weigh yourself after using the bathroom</li>
                    <li>Use the same scale for consistency</li>
                    <li>Track weekly or bi-weekly for better trends</li>
                    <li>Weight fluctuates daily - look at trends</li>
                    <li>Combined with photos for best results</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
