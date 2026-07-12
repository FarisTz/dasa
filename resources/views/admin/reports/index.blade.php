@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Reports Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Reports</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total Applications</h4></div>
                        <div class="card-body">{{ $stats['total_applications'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Approved</h4></div>
                        <div class="card-body">{{ $stats['approved_applications'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Pending</h4></div>
                        <div class="card-body">{{ $stats['pending_applications'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Open Scholarships</h4></div>
                        <div class="card-body">{{ $stats['open_scholarships'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Cards -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-alt text-primary"></i> Application Report</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">View detailed application statistics including status breakdown, monthly trends, and scholarship distribution.</p>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.reports.application') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-chart-bar"></i> View Application Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-money-bill-wave text-success"></i> Financial Report</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">View beneficiary financial summary including allocations, payments, and suspension status.</p>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.reports.financial') }}" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-chart-pie"></i> View Financial Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-graduation-cap text-info"></i> Academic Performance Report</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">View student academic performance including GPA distribution, trends, and top performers.</p>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.reports.academic') }}" class="btn btn-info btn-lg px-5">
                                <i class="fas fa-chart-line"></i> View Academic Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-award text-warning"></i> Scholarship Utilization Report</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">View scholarship utilization including application rates, payment distribution, and trends.</p>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.reports.utilization') }}" class="btn btn-warning btn-lg px-5">
                                <i class="fas fa-chart-area"></i> View Utilization Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
