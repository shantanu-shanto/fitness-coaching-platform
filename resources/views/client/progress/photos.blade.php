@extends('layouts.app')

@section('title', 'My Progress Photos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="bi bi-image"></i> Progress Photos
            </h1>
            <a href="{{ route('client.progress.upload-form') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Upload New Photo
            </a>
        </div>
    </div>
</div>

@if ($photos->count() > 0)
    <div class="row">
        @foreach ($photos as $photo)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ Storage::url($photo->photo_url) }}" 
                         alt="Progress Photo" class="card-img-top" 
                         style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-calendar"></i> 
                            {{ $photo->upload_date->format('M d, Y') }}
                        </p>
                        <h5 class="mb-2">{{ $photo->weight }} kg</h5>
                        @if ($photo->body_notes)
                            <p class="small text-muted">{{ $photo->body_notes }}</p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('client.progress.delete-photo', $photo->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to delete this photo?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            {{ $photos->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No progress photos yet. 
        <a href="{{ route('client.progress.upload-form') }}">Upload your first photo</a>
    </div>
@endif

@endsection
