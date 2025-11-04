@extends('layouts.app')

@section('title', 'Progress Tracking')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="bi bi-graph-up"></i> Your Progress
            </h1>
            <a href="{{ route('client.progress.log-form') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Log New Entry
            </a>
        </div>
    </div>
</div>

@if ($progressData->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Weight Progress Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="progressChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Progress History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Weight (kg)</th>
                                    <th>Body Fat %</th>
                                    <th>Change</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $previousWeight = null; @endphp
                                @foreach ($progressData as $progress)
                                    @php
                                        $weightChange = null;
                                        if ($previousWeight !== null) {
                                            $weightChange = $progress->weight - $previousWeight;
                                        }
                                        $previousWeight = $progress->weight;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $progress->log_date->format('M d, Y') }}</strong></td>
                                        <td>{{ $progress->weight }}</td>
                                        <td>{{ $progress->body_fat_percentage ?? '-' }}</td>
                                        <td>
                                            @if ($weightChange !== null)
                                                @if ($weightChange < 0)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-arrow-down"></i> {{ abs($weightChange) }}
                                                    </span>
                                                @elseif ($weightChange > 0)
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-arrow-up"></i> {{ $weightChange }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">No change</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $progress->notes ? Str::limit($progress->notes, 50) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{ $progressData->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No progress data yet. 
        <a href="{{ route('client.progress.log-form') }}">Log your first entry</a>
    </div>
@endif

@endsection

@section('scripts')
<script>
@if ($progressData->count() > 0)
    fetch('{{ route("client.progress.chart-data") }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Weight (kg)',
                        data: data.weights,
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
        });
@endif
</script>
@endsection
