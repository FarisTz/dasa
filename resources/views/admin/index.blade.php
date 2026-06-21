@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Admin Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        </div>
    </div>

    <div class="section-body">
        <!-- Welcome Message -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-0">Welcome back, <strong>{{ Auth::user()->name }}</strong>!</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt"></i> {{ $currentTime->format('l, F d, Y') }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-clock"></i> {{ $currentTime->format('h:i A') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-check-circle"></i> System Active
                        </span>
                        <span class="badge badge-info badge-lg ml-2">
                            <i class="fas fa-users"></i> {{ $totalUsers }} Users
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Users</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalUsers }}
                        </div>
                        <div class="card-footer">
                            <small>
                                <span class="text-success"><i class="fas fa-arrow-up"></i> {{ $activeUsers }} Active</span>
                                <span class="text-danger ml-2"><i class="fas fa-arrow-down"></i> {{ $inactiveUsers + $suspendedUsers }} Inactive</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Applications</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalApplications }}
                        </div>
                        <div class="card-footer">
                            <small>
                                <span class="text-warning"><i class="fas fa-clock"></i> {{ $pendingApplications }} Pending</span>
                                <span class="text-success ml-2"><i class="fas fa-check"></i> {{ $approvedFullApplications + $approvedPartialApplications }} Approved</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Scholarships</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalScholarships }}
                        </div>
                        <div class="card-footer">
                            <small>
                                <span class="text-success"><i class="fas fa-door-open"></i> {{ $openScholarships }} Open</span>
                                <span class="text-danger ml-2"><i class="fas fa-door-closed"></i> {{ $closedScholarships }} Closed</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Completion Rate</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $totalRequired = 4; // personal_info, o_level, a_level, motivation
                                $completedUsers = 0;
                                $users = \App\Models\User::all();
                                foreach($users as $user) {
                                    $completed = 0;
                                    if($user->personalInfo) $completed++;
                                    if($user->oLevelEducation) $completed++;
                                    if($user->aLevelEducation) $completed++;
                                    if($user->motivation) $completed++;
                                    if($completed == $totalRequired) $completedUsers++;
                                }
                                $completionRate = $totalUsers > 0 ? round(($completedUsers / $totalUsers) * 100) : 0;
                            @endphp
                            {{ $completionRate }}%
                        </div>
                        <div class="card-footer">
                            <small>
                                <span class="text-success"><i class="fas fa-check-circle"></i> {{ $completedUsers }} Completed</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-4">
            <div class="col-lg-8 col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-bar text-primary"></i> Application Trends</h4>
                        <div class="card-header-action">
                            <select class="form-control form-control-sm" id="chartPeriod">
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="applicationsChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie text-primary"></i> Application Status</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statusPieChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Row -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-alt text-primary"></i> Recent Applications</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.applications.index') }}" class="btn btn-primary btn-sm">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentApplications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Applicant</th>
                                            <th>Scholarship</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentApplications as $application)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $application->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                             alt="{{ $application->user->name }}"
                                                             class="rounded-circle mr-2"
                                                             width="25"
                                                             height="25">
                                                        <span>{{ $application->user->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ Str::limit($application->scholarship->title ?? 'N/A', 20) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $application->status == 'submitted' ? 'primary' :
                                                        ($application->status == 'pending' ? 'secondary' :
                                                        ($application->status == 'under_review' ? 'warning' :
                                                        ($application->status == 'approved_full' ? 'success' :
                                                        ($application->status == 'approved_partial' ? 'info' : 'danger')))) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ $application->created_at->diffForHumans() }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent applications</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-users text-primary"></i> Recent Users</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentUsers->count() > 0)
                            <div class="list-group">
                                @foreach($recentUsers as $user)
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $user->name }}"
                                                     class="rounded-circle mr-3"
                                                     width="40"
                                                     height="40">
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'coordinator' ? 'warning' : ($user->role == 'beneficiary' ? 'success' : 'info')) }}">
                                                    {{ ucfirst($user->role ?? 'applicant') }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent users</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Stats Row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-tasks text-primary"></i> Application Completion Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="completion-stat">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Personal Info</span>
                                        <span class="badge badge-{{ $completionStats['personal_info']['percentage'] >= 80 ? 'success' : ($completionStats['personal_info']['percentage'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $completionStats['personal_info']['percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $completionStats['personal_info']['percentage'] >= 80 ? 'success' : ($completionStats['personal_info']['percentage'] >= 50 ? 'warning' : 'danger') }}"
                                             style="width: {{ $completionStats['personal_info']['percentage'] }}%;">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completionStats['personal_info']['completed'] }} / {{ $completionStats['personal_info']['total'] }} users</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="completion-stat">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>O-Level</span>
                                        <span class="badge badge-{{ $completionStats['o_level']['percentage'] >= 80 ? 'success' : ($completionStats['o_level']['percentage'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $completionStats['o_level']['percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $completionStats['o_level']['percentage'] >= 80 ? 'success' : ($completionStats['o_level']['percentage'] >= 50 ? 'warning' : 'danger') }}"
                                             style="width: {{ $completionStats['o_level']['percentage'] }}%;">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completionStats['o_level']['completed'] }} / {{ $completionStats['o_level']['total'] }} users</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="completion-stat">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>A-Level</span>
                                        <span class="badge badge-{{ $completionStats['a_level']['percentage'] >= 80 ? 'success' : ($completionStats['a_level']['percentage'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $completionStats['a_level']['percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $completionStats['a_level']['percentage'] >= 80 ? 'success' : ($completionStats['a_level']['percentage'] >= 50 ? 'warning' : 'danger') }}"
                                             style="width: {{ $completionStats['a_level']['percentage'] }}%;">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completionStats['a_level']['completed'] }} / {{ $completionStats['a_level']['total'] }} users</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="completion-stat">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Motivation</span>
                                        <span class="badge badge-{{ $completionStats['motivation']['percentage'] >= 80 ? 'success' : ($completionStats['motivation']['percentage'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $completionStats['motivation']['percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $completionStats['motivation']['percentage'] >= 80 ? 'success' : ($completionStats['motivation']['percentage'] >= 50 ? 'warning' : 'danger') }}"
                                             style="width: {{ $completionStats['motivation']['percentage'] }}%;">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completionStats['motivation']['completed'] }} / {{ $completionStats['motivation']['total'] }} users</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Scholarships -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-trophy text-primary"></i> Top Scholarships</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.scholarships.index') }}" class="btn btn-primary btn-sm">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($topScholarships->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Scholarship</th>
                                            <th>Academic Year</th>
                                            <th>Status</th>
                                            <th>Applications</th>
                                            <th>Deadline</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topScholarships as $index => $scholarship)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $scholarship->title }}</strong></td>
                                                <td>{{ $scholarship->academic_year }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : ($scholarship->status == 'draft' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($scholarship->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $scholarship->applications_count }}</span>
                                                </td>
                                                <td>
                                                    @if($scholarship->deadline)
                                                        <small>
                                                            {{ $scholarship->deadline->format('M d, Y') }}
                                                            <br>
                                                            @if(now()->gt($scholarship->deadline))
                                                                <span class="text-danger">Expired</span>
                                                            @else
                                                                <span class="text-success">{{ $scholarship->deadline->diffForHumans() }}</span>
                                                            @endif
                                                        </small>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No scholarships available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-bolt text-primary"></i> Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary btn-lg btn-block mb-2">
                                    <i class="fas fa-plus"></i> Create Scholarship
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-lg btn-block mb-2">
                                    <i class="fas fa-user-plus"></i> Add User
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-info btn-lg btn-block mb-2">
                                    <i class="fas fa-file-alt"></i> View Applications
                                </a>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <a href="{{ route('admin.applications.export') }}" class="btn btn-warning btn-lg btn-block mb-2">
                                    <i class="fas fa-file-export"></i> Export Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card-statistic-1 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        padding: 15px;
        height: 100%;
    }
    .card-statistic-1:hover {
        transform: translateY(-5px);
    }
    .card-statistic-1 .card-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 24px;
    }
    .card-statistic-1 .card-wrap {
        padding-left: 10px;
    }
    .card-statistic-1 .card-header {
        padding: 0;
        background: none;
        border: none;
    }
    .card-statistic-1 .card-header h4 {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card-statistic-1 .card-body {
        font-size: 24px;
        font-weight: 700;
        padding: 5px 0;
        color: #2d2d2d;
    }
    .card-statistic-1 .card-footer {
        padding: 5px 0 0 0;
        background: none;
        border: none;
    }
    .card-statistic-2 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        padding: 20px;
        text-align: center;
        height: 100%;
    }
    .card-statistic-2:hover {
        transform: translateY(-5px);
    }
    .card-statistic-2 .card-icon {
        width: 50px;
        height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 20px;
        margin-bottom: 10px;
    }
    .card-statistic-2 .card-header {
        padding: 0;
        background: none;
        border: none;
    }
    .card-statistic-2 .card-header h4 {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card-statistic-2 .card-body {
        font-size: 22px;
        font-weight: 700;
        padding: 5px 0;
        color: #2d2d2d;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
        border-color: #e9ecef;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .completion-stat {
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .completion-stat:last-child {
        margin-bottom: 0;
    }
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
    .shadow-primary {
        box-shadow: 0 2px 10px rgba(52, 144, 220, 0.3);
    }
    .shadow-success {
        box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
    }
    .shadow-warning {
        box-shadow: 0 2px 10px rgba(255, 193, 7, 0.3);
    }
    .shadow-danger {
        box-shadow: 0 2px 10px rgba(220, 53, 69, 0.3);
    }
    .shadow-info {
        box-shadow: 0 2px 10px rgba(23, 162, 184, 0.3);
    }
    .btn-block {
        padding: 15px;
        font-size: 16px;
    }
    @media print {
        .section-header-breadcrumb,
        .card-header-action,
        .btn,
        .no-print {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
        .card-statistic-1,
        .card-statistic-2 {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare chart data
        const monthlyData = @json($monthlyStats);

        // Applications Chart
        const ctx = document.getElementById('applicationsChart').getContext('2d');
        let applicationsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(function(item) {
                    // Create date from year and month
                    const date = new Date(item.year, item.month - 1, 1);
                    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Applications',
                    data: monthlyData.map(function(item) {
                        return item.total;
                    }),
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
                labels: ['Pending', 'Submitted', 'Under Review', 'Approved Full', 'Approved Partial', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $pendingApplications }},
                        {{ $submittedApplications }},
                        {{ $underReviewApplications }},
                        {{ $approvedFullApplications }},
                        {{ $approvedPartialApplications }},
                        {{ $rejectedApplications }}
                    ],
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
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Chart period change
        document.getElementById('chartPeriod').addEventListener('change', function() {
            const period = this.value;
            fetch(`{{ route('admin.dashboard.chart-data') }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    let labels, values;

                    if (period === 'weekly') {
                        labels = data.map(item => item.date);
                        values = data.map(item => item.total);
                    } else if (period === 'monthly') {
                        labels = data.map(function(item) {
                            const date = new Date(item.year, item.month - 1, 1);
                            return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                        });
                        values = data.map(item => item.total);
                    } else {
                        // Yearly
                        labels = data.map(item => item.year);
                        values = data.map(item => item.total);
                    }

                    applicationsChart.data.labels = labels;
                    applicationsChart.data.datasets[0].data = values;
                    applicationsChart.update();
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                });
        });
    });
</script>
@endpush
