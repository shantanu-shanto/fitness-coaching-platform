@extends('layouts.app')

@section('title', 'Client Progress - ' . $client->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('coach.clients.view', $client->user_id) }}" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Client Profile
        </a>
        <h1>
            <i class="bi bi-graph-up"></i> Progress Report - {{ $client->name }}
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $client->getLatestWeight() }} kg</h3>
            <p class="mb-0">Current Weight</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3>{{ $progressPhotos->count() }}</h3>
            <p class="mb-0">Progress Photos</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3>{{ $progressTracking->count() }}</h3>
            <p class="mb-0">Weight Logs</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h3>{{ $client->calculateBMI() ?? 'N/A' }}</h3>
            <p class="mb-0">BMI</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Weight Progress Chart</h5>
            </div>
            <div class="card-body">
                @if ($progressTracking->count() > 0)
                    <canvas id="progressChart" style="max-height: 400px;"></canvas>
                @else
                    <p class="text-muted">No progress data available yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Progress Photos</h5>
            </div>
            <div class="card-body">
                @if ($progressPhotos->count() > 0)
                    <div class="row">
                        @foreach ($progressPhotos as $photo)
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <img src="{{ Storage::url($photo->photo_url) }}" 
                                         alt="Progress Photo" class="card-img-top" 
                                         style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-calendar"></i> 
                                            {{ $photo->upload_date->format('M d, Y') }}
                                        </p>
                                        <h6 class="mb-2">{{ $photo->weight }} kg</h6>
                                        @if ($photo->body_notes)
                                            <small class="text-muted">{{ Str::limit($photo->body_notes, 60) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{ $progressPhotos->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-muted">No progress photos uploaded yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Weight Tracking History</h5>
            </div>
            <div class="card-body">
                @if ($progressTracking->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Weight (kg)</th>
                                    <th>Body Fat %</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($progressTracking as $progress)
                                    <tr>
                                        <td><strong>{{ $progress->log_date->format('M d, Y') }}</strong></td>
                                        <td>{{ $progress->weight }}</td>
                                        <td>{{ $progress->body_fat_percentage ?? '-' }}</td>
                                        <td>{{ $progress->notes ? Str::limit($progress->notes, 50) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No progress tracking data yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
@if ($progressTracking->count() > 0)
    // Prepare data for chart
    const labels = {!! json_encode($progressTracking->map(fn($p) => $p->log_date->format('M d'))->toArray()) !!};
    const weights = {!! json_encode($progressTracking->map(fn($p) => $p->weight)->toArray()) !!};

    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Weight (kg)',
                data: weights,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3498db',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Weight (kg)'
                    }
                }
            }
        }
    });
@endif
</script>
@endsection
