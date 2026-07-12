@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Academic Performance Report</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></div>
            <div class="breadcrumb-item">Academic Performance</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary"><i class="fas fa-file-alt"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Results</h4></div>
                        <div class="card-body">{{ $stats['total_results'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Approved</h4></div>
                        <div class="card-body">{{ $stats['approved_results'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-clock"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Pending</h4></div>
                        <div class="card-body">{{ $stats['pending_results'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger"><i class="fas fa-times-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Rejected</h4></div>
                        <div class="card-body">{{ $stats['rejected_results'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info"><i class="fas fa-chart-line"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Avg GPA</h4></div>
                        <div class="card-body">{{ number_format($stats['average_gpa_overall'] ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger"><i class="fas fa-ban"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Suspended</h4></div>
                        <div class="card-body">{{ $stats['suspended_students'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar text-primary"></i> GPA Distribution</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="gpaDistributionChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-line text-primary"></i> Performance by Academic Year</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="yearlyPerformanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-trophy text-primary"></i> Top Performers</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Average GPA</th>
                                <th>Total Results</th>
                                <th>Latest Result</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentPerformance as $index => $performance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $performance['student']->name ?? 'N/A' }}</td>
                                    <td>{{ $performance['student']->email ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $performance['average_gpa'] >= 3.5 ? 'success' : ($performance['average_gpa'] >= 2.5 ? 'warning' : 'danger') }}">
                                            {{ number_format($performance['average_gpa'], 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $performance['total_results'] }}</td>
                                    <td>
                                        @if($performance['latest_result'])
                                            {{ $performance['latest_result']->academic_year }}<br>
                                            <small>GPA: {{ $performance['latest_result']->formatted_gpa }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($performance['student'] && $performance['student']->is_academic_suspended)
                                            <span class="badge badge-danger">Suspended</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No results found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Export -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="{{ route('admin.reports.export', ['type' => 'academic']) }}" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-file-export"></i> Export Report as CSV
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="fas fa-arrow-left"></i> Back to Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // GPA Distribution Chart
        const gpaCtx = document.getElementById('gpaDistributionChart').getContext('2d');
        new Chart(gpaCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($gpaDistribution)),
                datasets: [{
                    label: 'Students',
                    data: @json(array_values($gpaDistribution)),
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(52, 144, 220, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(255, 133, 27, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        '#28a745', '#17a2b8', '#3490dc',
                        '#ffc107', '#ff851b', '#dc3545'
                    ],
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Yearly Performance Chart
        const yearlyCtx = document.getElementById('yearlyPerformanceChart').getContext('2d');
        new Chart(yearlyCtx, {
            type: 'line',
            data: {
                labels: @json($yearlyPerformance->pluck('academic_year')),
                datasets: [{
                    label: 'Average GPA',
                    data: @json($yearlyPerformance->pluck('avg_gpa')),
                    backgroundColor: 'rgba(52, 144, 220, 0.2)',
                    borderColor: 'rgba(52, 144, 220, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4,
                        ticks: { stepSize: 0.5 }
                    }
                }
            }
        });
    });
</script>
@endpush
