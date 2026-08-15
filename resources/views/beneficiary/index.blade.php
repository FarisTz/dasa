@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Beneficiary Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-0">Welcome back, <strong>{{ Auth::user()->name }}</strong>!</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt"></i> {{ now()->format('l, F d, Y') }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-clock"></i> {{ now()->format('h:i A') }}
                        </p>
                        <p class="mt-2 mb-0">
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Beneficiary Status
                            </span>
                            <span class="badge badge-info ml-2">
                                <i class="fas fa-award"></i> Scholarship Recipient
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <div class="beneficiary-badge">
                            <i class="fas fa-user-graduate" style="font-size: 48px; color: #28a745;"></i>
                            <h6 class="mt-2 text-muted">Beneficiary</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Received</h4>
                        </div>
                        <div class="card-body">
                            TSh {{ number_format($totalReceived ?? 0, 2) }}
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
                            <h4>Approved Payments</h4>
                        </div>
                        <div class="card-body">
                            {{ $approvedPayments ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingPayments ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-instalment"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Installments</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalInstallments ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-primary text-white mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 28px;">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h5>Sign Installment</h5>
                        <p class="text-muted">Sign for your pending installments</p>
                        <a href="{{ route('beneficiary.payments.index') }}" class="btn btn-primary">
                            <i class="fas fa-pen"></i> Sign Now
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-success text-white mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 28px;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5>My Application</h5>
                        <p class="text-muted">View your scholarship application</p>
                        <a href="{{ route('applicant.my-application') }}" class="btn btn-success">
                            <i class="fas fa-eye"></i> View Application
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-info text-white mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 28px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5>My Profile</h5>
                        <p class="" class="btn btn-info">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-history text-primary"></i> Recent Payments</h4>
                        <div class="card-header-action">
                            <a href="{{ route('beneficiary.payments.index') }}" class="btn btn-primary btn-sm">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentPayments && $recentPayments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Installment</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $payment->installment->inst_number ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">Year {{ $payment->installment->student_year ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <strong class="text-success">TSh {{ number_format($payment->amount, 2) }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $payment->status_color }}">
                                                        <i class="fas fa-{{ $payment->status_icon }}"></i>
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($payment->confirmed_at)
                                                        {{ $payment->confirmed_at->format('M d, Y') }}
                                                        <br>
                                                        <small class="text-muted">{{ $payment->confirmed_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('beneficiary.payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No payments found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Status Summary -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-pie text-primary"></i> Payment Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="payment-status-summary">
                            <div class="status-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i></span>
                                        Approved
                                    </span>
                                    <span class="font-weight-bold">{{ $approvedPayments ?? 0 }}</span>
                                </div>
                                <div class="progress mt-1">
                                    @php
                                        $total = ($approvedPayments ?? 0) + ($pendingPayments ?? 0) + ($rejectedPayments ?? 0);
                                        $approvedPercent = $total > 0 ? round(($approvedPayments ?? 0) / $total * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $approvedPercent }}%"></div>
                                </div>
                            </div>
                            <div class="status-item mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i></span>
                                        Pending
                                    </span>
                                    <span class="font-weight-bold">{{ $pendingPayments ?? 0 }}</span>
                                </div>
                                <div class="progress mt-1">
                                    @php
                                        $pendingPercent = $total > 0 ? round(($pendingPayments ?? 0) / $total * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-warning" style="width: {{ $pendingPercent }}%"></div>
                                </div>
                            </div>
                            <div class="status-item mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i></span>
                                        Rejected
                                    </span>
                                    <span class="font-weight-bold">{{ $rejectedPayments ?? 0 }}</span>
                                </div>
                                <div class="progress mt-1">
                                    @php
                                        $rejectedPercent = $total > 0 ? round(($rejectedPayments ?? 0) / $total * 100) : 0;
                                    @endphp
                                    <div class="progress-bar bg-danger" style="width: {{ $rejectedPercent }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <div class="row">
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <small class="text-muted">Total Amount</small>
                                        <h6 class="font-weight-bold text-success">
                                            TSh {{ number_format($totalAmount ?? 0, 2) }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <small class="text-muted">Received</small>
                                        <h6 class="font-weight-bold text-primary">
                                            TSh {{ number_format($totalReceived ?? 0, 2) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming and Active Installments -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-clock text-primary"></i> Pending Signatures</h4>
                        <div class="card-header-action">
                            <span class="badge badge-warning">
                                {{ $pendingSignatures ?? 0 }} pending
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($pendingInstallments && $pendingInstallments->count() > 0)
                            @foreach($pendingInstallments as $installment)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $installment->inst_number }}</h6>
                                            <small class="text-muted">
                                                Year {{ $installment->student_year }} • {{ $installment->academic_year }}
                                            </small>
                                            <br>
                                            <span class="text-success font-weight-bold">
                                                TSh {{ number_format($installment->amount, 2) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('beneficiary.payments.sign', $installment->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-pen"></i> Sign
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">No pending signatures. All caught up!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle text-primary"></i> Quick Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-user text-primary"></i>
                                        <span class="ml-2">Name</span>
                                    </div>
                                    <span class="font-weight-bold">{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-envelope text-primary"></i>
                                        <span class="ml-2">Email</span>
                                    </div>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-phone text-primary"></i>
                                        <span class="ml-2">Phone</span>
                                    </div>
                                    <span>{{ Auth::user()->phone_number ?? 'Not set' }}</span>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-award text-primary"></i>
                                        <span class="ml-2">Scholarship</span>
                                    </div>
                                    <span class="text-success font-weight-bold">
                                        {{ $scholarshipTitle ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                        <span class="ml-2">Member Since</span>
                                    </div>
                                    <span>{{ Auth::user()->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements/Notifications -->
        @if($notifications && $notifications->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-bell text-primary"></i> Notifications</h4>
                        </div>
                        <div class="card-body">
                            @foreach($notifications as $notification)
                                <div class="alert alert-{{ $notification['type'] ?? 'info' }} alert-dismissible fade show" role="alert">
                                    <i class="fas fa-{{ $notification['icon'] ?? 'info-circle' }}"></i>
                                    {{ $notification['message'] ?? '' }}
                                    <small class="text-muted float-right">{{ $notification['date'] ?? '' }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Support Contact -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-headset text-primary"></i> Need Help?</h6>
                        <p class="text-muted">
                            Contact our support team for assistance with your scholarship or payments.
                        </p>
                        <a href="{{ route('support') }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope"></i> Contact Support
                        </a>
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
        font-size: 22px;
        font-weight: 700;
        padding: 5px 0;
        color: #2d2d2d;
    }
    .icon-circle {
        transition: transform 0.3s ease;
    }
    .icon-circle:hover {
        transform: scale(1.1);
    }
    .status-item .progress {
        height: 6px;
        border-radius: 3px;
        background-color: #e9ecef;
    }
    .status-item .progress .progress-bar {
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    .beneficiary-badge {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        display: inline-block;
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
        .card-statistic-1 {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush
