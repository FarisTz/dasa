@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Application Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Applications</a></div>
            <div class="breadcrumb-item">Application #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</div>
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

        <!-- Application Header -->
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-file-alt text-primary"></i>
                    Application #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}
                </h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $application->status == 'submitted' ? 'primary' :
                        ($application->status == 'pending' ? 'secondary' :
                        ($application->status == 'under_review' ? 'warning' :
                        ($application->status == 'approved_full' ? 'success' :
                        ($application->status == 'approved_partial' ? 'info' : 'danger')))) }} badge-lg">
                        <i class="fas fa-{{ $application->status == 'submitted' ? 'clock' :
                            ($application->status == 'pending' ? 'hourglass-half' :
                            ($application->status == 'under_review' ? 'search' :
                            ($application->status == 'approved_full' ? 'check-circle' :
                            ($application->status == 'approved_partial' ? 'check-circle' : 'times-circle')))) }}"></i>
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                    <a href="{{ route('admin.applications.edit', $application->id) }}" class="btn btn-warning ml-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary ml-2">
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
                                        <th width="35%">Application ID</th>
                                        <td><code>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Applicant Name</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $application->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $application->user->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="30"
                                                     height="30">
                                                <strong>{{ $application->user->name }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>
                                            <a href="mailto:{{ $application->user->email }}">{{ $application->user->email }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td>{{ $application->user->phone_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Scholarship</th>
                                        <td>
                                            <strong>{{ $application->scholarship->title ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $application->scholarship->academic_year ?? '' }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Academic Year</th>
                                        <td>{{ $application->scholarship->academic_year ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Scholarship Deadline</th>
                                        <td>
                                            @if($application->scholarship)
                                                <span class="badge badge-{{ now()->gt($application->scholarship->deadline) ? 'danger' : 'success' }}">
                                                    {{ $application->scholarship->deadline->format('F d, Y') }}
                                                    @if(now()->gt($application->scholarship->deadline))
                                                        <i class="fas fa-exclamation-circle"></i> (Expired)
                                                    @else
                                                        <i class="fas fa-clock"></i> {{ $application->scholarship->deadline->diffForHumans() }}
                                                    @endif
                                                </span>
                                            @else
                                                -
                                            @endif
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
                                        <th width="35%">Status</th>
                                        <td>
                                            <span class="badge badge-{{ $application->status == 'submitted' ? 'primary' :
                                                ($application->status == 'pending' ? 'secondary' :
                                                ($application->status == 'under_review' ? 'warning' :
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
                                                <span class="badge badge-{{ $days > 30 ? 'danger' : ($days > 15 ? 'warning' : 'info') }}">
                                                    {{ $days }} days in review
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            <i class="fas fa-clock text-info"></i>
                                            {{ $application->updated_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $application->updated_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created Date</th>
                                        <td>
                                            <i class="fas fa-plus-circle text-success"></i>
                                            {{ $application->created_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Form Four Index</th>
                                        <td>
                                            @if($application->user->oLevelEducation)
                                                {{ $application->user->oLevelEducation->form_four_index_number }}
                                            @else
                                                <span class="text-muted">Not provided</span>
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

        <!-- Applicant Details Tabs -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user"></i> Applicant Information</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="applicantTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="fas fa-user"></i> Personal Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="olevel-tab" data-toggle="tab" href="#olevel" role="tab" aria-controls="olevel" aria-selected="false">
                            <i class="fas fa-school"></i> O-Level
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="alevel-tab" data-toggle="tab" href="#alevel" role="tab" aria-controls="alevel" aria-selected="false">
                            <i class="fas fa-university"></i> A-Level
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="motivation-tab" data-toggle="tab" href="#motivation" role="tab" aria-controls="motivation" aria-selected="false">
                            <i class="fas fa-file-alt"></i> Motivation
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="applicantTabsContent">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="mt-3">
                            @if($application->user->personalInfo)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">Gender</th>
                                                        <td>{{ ucfirst($application->user->personalInfo->gender) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Birthdate</th>
                                                        <td>{{ $application->user->personalInfo->birthdate ? $application->user->personalInfo->birthdate->format('F d, Y') : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Place of Birth</th>
                                                        <td>{{ $application->user->personalInfo->place_of_birth ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nationality</th>
                                                        <td>{{ $application->user->personalInfo->nationality ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Marital Status</th>
                                                        <td>{{ ucfirst($application->user->personalInfo->marital_status ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Religion</th>
                                                        <td>{{ ucfirst($application->user->personalInfo->religion ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{ $application->user->personalInfo->address ?? '-' }}</td>
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
                                                        <th width="40%">Region</th>
                                                        <td>{{ $application->user->personalInfo->region ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>District</th>
                                                        <td>{{ $application->user->personalInfo->district ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone Number</th>
                                                        <td>{{ $application->user->personalInfo->phone_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Type</th>
                                                        <td>{{ $application->user->personalInfo->id_type ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Number</th>
                                                        <td>{{ $application->user->personalInfo->id_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Disability</th>
                                                        <td>{{ ucfirst($application->user->personalInfo->disability ?? 'None') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Birth Certificate</th>
                                                        <td>
                                                            @if($application->user->personalInfo->birth_certificate_path)
                                                                <a href="{{ asset('storage/' . $application->user->personalInfo->birth_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-file-pdf"></i> View
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Not uploaded</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h6 class="font-weight-bold">Next of Kin Information</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th width="40%">Full Name</th>
                                                            <td>{{ $application->user->personalInfo->kin_full_name ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Relationship</th>
                                                            <td>{{ ucfirst($application->user->personalInfo->kin_relationship ?? '-') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone Number</th>
                                                            <td>{{ $application->user->personalInfo->kin_phone_number ?? '-' }}</td>
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
                                                            <th width="40%">Address</th>
                                                            <td>{{ $application->user->personalInfo->kin_address ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>District</th>
                                                            <td>{{ $application->user->personalInfo->kin_district ?? '-' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>Personal information not provided by the applicant.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- O-Level Tab -->
                    <div class="tab-pane fade" id="olevel" role="tabpanel" aria-labelledby="olevel-tab">
                        <div class="mt-3">
                            @if($application->user->oLevelEducation)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">School Name</th>
                                                        <td>{{ $application->user->oLevelEducation->school_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Form Four Index Number</th>
                                                        <td><strong>{{ $application->user->oLevelEducation->form_four_index_number }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Division</th>
                                                        <td>{{ $application->user->oLevelEducation->division ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Points</th>
                                                        <td>{{ $application->user->oLevelEducation->points ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year of Completion</th>
                                                        <td>{{ $application->user->oLevelEducation->end_of_study_year ?? '-' }}</td>
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
                                                        <th width="40%">Certificate</th>
                                                        <td>
                                                            @if($application->user->oLevelEducation->form_four_certificate_path)
                                                                <a href="{{ asset('storage/' . $application->user->oLevelEducation->form_four_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-file-pdf"></i> View Certificate
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Not uploaded</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>O-Level information not provided by the applicant.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- A-Level Tab -->
                    <div class="tab-pane fade" id="alevel" role="tabpanel" aria-labelledby="alevel-tab">
                        <div class="mt-3">
                            @if($application->user->aLevelEducation)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">School Name</th>
                                                        <td>{{ $application->user->aLevelEducation->school_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Form Six Index Number</th>
                                                        <td><strong>{{ $application->user->aLevelEducation->form_six_index_number }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Division</th>
                                                        <td>{{ $application->user->aLevelEducation->division ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Points</th>
                                                        <td>{{ $application->user->aLevelEducation->points ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year of Completion</th>
                                                        <td>{{ $application->user->aLevelEducation->end_of_study_year ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Preferred University</th>
                                                        <td>{{ $application->user->aLevelEducation->preferred_university ?? '-' }}</td>
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
                                                        <th width="40%">Certificate</th>
                                                        <td>
                                                            @if($application->user->aLevelEducation->form_six_certificate_path)
                                                                <a href="{{ asset('storage/' . $application->user->aLevelEducation->form_six_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-file-pdf"></i> View Certificate
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Not uploaded</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>A-Level information not provided by the applicant.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Motivation Tab -->
                    <div class="tab-pane fade" id="motivation" role="tabpanel" aria-labelledby="motivation-tab">
                        <div class="mt-3">
                            @if($application->user->motivation)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Motivation Letter</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                    {{ $application->user->motivation->motivation_letter }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($application->user->motivation->academic_goals)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Academic Goals</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $application->user->motivation->academic_goals }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($application->user->motivation->community_contribution)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Community Contribution</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $application->user->motivation->community_contribution }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($application->user->motivation->additional_information)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Additional Information</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $application->user->motivation->additional_information }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>Motivation letter not provided by the applicant.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-sticky-note"></i> Admin Notes</h4>
            </div>
            <div class="card-body">
                @if($application->admin_notes)
                    <div class="alert alert-info">
                        <div style="white-space: pre-wrap; line-height: 1.8;">
                            {{ $application->admin_notes }}
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle"></i> No admin notes for this application.
                    </div>
                @endif

                <!-- Add/Edit Notes -->
                <form action="{{ route('admin.applications.update', $application->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Update Admin Notes</label>
                        <textarea class="form-control" name="admin_notes" rows="4" placeholder="Add notes about this application...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Update Status</label>
                        <select class="form-control" name="status">
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ $application->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved_full" {{ $application->status == 'approved_full' ? 'selected' : '' }}>Approved Full</option>
                            <option value="approved_partial" {{ $application->status == 'approved_partial' ? 'selected' : '' }}>Approved Partial</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Notes & Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Application Timeline -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-history"></i> Application Timeline</h4>
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
                                    <span class="text-success">Application has been reviewed</span>
                                    @if($application->updated_at)
                                        <br>
                                        <small class="text-muted">Reviewed: {{ $application->updated_at->diffForHumans() }}</small>
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
                                    <i class="fas fa-check-circle text-success"></i> Congratulations! The applicant has been approved for a full scholarship.
                                @elseif($application->status == 'approved_partial')
                                    <span class="text-info"><strong>Approved (Partial Scholarship)</strong></span>
                                    <br>
                                    <i class="fas fa-check-circle text-info"></i> The applicant has been approved for a partial scholarship.
                                @elseif($application->status == 'rejected')
                                    <span class="text-danger"><strong>Rejected</strong></span>
                                    <br>
                                    <i class="fas fa-times-circle text-danger"></i> The application was not successful.
                                @else
                                    <span class="text-muted">Awaiting decision</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group">
                            @if($application->status != 'approved_full' && $application->status != 'approved_partial' && $application->status != 'rejected')
                                <form method="POST" action="{{ route('admin.applications.update-status') }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $application->id }}">
                                    <input type="hidden" name="status" value="approved_full">
                                    <button type="submit" class="btn btn-success btn-lg px-4 mr-2" onclick="return confirm('Approve this application for full scholarship?')">
                                        <i class="fas fa-check-circle"></i> Approve Full
                                    </button>
                                    <input type="hidden" name="status" value="approved_partial">
                                    <button type="submit" class="btn btn-info btn-lg px-4 mr-2" onclick="return confirm('Approve this application for partial scholarship?')">
                                        <i class="fas fa-check-circle"></i> Approve Partial
                                    </button>
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-lg px-4 mr-2" onclick="return confirm('Reject this application?')">
                                        <i class="fas fa-times-circle"></i> Reject
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.applications.edit', $application->id) }}" class="btn btn-warning btn-lg px-4 mr-2">
                                <i class="fas fa-edit"></i> Edit
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
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    .bg-light {
        background-color: #f8f9fa !important;
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

        // Print button
        $('.btn-print').on('click', function() {
            window.print();
        });
    });
</script>
@endpush
