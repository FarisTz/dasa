@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>User Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></div>
            <div class="breadcrumb-item">User Details</div>
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

        <!-- User Header -->
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-user text-primary"></i>
                    {{ $user->name }}
                </h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }} badge-lg">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                    <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'coordinator' ? 'warning' : ($user->role == 'beneficiary' ? 'success' : 'info')) }} badge-lg ml-2">
                        <i class="fas fa-user-tag"></i>
                        {{ ucfirst($user->role ?? 'applicant') }}
                    </span>
                    @if($user->id == auth()->id())
                        <span class="badge badge-warning badge-lg ml-2">
                            <i class="fas fa-user-check"></i> You
                        </span>
                    @endif
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning ml-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-12 text-center">
                        <!-- Profile Photo -->
                        <div class="mb-4">
                            <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                 alt="{{ $user->name }}"
                                 class="rounded-circle"
                                 width="150"
                                 height="150"
                                 style="object-fit: cover; border: 4px solid #f8f9fa; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        </div>
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                        @if($user->phone_number)
                            <p class="text-muted"><i class="fas fa-phone"></i> {{ $user->phone_number }}</p>
                        @endif
                        <div class="mt-2">
                            @if($user->email_verified_at)
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Email Verified
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i> Email Not Verified
                                </span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                Joined: {{ $user->created_at->format('F d, Y') }}
                                <br>
                                {{ $user->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">User ID</th>
                                        <td><code>#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Full Name</th>
                                        <td><strong>{{ $user->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Email Address</th>
                                        <td>
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                            @if($user->email_verified_at)
                                                <span class="badge badge-success ml-2">
                                                    <i class="fas fa-check"></i> Verified
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td>{{ $user->phone_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td>
                                            <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'coordinator' ? 'warning' : ($user->role == 'beneficiary' ? 'success' : 'info')) }} badge-lg">
                                                <i class="fas fa-user-tag"></i>
                                                {{ ucfirst($user->role ?? 'applicant') }}
                                            </span>
                                            <span class="text-muted ml-2">
                                                @if($user->role == 'admin')
                                                    - Full system access
                                                @elseif($user->role == 'coordinator')
                                                    - Can manage applications
                                                @elseif($user->role == 'beneficiary')
                                                    - Scholarship recipient
                                                @else
                                                    - Can apply for scholarships
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Account Status</th>
                                        <td>
                                            <span class="badge badge-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }} badge-lg">
                                                <i class="fas fa-circle"></i>
                                                {{ ucfirst($user->status ?? 'active') }}
                                            </span>
                                            @if($user->status == 'suspended')
                                                <span class="text-danger ml-2">(Account suspended)</span>
                                            @elseif($user->status == 'inactive')
                                                <span class="text-warning ml-2">(Account inactive)</span>
                                            @else
                                                <span class="text-success ml-2">(Account active)</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Application Status</th>
                                        <td>
                                            @if($user->application_status)
                                                <span class="badge badge-{{ $user->application_status == 'submitted' ? 'primary' :
                                                    ($user->application_status == 'under_review' ? 'warning' :
                                                    ($user->application_status == 'approved' ? 'success' :
                                                    ($user->application_status == 'rejected' ? 'danger' : 'secondary'))) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $user->application_status)) }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Not Applied</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Login</th>
                                        <td>
                                            @if($user->last_login_at)
                                                <i class="fas fa-clock text-info"></i>
                                                {{ $user->last_login_at->format('F d, Y H:i A') }}
                                                <br>
                                                <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Never logged in</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            <i class="fas fa-clock text-info"></i>
                                            {{ $user->updated_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Applications</h4>
                        </div>
                        <div class="card-body">
                            {{ $user->applications()->count() }}
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
                            {{ $user->applications()->whereIn('status', ['approved_full', 'approved_partial'])->count() }}
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
                            {{ $user->applications()->where('status', 'pending')->count() }}
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
                            <h4>Rejected</h4>
                        </div>
                        <div class="card-body">
                            {{ $user->applications()->where('status', 'rejected')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Application Data Tabs -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-folder-open text-primary"></i> User Application Data</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="userTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="fas fa-user"></i> Personal Info
                            @if($user->personalInfo)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="olevel-tab" data-toggle="tab" href="#olevel" role="tab" aria-controls="olevel" aria-selected="false">
                            <i class="fas fa-school"></i> O-Level
                            @if($user->oLevelEducation)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="alevel-tab" data-toggle="tab" href="#alevel" role="tab" aria-controls="alevel" aria-selected="false">
                            <i class="fas fa-university"></i> A-Level
                            @if($user->aLevelEducation)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="motivation-tab" data-toggle="tab" href="#motivation" role="tab" aria-controls="motivation" aria-selected="false">
                            <i class="fas fa-file-alt"></i> Motivation
                            @if($user->motivation)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="applications-tab" data-toggle="tab" href="#applications" role="tab" aria-controls="applications" aria-selected="false">
                            <i class="fas fa-file-alt"></i> Applications
                            @php
                                $appCount = $user->applications()->count();
                            @endphp
                            @if($appCount > 0)
                                <span class="badge badge-primary">{{ $appCount }}</span>
                            @else
                                <span class="badge badge-secondary">0</span>
                            @endif
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="userTabsContent">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="mt-3">
                            @if($user->personalInfo)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">Gender</th>
                                                        <td>{{ ucfirst($user->personalInfo->gender) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Birthdate</th>
                                                        <td>{{ $user->personalInfo->birthdate ? $user->personalInfo->birthdate->format('F d, Y') : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Place of Birth</th>
                                                        <td>{{ $user->personalInfo->place_of_birth ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nationality</th>
                                                        <td>{{ $user->personalInfo->nationality ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Marital Status</th>
                                                        <td>{{ ucfirst($user->personalInfo->marital_status ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Religion</th>
                                                        <td>{{ ucfirst($user->personalInfo->religion ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{ $user->personalInfo->address ?? '-' }}</td>
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
                                                        <td>{{ $user->personalInfo->region ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>District</th>
                                                        <td>{{ $user->personalInfo->district ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone Number</th>
                                                        <td>{{ $user->personalInfo->phone_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Type</th>
                                                        <td>{{ $user->personalInfo->id_type ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Number</th>
                                                        <td>{{ $user->personalInfo->id_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Disability</th>
                                                        <td>{{ ucfirst($user->personalInfo->disability ?? 'None') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Birth Certificate</th>
                                                        <td>
                                                            @if($user->personalInfo->birth_certificate_path)
                                                                <a href="{{ asset('storage/' . $user->personalInfo->birth_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
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
                                                            <td>{{ $user->personalInfo->kin_full_name ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Relationship</th>
                                                            <td>{{ ucfirst($user->personalInfo->kin_relationship ?? '-') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone Number</th>
                                                            <td>{{ $user->personalInfo->kin_phone_number ?? '-' }}</td>
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
                                                            <td>{{ $user->personalInfo->kin_address ?? '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>District</th>
                                                            <td>{{ $user->personalInfo->kin_district ?? '-' }}</td>
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
                                    <p>Personal information not provided by the user.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- O-Level Tab -->
                    <div class="tab-pane fade" id="olevel" role="tabpanel" aria-labelledby="olevel-tab">
                        <div class="mt-3">
                            @if($user->oLevelEducation)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">School Name</th>
                                                        <td>{{ $user->oLevelEducation->school_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Form Four Index Number</th>
                                                        <td><strong>{{ $user->oLevelEducation->form_four_index_number }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Division</th>
                                                        <td>{{ $user->oLevelEducation->division ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Points</th>
                                                        <td>{{ $user->oLevelEducation->points ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year of Completion</th>
                                                        <td>{{ $user->oLevelEducation->end_of_study_year ?? '-' }}</td>
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
                                                            @if($user->oLevelEducation->form_four_certificate_path)
                                                                <a href="{{ asset('storage/' . $user->oLevelEducation->form_four_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
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
                                    <p>O-Level information not provided by the user.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- A-Level Tab -->
                    <div class="tab-pane fade" id="alevel" role="tabpanel" aria-labelledby="alevel-tab">
                        <div class="mt-3">
                            @if($user->aLevelEducation)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">School Name</th>
                                                        <td>{{ $user->aLevelEducation->school_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Form Six Index Number</th>
                                                        <td><strong>{{ $user->aLevelEducation->form_six_index_number }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Division</th>
                                                        <td>{{ $user->aLevelEducation->division ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Points</th>
                                                        <td>{{ $user->aLevelEducation->points ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year of Completion</th>
                                                        <td>{{ $user->aLevelEducation->end_of_study_year ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Preferred University</th>
                                                        <td>{{ $user->aLevelEducation->preferred_university ?? '-' }}</td>
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
                                                            @if($user->aLevelEducation->form_six_certificate_path)
                                                                <a href="{{ asset('storage/' . $user->aLevelEducation->form_six_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
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
                                    <p>A-Level information not provided by the user.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Motivation Tab -->
                    <div class="tab-pane fade" id="motivation" role="tabpanel" aria-labelledby="motivation-tab">
                        <div class="mt-3">
                            @if($user->motivation)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Motivation Letter</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                    {{ $user->motivation->motivation_letter }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($user->motivation->academic_goals)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Academic Goals</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $user->motivation->academic_goals }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($user->motivation->community_contribution)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Community Contribution</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $user->motivation->community_contribution }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($user->motivation->additional_information)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Additional Information</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $user->motivation->additional_information }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>Motivation letter not provided by the user.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Applications Tab -->
                    <div class="tab-pane fade" id="applications" role="tabpanel" aria-labelledby="applications-tab">
                        <div class="mt-3">
                            @php
                                $userApplications = $user->applications()->with(['scholarship'])->get();
                            @endphp

                            @if($userApplications->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Scholarship</th>
                                                <th>Status</th>
                                                <th>Submitted Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userApplications as $application)
                                                <tr>
                                                    <td>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                    <td>
                                                        @if($application->scholarship)
                                                            {{ $application->scholarship->title }}
                                                        @else
                                                            <span class="text-muted">Scholarship deleted</span>
                                                        @endif
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
                                    <p class="text-muted">No applications submitted by this user.</p>
                                </div>
                            @endif
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
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-lg px-4 mr-2">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                            @if($user->id != auth()->id())
                                <button type="button" class="btn btn-danger btn-lg px-4 mr-2" data-toggle="modal" data-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            @endif
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

<!-- Delete Modal -->
@if($user->id != auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h5>Are you sure you want to delete this user?</h5>
                    <p><strong>{{ $user->name }}</strong></p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="text-danger"><small>This action cannot be undone. All associated data will be permanently removed.</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
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
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
    }
    .nav-tabs .nav-link .badge {
        margin-left: 8px;
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
