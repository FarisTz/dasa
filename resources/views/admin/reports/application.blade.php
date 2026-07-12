@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Application Report</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></div>
            <div class="breadcrumb-item">Application Report</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Applications</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalApplications }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Approved</h4>
                        </div>
                        <div class="card-body">
                            {{ $approvedApplications }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Approval Rate</h4>
                        </div>
                        <div class="card-body">
                            {{ $approvalRate }}%
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Rejection Rate</h4>
                        </div>
                        <div class="card-body">
                            {{ $rejectionRate }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-line text-primary"></i> Monthly Application Trends</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie text-primary"></i> Status Distribution</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statusPieChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- More Charts -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar text-primary"></i> Applications by Scholarship</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="scholarshipChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-area text-primary"></i> Monthly Status Trends</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statusTrendChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="{{ route('admin.reports.export', ['type' => 'application']) }}" class="btn btn-success btn-lg px-5">
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
        // Monthly Trends Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Applications',
                    data: @json($monthlyCounts),
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
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Status Pie Chart
        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: @json($statusLabels),
                datasets: [{
                    data: @json($statusData),
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(52, 144, 220, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        '#6c757d',
                        '#3490dc',
                        '#ffc107',
                        '#28a745',
                        '#17a2b8',
                        '#dc3545'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 10,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Scholarship Chart
        const scholarCtx = document.getElementById('scholarshipChart').getContext('2d');
        new Chart(scholarCtx, {
            type: 'bar',
            data: {
                labels: @json($scholarshipLabels),
                datasets: [{
                    label: 'Applications',
                    data: @json($scholarshipCounts),
                    backgroundColor: 'rgba(40, 167, 69, 0.6)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Status Trend Chart
        const trendCtx = document.getElementById('statusTrendChart').getContext('2d');
        const statusColors = {
            'pending': 'rgba(108, 117, 125, 0.8)',
            'submitted': 'rgba(52, 144, 220, 0.8)',
            'under_review': 'rgba(255, 193, 7, 0.8)',
            'approved_full': 'rgba(40, 167, 69, 0.8)',
            'approved_partial': 'rgba(23, 162, 184, 0.8)',
            'rejected': 'rgba(220, 53, 69, 0.8)'
        };

        const statusBorderColors = {
            'pending': '#6c757d',
            'submitted': '#3490dc',
            'under_review': '#ffc107',
            'approved_full': '#28a745',
            'approved_partial': '#17a2b8',
            'rejected': '#dc3545'
        };

        const datasets = [];
        @foreach($statuses as $status)
            const data{{ $loop->index }} = [];
            @foreach($months as $month)
                data{{ $loop->index }}.push({{ $statusDataByMonth[$status][$month] ?? 0 }});
            @endforeach
            datasets.push({
                label: '{{ ucfirst(str_replace('_', ' ', $status)) }}',
                data: data{{ $loop->index }},
                backgroundColor: statusColors['{{ $status }}'],
                borderColor: statusBorderColors['{{ $status }}'],
                borderWidth: 1
            });
        @endforeach

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 10,
                            font: { size: 10 }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
