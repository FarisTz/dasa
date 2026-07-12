@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Scholarship Utilization Report</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></div>
            <div class="breadcrumb-item">Utilization Report</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary"><i class="fas fa-file-alt"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Payments</h4></div>
                        <div class="card-body">{{ $paymentStats['total_payments'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Amount</h4></div>
                        <div class="card-body">TSh {{ number_format($paymentStats['total_amount'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info"><i class="fas fa-check-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Approved Amount</h4></div>
                        <div class="card-body">TSh {{ number_format($paymentStats['approved_amount'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-clock"></i></div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Pending Amount</h4></div>
                        <div class="card-body">TSh {{ number_format($paymentStats['pending_amount'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie text-primary"></i> Payment Status Breakdown</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentStatusPieChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-line text-primary"></i> Monthly Payment Trends</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyPaymentChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scholarship Utilization Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-award text-primary"></i> Scholarship Utilization</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Scholarship</th>
                                <th>Academic Year</th>
                                <th>Applications</th>
                                <th>Approved</th>
                                <th>Beneficiaries</th>
                                <th>Utilization Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scholarshipStats as $index => $stat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stat['scholarship']->title }}</td>
                                    <td>{{ $stat['scholarship']->academic_year }}</td>
                                    <td>{{ $stat['total_applications'] }}</td>
                                    <td>{{ $stat['approved_applications'] }}</td>
                                    <td>{{ $stat['beneficiaries'] }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $stat['utilization_rate'] >= 70 ? 'success' : ($stat['utilization_rate'] >= 40 ? 'warning' : 'danger') }}"
                                                 style="width: {{ $stat['utilization_rate'] }}%;">
                                                {{ $stat['utilization_rate'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No data available</td>
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
                        <a href="{{ route('admin.reports.export', ['type' => 'utilization']) }}" class="btn btn-success btn-lg px-5">
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
        // Payment Status Pie Chart
        const pieCtx = document.getElementById('paymentStatusPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Approved', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $statusBreakdown['pending'] }},
                        {{ $statusBreakdown['confirmed'] }},
                        {{ $statusBreakdown['approved'] }},
                        {{ $statusBreakdown['rejected'] }}
                    ],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 10, font: { size: 11 } }
                    }
                }
            }
        });

        // Monthly Payment Trends
        const monthlyCtx = document.getElementById('monthlyPaymentChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Payment Amount (TSh)',
                    data: @json($monthlyPaymentAmounts),
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Number of Payments',
                    data: @json($monthlyPaymentCounts),
                    backgroundColor: 'rgba(52, 144, 220, 0.2)',
                    borderColor: 'rgba(52, 144, 220, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 10, font: { size: 11 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return 'TSh ' + value.toLocaleString();
                            }
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
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
