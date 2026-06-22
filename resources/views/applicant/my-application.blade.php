@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>My Application</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">My Application</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Status Messages -->
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

        @if($application)
            <!-- Application Status Card -->
            <div class="card">
                <div class="card-header">
                    <h4>Application Summary</h4>
                    <div class="card-header-action">
                        <span class="badge badge-{{ $application->status == 'submitted' ? 'info' :
                            ($application->status == 'pending' ? 'warning' :
                            ($application->status == 'under_review' ? 'primary' :
                            ($application->status == 'approved_full' || $application->status == 'approved_partial' ? 'success' : 'danger'))) }} badge-lg">
                            <i class="fas fa-{{ $application->status == 'submitted' ? 'clock' :
                                ($application->status == 'pending' ? 'hourglass-half' :
                                ($application->status == 'under_review' ? 'search' :
                                ($application->status == 'approved_full' || $application->status == 'approved_partial' ? 'check-circle' : 'times-circle'))) }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="statistic-card bg-primary text-white p-4 rounded">
                                <div class="statistic-icon">
                                    <i class="fas fa-user-graduate fa-2x"></i>
                                </div>
                                <div class="statistic-details">
                                    <span class="statistic-label">Applicant Name</span>
                                    <h5 class="mt-2 mb-0">{{ Auth::user()->name }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="statistic-card bg-info text-white p-4 rounded">
                                <div class="statistic-icon">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                                <div class="statistic-details">
                                    <span class="statistic-label">Submitted Date</span>
                                    <h5 class="mt-2 mb-0">{{ $application->submitted_at ? $application->submitted_at->format('F d, Y') : 'Not submitted' }}</h5>
                                    <small>{{ $application->submitted_at ? $application->submitted_at->diffForHumans() : '' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="statistic-card bg-success text-white p-4 rounded">
                                <div class="statistic-icon">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <div class="statistic-details">
                                    <span class="statistic-label">Last Updated</span>
                                    <h5 class="mt-2 mb-0">{{ $application->updated_at ? $application->updated_at->format('F d, Y') : 'N/A' }}</h5>
                                    <small>{{ $application->updated_at ? $application->updated_at->diffForHumans() : '' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Details -->
            <div class="row">
                <!-- Scholarship Details -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-award text-primary"></i> Scholarship Details</h4>
                        </div>
                        <div class="card-body">
                            @if($application->scholarship)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="35%">Scholarship Title</th>
                                                <td><strong>{{ $application->scholarship->title }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Academic Year</th>
                                                <td>{{ $application->scholarship->academic_year }}</td>
                                            </tr>
                                            <tr>
                                                <th>Deadline</th>
                                                <td>
                                                    <span class="badge badge-{{ now()->gt($application->scholarship->deadline) ? 'danger' : 'success' }}">
                                                        {{ $application->scholarship->deadline->format('F d, Y') }}
                                                        @if(now()->gt($application->scholarship->deadline))
                                                            <i class="fas fa-exclamation-circle"></i> (Expired)
                                                        @else
                                                            <i class="fas fa-clock"></i> {{ $application->scholarship->deadline->diffForHumans() }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge badge-{{ $application->scholarship->status == 'open' ? 'success' : ($application->scholarship->status == 'draft' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($application->scholarship->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <a href="" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Scholarship
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Scholarship details not found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Application Status Details -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-info-circle text-primary"></i> Application Status</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="35%">Application ID</th>
                                            <td><code>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Current Status</th>
                                            <td>
                                                <span class="badge badge-{{ $application->status == 'submitted' ? 'info' :
                                                    ($application->status == 'pending' ? 'warning' :
                                                    ($application->status == 'under_review' ? 'primary' :
                                                    ($application->status == 'approved_full' ? 'success' :
                                                    ($application->status == 'approved_partial' ? 'info' : 'danger')))) }} badge-lg">
                                                    <i class="fas fa-{{ $application->status == 'submitted' ? 'clock' :
                                                        ($application->status == 'pending' ? 'hourglass-half' :
                                                        ($application->status == 'under_review' ? 'search' :
                                                        ($application->status == 'approved_full' ? 'check-circle' :
                                                        ($application->status == 'approved_partial' ? 'check-circle' : 'times-circle')))) }}"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Submitted Date</th>
                                            <td>
                                                @if($application->submitted_at)
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                    {{ $application->submitted_at->format('F d, Y H:i A') }}
                                                    <br>
                                                    <small class="text-muted">{{ $application->submitted_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Not submitted yet</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Review Duration</th>
                                            <td>
                                                @if($application->submitted_at)
                                                    @php
                                                        $days = $application->submitted_at->diffInDays(now());
                                                    @endphp
                                                    <span class="badge badge-{{ $days > 30 ? 'warning' : 'info' }}">
                                                        {{ $days }} days in review
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Timeline -->
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-history text-primary"></i> Application Timeline</h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Created -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">Application Created</h5>
                                <p class="timeline-text">
                                    <i class="fas fa-calendar-alt"></i> {{ $application->created_at->format('F d, Y H:i A') }}
                                    <br>
                                    <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>

                        <!-- Submitted -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $application->submitted_at ? 'primary' : 'secondary' }}">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">Application Submitted</h5>
                                <p class="timeline-text">
                                    @if($application->submitted_at)
                                        <i class="fas fa-calendar-alt"></i> {{ $application->submitted_at->format('F d, Y H:i A') }}
                                        <br>
                                        <small class="text-muted">{{ $application->submitted_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not submitted yet</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Under Review -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $application->status == 'under_review' || $application->status == 'approved_full' || $application->status == 'approved_partial' || $application->status == 'rejected' ? 'warning' : 'secondary' }}">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">Under Review</h5>
                                <p class="timeline-text">
                                    @if($application->status == 'under_review' || $application->status == 'approved_full' || $application->status == 'approved_partial' || $application->status == 'rejected')
                                        <span class="text-success">Application is being reviewed</span>
                                        @if($application->updated_at)
                                            <br>
                                            <small class="text-muted">Last updated: {{ $application->updated_at->diffForHumans() }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Waiting for review</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Final Decision -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $application->status == 'approved_full' ? 'success' : ($application->status == 'approved_partial' ? 'info' : ($application->status == 'rejected' ? 'danger' : 'secondary')) }}">
                                <i class="fas fa-{{ $application->status == 'approved_full' || $application->status == 'approved_partial' ? 'check' : ($application->status == 'rejected' ? 'times' : 'hourglass-half') }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="timeline-title">Final Decision</h5>
                                <p class="timeline-text">
                                    @if($application->status == 'approved_full')
                                        <span class="text-success"><strong>Approved (Full Scholarship)</strong></span>
                                        <br>
                                        <i class="fas fa-check-circle text-success"></i> Congratulations! Your application has been approved.
                                    @elseif($application->status == 'approved_partial')
                                        <span class="text-info"><strong>Approved (Partial Scholarship)</strong></span>
                                        <br>
                                        <i class="fas fa-check-circle text-info"></i> Your application has been partially approved.
                                    @elseif($application->status == 'rejected')
                                        <span class="text-danger"><strong>Rejected</strong></span>
                                        <br>
                                        <i class="fas fa-times-circle text-danger"></i> We regret to inform you that your application was not successful.
                                    @else
                                        <span class="text-muted">Awaiting decision</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($application->admin_notes)
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-sticky-note text-primary"></i> Admin Notes</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <div style="white-space: pre-wrap; margin-top: 8px;">
                                {{ $application->admin_notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            @if($application->status == 'submitted' || $application->status == 'pending' || $application->status == 'under_review')
                                <a href="{{ route('applicant.application.review') }}" class="btn btn-primary btn-lg px-5 mr-2">
                                    <i class="fas fa-eye"></i> View Full Application
                                </a>
                                @if($application->status == 'submitted' || $application->status == 'pending')
                                    <a href="{{ route('applicant.application.edit') }}" class="btn btn-warning btn-lg px-5 mr-2">
                                        <i class="fas fa-edit"></i> Edit Application
                                    </a>
                                @endif
                            @endif

                            @if($application->status == 'approved_full' || $application->status == 'approved_partial')
                                <a href="{{ route('applicant.acceptance.download') }}" class="btn btn-success btn-lg px-5 mr-2">
                                    <i class="fas fa-file-pdf"></i> Download Acceptance Letter
                                </a>
                            @endif

                            @if($application->status == 'rejected')
                                <a href="{{ route('applicant.application.contact') }}" class="btn btn-info btn-lg px-5">
                                    <i class="fas fa-envelope"></i> Contact Support
                                </a>
                            @endif

                            
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- No Application Found -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="empty-state" data-height="400">
                        <div class="empty-state-icon bg-light">
                            <i class="fas fa-file-alt text-muted"></i>
                        </div>
                        <h2>No Application Found</h2>
                        <p class="lead">
                            You haven't submitted any application yet.
                        </p>
                        <a href="{{ route('applicant.personal_information') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-plus"></i> Apply for Scholarship
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .statistic-card {
        transition: transform 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .statistic-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    .statistic-icon {
        float: right;
        opacity: 0.3;
    }
    .statistic-details {
        position: relative;
        z-index: 1;
    }
    .statistic-label {
        font-size: 14px;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 60px;
        margin-bottom: 30px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .timeline-marker.bg-success {
        background: #28a745;
    }
    .timeline-marker.bg-primary {
        background: #007bff;
    }
    .timeline-marker.bg-warning {
        background: #ffc107;
        color: #212529;
    }
    .timeline-marker.bg-secondary {
        background: #6c757d;
    }
    .timeline-marker.bg-danger {
        background: #dc3545;
    }
    .timeline-marker.bg-info {
        background: #17a2b8;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        width: 2px;
        height: calc(100% + 10px);
        background: #e9ecef;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-content {
        padding: 15px 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }
    .timeline-title {
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 16px;
    }
    .timeline-text {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 14px;
    }
    .empty-state {
        padding: 40px 0;
    }
    .empty-state-icon {
        font-size: 80px;
        width: 150px;
        height: 150px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 20px;
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
        .statistic-card {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush
