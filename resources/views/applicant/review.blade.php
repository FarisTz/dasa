@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Review Your Application</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Review Application</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Status Alert -->
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

        <!-- Progress Bar -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                Application Complete - Ready for Review
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="card">
            <div class="card-header">
                <h4>Application Review</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="applicationTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="fas fa-user"></i> Personal Info
                            @if($personalInfo)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="olevel-tab" data-toggle="tab" href="#olevel" role="tab" aria-controls="olevel" aria-selected="false">
                            <i class="fas fa-school"></i> O-Level
                            @if($oLevel)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="alevel-tab" data-toggle="tab" href="#alevel" role="tab" aria-controls="alevel" aria-selected="false">
                            <i class="fas fa-university"></i> A-Level
                            @if($aLevel)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="motivation-tab" data-toggle="tab" href="#motivation" role="tab" aria-controls="motivation" aria-selected="false">
                            <i class="fas fa-file-alt"></i> Motivation
                            @if($motivation)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="applicationTabsContent">
                    <!-- Tab 1: Personal Information -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="mt-3">
                            @if($personalInfo)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">Gender</th>
                                                        <td>{{ ucfirst($personalInfo->gender) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Birthdate</th>
                                                        <td>{{ $personalInfo->birthdate ? $personalInfo->birthdate->format('F d, Y') : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Place of Birth</th>
                                                        <td>{{ $personalInfo->place_of_birth ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nationality</th>
                                                        <td>{{ $personalInfo->nationality ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Marital Status</th>
                                                        <td>{{ ucfirst($personalInfo->marital_status ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Religion</th>
                                                        <td>{{ ucfirst($personalInfo->religion ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{ $personalInfo->address ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Region</th>
                                                        <td>{{ $personalInfo->region ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>District</th>
                                                        <td>{{ $personalInfo->district ?? '-' }}</td>
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
                                                        <th width="40%">Phone Number</th>
                                                        <td>{{ $personalInfo->phone_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Type</th>
                                                        <td>{{ $personalInfo->id_type ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Number</th>
                                                        <td>{{ $personalInfo->id_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Disability</th>
                                                        <td>{{ ucfirst($personalInfo->disability ?? 'None') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Birth Certificate</th>
                                                        <td>
                                                            @if($personalInfo->birth_certificate_path)
                                                                <a href="{{ asset('storage/' . $personalInfo->birth_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-file-pdf"></i> View
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Not uploaded</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Next of Kin</th>
                                                        <td>{{ $personalInfo->kin_full_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kin Relationship</th>
                                                        <td>{{ ucfirst($personalInfo->kin_relationship ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kin Phone</th>
                                                        <td>{{ $personalInfo->kin_phone_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kin Address</th>
                                                        <td>{{ $personalInfo->kin_address ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kin District</th>
                                                        <td>{{ $personalInfo->kin_district ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('applicant.personal_information') }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Personal Info
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>Personal information not yet submitted. Please complete your personal information first.</p>
                                    <a href="{{ route('applicant.personal_information') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Personal Information
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 2: O-Level Education -->
                    <div class="tab-pane fade" id="olevel" role="tabpanel" aria-labelledby="olevel-tab">
                        <div class="mt-3">
                            @if($oLevel)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">School Name</th>
                                                        <td>{{ $oLevel->school_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Form Four Index Number</th>
                                                        <td>{{ $oLevel->form_four_index_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Division</th>
                                                        <td>{{ $oLevel->division ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Points</th>
                                                        <td>{{ $oLevel->points ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year of Completion</th>
                                                        <td>{{ $oLevel->end_of_study_year ?? '-' }}</td>
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
                                                            @if($oLevel->form_four_certificate_path)
                                                                <a href="{{ asset('storage/' . $oLevel->form_four_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
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
                                <div class="text-center">
                                    <a href="{{ route('applicant.o-level.edit') }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit O-Level Info
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>O-Level education not yet submitted. Please complete your O-Level information.</p>
                                    <a href="{{ route('applicant.o-level-education') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add O-Level Information
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 3: A-Level Education -->
                    <div class="tab-pane fade" id="alevel" role="tabpanel" aria-labelledby="alevel-tab">
                        <div class="mt-3">
                            @if($aLevel)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">School Name</th>
                                                        <td>{{ $aLevel->school_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Form Six Index Number</th>
                                                        <td>{{ $aLevel->form_six_index_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Division</th>
                                                        <td>{{ $aLevel->division ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Points</th>
                                                        <td>{{ $aLevel->points ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year of Completion</th>
                                                        <td>{{ $aLevel->end_of_study_year ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Preferred University</th>
                                                        <td>{{ $aLevel->preferred_university ?? '-' }}</td>
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
                                                            @if($aLevel->form_six_certificate_path)
                                                                <a href="{{ asset('storage/' . $aLevel->form_six_certificate_path) }}" target="_blank" class="btn btn-sm btn-primary">
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
                                <div class="text-center">
                                    <a href="{{ route('applicant.a-level-education') }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit A-Level Info
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>A-Level education not yet submitted. Please complete your A-Level information.</p>
                                    <a href="{{ route('applicant.a-level-education') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add A-Level Information
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 4: Motivation -->
                    <div class="tab-pane fade" id="motivation" role="tabpanel" aria-labelledby="motivation-tab">
                        <div class="mt-3">
                            @if($motivation)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Motivation Letter</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                    {{ $motivation->motivation_letter }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($motivation->academic_goals)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Academic Goals</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $motivation->academic_goals }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($motivation->community_contribution)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Community Contribution</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $motivation->community_contribution }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($motivation->additional_information)
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Additional Information</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="bg-light p-4 rounded" style="white-space: pre-wrap; line-height: 1.8;">
                                                        {{ $motivation->additional_information }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('applicant.motivations.index') }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Motivation
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <p>Motivation not yet submitted. Please complete your motivation letter.</p>
                                    <a href="{{ route('applicant.motivations.index') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Motivation
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Application -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Review your application carefully before submitting.</strong>
                            Once submitted, you cannot make any changes to your application.
                        </div>

                        <!-- Status Check -->
                        @php
                            $allCompleted = ($personalInfo && $oLevel && $aLevel && $motivation);
                        @endphp

                        @if($allCompleted)
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                                <h5>All sections are complete! You are ready to submit your application.</h5>
                            </div>
                            <form method="POST" action="{{ route('applicant.application.submit') }}" onsubmit="return confirm('Are you sure you want to submit your application? This action cannot be undone.')">
                                @csrf
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-paper-plane"></i> Submit Application
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-danger text-center">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                                <h5>Please complete all sections before submitting.</h5>
                                <p class="mb-0">
                                    Missing:
                                    @if(!$personalInfo) <span class="badge badge-danger">Personal Info</span> @endif
                                    @if(!$oLevel) <span class="badge badge-danger">O-Level</span> @endif
                                    @if(!$aLevel) <span class="badge badge-danger">A-Level</span> @endif
                                    @if(!$motivation) <span class="badge badge-danger">Motivation</span> @endif
                                </p>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary btn-lg px-5" disabled>
                                    <i class="fas fa-paper-plane"></i> Complete All Sections to Submit
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
    }
    .nav-tabs .nav-link .badge {
        margin-left: 8px;
        font-size: 12px;
        padding: 3px 8px;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    .table td {
        vertical-align: middle;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .progress-bar {
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endpush
