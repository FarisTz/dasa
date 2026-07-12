@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Beneficiary Financial Report</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></div>
            <div class="breadcrumb-item">Financial Report</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Filter -->
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.financial') }}" class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Academic Year</label>
                            <select class="form-control" name="academic_year">
                                <option value="">All Years</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Suspension Status</label>
                            <select class="form-control" name="suspension_status">
                                <option value="">All</option>
                                <option value="active" {{ request('suspension_status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ request('suspension_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Beneficiaries</h4></div>
                        <div class="card-body">{{ $summary['total_beneficiaries'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Allocated</h4></div>
                        <div class="card-body">TSh {{ number_format($summary['total_allocated'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Received</h4></div>
                        <div class="card-body">TSh {{ number_format($summary['total_received'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Suspended</h4></div>
                        <div class="card-body">{{ $summary['suspended_count'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie text-primary"></i> Payment Status Distribution</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentStatusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar text-primary"></i> Amount Distribution</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="amountDistributionChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Beneficiaries -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-trophy text-primary"></i> Top Beneficiaries</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Allocated</th>
                                <th>Total Received</th>
                                <th>Payment Count</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topBeneficiaries as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data['beneficiary']->name }}</td>
                                    <td>{{ $data['beneficiary']->email }}</td>
                                    <td>TSh {{ number_format($data['total_allocated'], 2) }}</td>
                                    <td class="text-success">TSh {{ number_format($data['total_received'], 2) }}</td>
                                    <td>{{ $data['payment_count'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $data['is_suspended'] ? 'danger' : 'success' }}">
                                            {{ $data['is_suspended'] ? 'Suspended' : 'Active' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No beneficiaries found</td>
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
                        <a href="{{ route('admin.reports.export', ['type' => 'financial']) }}" class="btn btn-success btn-lg px-5">
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
        // Payment Status Chart
        const paymentCtx = document.getElementById('paymentStatusChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Approved', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $paymentStatusCounts['pending'] }},
                        {{ $paymentStatusCounts['confirmed'] }},
                        {{ $paymentStatusCounts['approved'] }},
                        {{ $paymentStatusCounts['rejected'] }}
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

        // Amount Distribution Chart
        const amountCtx = document.getElementById('amountDistributionChart').getContext('2d');
        new Chart(amountCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($amountRanges)),
                datasets: [{
                    label: 'Beneficiaries',
                    data: @json(array_values($amountRanges)),
                    backgroundColor: 'rgba(52, 144, 220, 0.6)',
                    borderColor: 'rgba(52, 144, 220, 1)',
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
    });
</script>
@endpush
