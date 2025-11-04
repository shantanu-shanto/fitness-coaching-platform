@extends('layouts.app')

@section('title', 'Upload Progress Photo')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('client.progress-photos') }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Photos
        </a>
        <h1>
            <i class="bi bi-image"></i> Upload Progress Photo
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('client.progress.store-photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo *</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" name="photo" accept="image/*" required>
                        <small class="form-text text-muted d-block mt-2">
                            Supported formats: JPEG, PNG, JPG (Max 5MB)
                        </small>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="weight" class="form-label">Current Weight (kg) *</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                               id="weight" name="weight" step="0.1" min="20" 
                               value="{{ old('weight') }}" required>
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body_notes" class="form-label">Notes (optional)</label>
                        <textarea class="form-control @error('body_notes') is-invalid @enderror" 
                                  id="body_notes" name="body_notes" rows="3" 
                                  placeholder="How do you feel? Any observations?">{{ old('body_notes') }}</textarea>
                        @error('body_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Upload Photo
                        </button>
                        <a href="{{ route('client.progress-photos') }}" class="btn btn-outline-secondary">
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
                <h6 class="mb-0">Tips for Best Results</h6>
            </div>
            <div class="card-body">
                <ul class="small mb-0">
                    <li>Take photos in good lighting</li>
                    <li>Wear consistent clothing</li>
                    <li>Same time of day (preferably morning)</li>
                    <li>Same location/background</li>
                    <li>Front, side, and back photos</li>
                    <li>Update regularly (weekly recommended)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('File size exceeds 5MB limit');
            this.value = '';
        }
    }
});
</script>
@endsection
