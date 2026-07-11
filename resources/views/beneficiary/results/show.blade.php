@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Result Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('beneficiary.results.index') }}">My Results</a></div>
            <div class="breadcrumb-item">Result Details</div>
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

        <div class="row">
            <!-- Main Result Details -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-alt text-primary"></i> Result Information</h4>
                        <div class="card-header-action">
                            <span class="badge badge-{{ $result->status_color }} badge-lg">
                                <i class="fas fa-{{ $result->status_icon }}"></i>
                                {{ ucfirst($result->status) }}
                            </span>
                            @if($result->is_suspended)
                                <span class="badge badge-danger badge-lg ml-2">
                                    <i class="fas fa-ban"></i> Suspended
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Result ID</th>
                                                <td><code>#{{ str_pad($result->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                            </tr>
                                            <tr>
                                                <th>Academic Year</th>
                                                <td><strong>{{ $result->academic_year }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Student Year</th>
                                                <td>Year {{ $result->student_year }}</td>
                                            </tr>
                                            <tr>
                                                <th>Course Name</th>
                                                <td>{{ $result->course_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>GPA</th>
                                                <td>
                                                    <span class="badge badge-{{ $result->gpa >= 3.5 ? 'success' : ($result->gpa >= 2.5 ? 'warning' : 'danger') }} badge-lg">
                                                        {{ $result->formatted_gpa }}
                                                    </span>
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Division</th>
                                                <td>
                                                    @if($result->division)
                                                        <span class="badge badge-{{ $result->division == 'First Class' ? 'success' : ($result->division == 'Second Class Upper' ? 'info' : 'warning') }}">
                                                            {{ $result->division }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge badge-{{ $result->status_color }}">
                                                        <i class="fas fa-{{ $result->status_icon }}"></i>
                                                        {{ ucfirst($result->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Submitted Date</th>
                                                <td>
                                                    <i class="fas fa-calendar-alt"></i>
                                                    {{ $result->created_at->format('F d, Y H:i A') }}
                                                    <br>
                                                    <small class="text-muted">{{ $result->created_at->diffForHumans() }}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Last Updated</th>
                                                <td>
                                                    <i class="fas fa-clock"></i>
                                                    {{ $result->updated_at->format('F d, Y H:i A') }}
                                                    <br>
                                                    <small class="text-muted">{{ $result->updated_at->diffForHumans() }}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td>{{ $result->remarks ?? 'No remarks' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Feedback -->
                        @if($result->admin_feedback)
                            <div class="mt-3">
                                <label class="font-weight-bold">
                                    <i class="fas fa-comment text-primary"></i> Admin Feedback:
                                </label>
                                <div class="bg-light p-3 rounded border-left border-{{ $result->status == 'approved' ? 'success' : 'danger' }}" style="border-left-width: 4px !important;">
                                    <p class="mb-0">{{ $result->admin_feedback }}</p>
                                    @if($result->reviewer)
                                        <small class="text-muted mt-2 d-block">
                                            Reviewed by: {{ $result->reviewer->name }}
                                            @if($result->reviewed_at)
                                                on {{ $result->reviewed_at->format('F d, Y H:i A') }}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Suspension Information -->
                        @if($result->is_suspended)
                            <div class="mt-3">
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>You are suspended from receiving payments.</strong>
                                    @if($result->suspension_reason)
                                        <p class="mb-0 mt-2">
                                            <strong>Reason:</strong> {{ $result->suspension_reason }}
                                        </p>
                                    @endif
                                    @if($result->suspended_at)
                                        <small class="text-muted d-block mt-1">
                                            Suspended on: {{ $result->suspended_at->format('F d, Y H:i A') }}
                                        </small>
                                    @endif
                                    @if($result->suspension_lifted_at)
                                        <small class="text-success d-block mt-1">
                                            Suspension lifted on: {{ $result->suspension_lifted_at->format('F d, Y H:i A') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Status Timeline -->
                        <div class="mt-4">
                            <label class="font-weight-bold">
                                <i class="fas fa-history text-primary"></i> Status Timeline
                            </label>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5 class="timeline-title">Result Submitted</h5>
                                        <p class="timeline-text">
                                            <i class="fas fa-calendar-alt"></i> {{ $result->created_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $result->created_at->diffForHumans() }}</small>
                                        </p>
                                    </div>
                                </div>

                                @if($result->status != 'pending')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-{{ $result->status == 'approved' ? 'success' : ($result->status == 'rejected' ? 'danger' : 'info') }}">
                                            <i class="fas fa-{{ $result->status == 'approved' ? 'check' : ($result->status == 'rejected' ? 'times' : 'spinner') }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5 class="timeline-title">
                                                Result {{ ucfirst($result->status) }}
                                            </h5>
                                            <p class="timeline-text">
                                                @if($result->reviewed_at)
                                                    <i class="fas fa-calendar-alt"></i> {{ $result->reviewed_at->format('F d, Y H:i A') }}
                                                    <br>
                                                    <small class="text-muted">{{ $result->reviewed_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Pending review</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if($result->is_suspended)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5 class="timeline-title">Suspended</h5>
                                            <p class="timeline-text">
                                                @if($result->suspended_at)
                                                    <i class="fas fa-calendar-alt"></i> {{ $result->suspended_at->format('F d, Y H:i A') }}
                                                    <br>
                                                    <small class="text-muted">{{ $result->suspended_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Suspension date not recorded</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if($result->suspension_lifted_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5 class="timeline-title">Suspension Lifted</h5>
                                            <p class="timeline-text">
                                                <i class="fas fa-calendar-alt"></i> {{ $result->suspension_lifted_at->format('F d, Y H:i A') }}
                                                <br>
                                                <small class="text-muted">{{ $result->suspension_lifted_at->diffForHumans() }}</small>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-12">
                <!-- Result File -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-pdf text-danger"></i> Result File</h4>
                    </div>
                    <div class="card-body text-center">
                        @if($result->result_file_path)
                            <div class="mb-3">
                                <i class="fas fa-file-pdf text-danger" style="font-size: 60px;"></i>
                            </div>
                            <p><strong>{{ $result->result_file_name ?? 'Result File' }}</strong></p>
                            <a href="{{ route('beneficiary.results.download', $result->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        @else
                            <div class="mb-3">
                                <i class="fas fa-file-alt text-muted" style="font-size: 60px;"></i>
                            </div>
                            <p class="text-muted">No file uploaded</p>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-tasks text-primary"></i> Actions</h4>
                    </div>
                    <div class="card-body">
                        @if($result->status == 'pending')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i>
                                <strong>Pending Review</strong>
                                <p class="mb-0 mt-1 small">Your result is being reviewed by the administrator.</p>
                            </div>

                            <form method="POST" action="{{ route('beneficiary.results.destroy', $result->id) }}" onsubmit="return confirm('Are you sure you want to delete this result? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete Result
                                </button>
                            </form>
                        @elseif($result->status == 'approved')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Approved!</strong>
                                <p class="mb-0 mt-1 small">Your result has been approved.</p>
                            </div>
                        @elseif($result->status == 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Rejected</strong>
                                <p class="mb-0 mt-1 small">Your result was rejected. Please review the feedback and submit a new one.</p>
                            </div>
                            <a href="{{ route('beneficiary.results.create') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-redo"></i> Submit New Result
                            </a>
                        @endif

                        <hr>
                        <a href="{{ route('beneficiary.results.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Back to Results
                        </a>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle text-primary"></i> Quick Info</h4>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <span class="text-muted">Student Name</span>
                            <div class="font-weight-bold">{{ $result->student->name }}</div>
                        </div>
                        <div class="info-item mt-2">
                            <span class="text-muted">Student Email</span>
                            <div class="font-weight-bold">{{ $result->student->email }}</div>
                        </div>
                        <div class="info-item mt-2">
                            <span class="text-muted">Academic Year</span>
                            <div class="font-weight-bold">{{ $result->academic_year }}</div>
                        </div>
                        <div class="info-item mt-2">
                            <span class="text-muted">Student Year</span>
                            <div class="font-weight-bold">Year {{ $result->student_year }}</div>
                        </div>
                        <div class="info-item mt-2">
                            <span class="text-muted">GPA</span>
                            <div class="font-weight-bold text-{{ $result->gpa >= 3.5 ? 'success' : ($result->gpa >= 2.5 ? 'warning' : 'danger') }}">
                                {{ $result->formatted_gpa }}
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
    .timeline-marker.bg-primary {
        background: #4e73df;
    }
    .timeline-marker.bg-success {
        background: #28a745;
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
        border-left: 3px solid #4e73df;
    }
    .timeline-title {
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 15px;
    }
    .timeline-text {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 13px;
    }
    .info-item {
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .info-item:last-child {
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
        .timeline-item::before {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
