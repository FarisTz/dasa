@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Scholarship Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.scholarships.index') }}">Scholarships</a></div>
            <div class="breadcrumb-item">Scholarship Details</div>
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

        <!-- Scholarship Header -->
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-award text-primary"></i>
                    {{ $scholarship->title }}
                </h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : ($scholarship->status == 'draft' ? 'warning' : 'danger') }} badge-lg">
                        <i class="fas fa-{{ $scholarship->status == 'open' ? 'door-open' : ($scholarship->status == 'draft' ? 'edit' : 'door-closed') }}"></i>
                        {{ ucfirst($scholarship->status) }}
                    </span>
                    @if($scholarship->isDeadlinePassed())
                        <span class="badge badge-danger badge-lg ml-2">
                            <i class="fas fa-exclamation-circle"></i> Deadline Passed
                        </span>
                    @endif

                    <a href="{{ route('coordinator.scholarships.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="35%">Scholarship ID</th>
                                        <td><code>#{{ str_pad($scholarship->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td><strong>{{ $scholarship->title }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Academic Year</th>
                                        <td>
                                            <span class="badge badge-info">{{ $scholarship->academic_year }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Deadline</th>
                                        <td>
                                            <span class="badge badge-{{ now()->gt($scholarship->deadline) ? 'danger' : 'success' }}">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $scholarship->deadline ? $scholarship->deadline->format('F d, Y') : 'Not set' }}
                                                @if($scholarship->deadline)
                                                    <br>
                                                    <small>
                                                        @if(now()->gt($scholarship->deadline))
                                                            <span class="text-danger">(Expired {{ $scholarship->deadline->diffForHumans() }})</span>
                                                        @else
                                                            <span class="text-success">{{ $scholarship->deadline->diffForHumans() }} remaining</span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : ($scholarship->status == 'draft' ? 'warning' : 'danger') }} badge-lg">
                                                <i class="fas fa-{{ $scholarship->status == 'open' ? 'door-open' : ($scholarship->status == 'draft' ? 'edit' : 'door-closed') }}"></i>
                                                {{ ucfirst($scholarship->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $scholarship->creator->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $scholarship->creator->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="30"
                                                     height="30">
                                                <strong>{{ $scholarship->creator->name ?? 'Unknown' }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="35%">Created Date</th>
                                        <td>
                                            <i class="fas fa-plus-circle text-success"></i>
                                            {{ $scholarship->created_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $scholarship->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            <i class="fas fa-clock text-info"></i>
                                            {{ $scholarship->updated_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $scholarship->updated_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Applications</th>
                                        <td>
                                            <span class="badge badge-primary badge-lg">
                                                <i class="fas fa-file-alt"></i> {{ $scholarship->applications()->count() }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Days Until Deadline</th>
                                        <td>
                                            @if($scholarship->deadline)
                                                @php
                                                    $days = now()->diffInDays($scholarship->deadline, false);
                                                @endphp
                                                @if($days > 0)
                                                    <span class="badge badge-{{ $days > 30 ? 'success' : ($days > 15 ? 'warning' : 'danger') }}">
                                                        <i class="fas fa-clock"></i> {{ $days }} days remaining
                                                    </span>
                                                @elseif($days == 0)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Today is the deadline!
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle"></i> {{ abs($days) }} days overdue
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-muted">No deadline set</span>
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

        <!-- Description & Eligibility -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-align-left text-primary"></i> Description</h4>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8; min-height: 150px;">
                            {{ $scholarship->description }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-check-circle text-success"></i> Eligibility Criteria</h4>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8; min-height: 150px;">
                            {{ $scholarship->eligibility_criteria }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

       

        <!-- Recent Applications -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list text-primary"></i> Recent Applications</h4>
                <div class="card-header-action">
                    <a href="{{ route('coordinator.applications.index', ['scholarship' => $scholarship->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right"></i> View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                @php
                    $recentApplications = $scholarship->applications()
                        ->with(['user'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @if($recentApplications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Applicant</th>
                                    <th>Status</th>
                                    <th>Submitted Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentApplications as $application)
                                    <tr>
                                        <td>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $application->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $application->user->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="30"
                                                     height="30">
                                                <div>
                                                    <div>{{ $application->user->name }}</div>
                                                    <small class="text-muted">{{ $application->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
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
                                            @if($application->submitted_at)
                                                {{ $application->submitted_at->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $application->submitted_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Not submitted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-info">
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
                        <p class="text-muted">No applications submitted yet for this scholarship.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group">


                            <a href="{{ route('coordinator.applications.index', ['scholarship' => $scholarship->id]) }}" class="btn btn-primary btn-lg px-4 mr-2">
                                <i class="fas fa-file-alt"></i> View Applications
                            </a>
                            <button type="button" class="btn btn-secondary btn-lg px-4" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
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
    .card-statistic-1 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        padding: 15px;
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
    }
    .card-statistic-1 .card-body {
        font-size: 20px;
        font-weight: 700;
        padding: 5px 0;
        color: #2d2d2d;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
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
        .badge {
            border: 1px solid #ddd !important;
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
