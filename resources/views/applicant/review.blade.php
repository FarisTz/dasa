@extends('layouts.app')

@section('content')


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
    .custom-radio .custom-control-label {
        cursor: pointer;
        font-weight: 500;
    }
    .card.border-success {
        border-width: 2px;
        box-shadow: 0 0 20px rgba(40, 167, 69, 0.15);
    }
    .application-status-badge {
        font-size: 14px;
        padding: 8px 16px;
    }
</style>
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

        <!-- Check if user already has a submitted application -->
        @php
            $hasSubmittedApplication = $existingApplication && in_array($existingApplication->status, ['submitted', 'under_review', 'approved_full', 'approved_partial', 'rejected']);
            $hasPendingApplication = $existingApplication && $existingApplication->status == 'pending';
            $isApproved = $existingApplication && in_array($existingApplication->status, ['approved_full', 'approved_partial']);
        @endphp

        @if($hasSubmittedApplication)
            @php
                $scholarshipName = $existingApplication->scholarship ? $existingApplication->scholarship->title : 'Direct Aid Scholarship';
                $status = $existingApplication->status;
            @endphp
            <div class="card">
                <div class="card-body">
                    @switch($status)
                        @case('approved_full')
                            <div class="card mb-0 border-success shadow-lg">
                                <div class="card-body bg-success text-white rounded" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                                    <div class="d-flex align-items-start flex-column flex-md-row">
                                        <div class="mr-3 mb-3 mb-md-0">
                                            <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-3 text-white">🎉 Application Status: Approved – Full Scholarship</h4>
                                            <p class="mb-2" style="color: #FFFFFF;">Dear <strong>{{ Auth::user()->name }}</strong>,</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Congratulations! We are pleased to inform you that your application for the <strong>{{ $scholarshipName }}</strong> has been <strong>approved for a Full Scholarship</strong>.</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Your scholarship package covers <strong>Full Tuition Fees, Accommodation, and Meals</strong>.</p>
                                            <p class="mb-0" style="color: #FFFFFF;">This award recognizes your academic potential and dedication. Please continue to monitor your application portal for important updates and the next steps.<br><strong>Best regards,</strong><br><strong>Direct Aid Scholarship Team</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @break

                        @case('approved_partial')
                            <div class="card mb-0 border-info shadow-lg">
                                <div class="card-body bg-info text-white rounded" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                                    <div class="d-flex align-items-start flex-column flex-md-row">
                                        <div class="mr-3 mb-3 mb-md-0">
                                            <div class="rounded-circle bg-white text-info d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                                <i class="fas fa-award"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-3 text-white">🎉 Application Status: Approved – Partial Scholarship</h4>
                                            <p class="mb-2" style="color: #FFFFFF;">Dear <strong>{{ Auth::user()->name }}</strong>,</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Congratulations! We are delighted to inform you that your application for the <strong>{{ $scholarshipName }}</strong> has been <strong>approved for a Partial Scholarship</strong>.</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Your scholarship package covers <strong>Full Tuition Fees only</strong>.</p>
                                            <p class="mb-0" style="color: #FFFFFF;">Please note that accommodation, meals, and other personal expenses are the responsibility of the student. Please continue to monitor your application portal for important updates and the next steps.<br><strong>Best regards,</strong><br><strong>Direct Aid Scholarship Team</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @break

                        @case('under_review')
                            <div class="card mb-0 border-primary shadow-lg">
                                <div class="card-body bg-primary text-white rounded" style="background: linear-gradient(135deg, #007bff 0%, #0069d9 100%);">
                                    <div class="d-flex align-items-start flex-column flex-md-row">
                                        <div class="mr-3 mb-3 mb-md-0">
                                            <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                                <i class="fas fa-search"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-3 text-white">Application Status: Under Review</h4>
                                            <p class="mb-2" style="color: #FFFFFF;">Dear <strong>{{ Auth::user()->name }}</strong>,</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Thank you for submitting your <strong>{{ $scholarshipName }}</strong> application.</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Your application is currently under review by the Scholarship Committee. Each application is carefully evaluated to ensure a fair and transparent selection process.</p>
                                            <p class="mb-0" style="color: #FFFFFF;">No further action is required from you at this time. Please continue to monitor your application portal for updates regarding your application status.<br><strong>Best regards,</strong><br><strong>Direct Aid Scholarship Team</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @break

                        @case('rejected')
                            <div class="card mb-0 border-danger shadow-lg">
                                <div class="card-body bg-danger text-white rounded" style="background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);">
                                    <div class="d-flex align-items-start flex-column flex-md-row">
                                        <div class="mr-3 mb-3 mb-md-0">
                                            <div class="rounded-circle bg-white text-danger d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-3 text-white">Application Status: Not Selected</h4>
                                            <p class="mb-2" style="color: #FFFFFF;">Dear <strong>{{ Auth::user()->name }}</strong>,</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Thank you for applying for the <strong>{{ $scholarshipName }}</strong>.</p>
                                            <p class="mb-2" style="color: #FFFFFF;">After careful review of all applications, we regret to inform you that your application has <strong>not been selected</strong> for this scholarship cycle.</p>
                                            <p class="mb-0" style="color: #FFFFFF;">We sincerely appreciate your interest and the effort you invested in your application. We encourage you to continue pursuing your academic goals and to apply again when future scholarship opportunities become available.<br><strong>Best regards,</strong><br><strong>Direct Aid Scholarship Team</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @break

                        @case('submitted')
                        @case('pending')
                        @default
                            <div class="card mb-0 border-success shadow-lg">
                                <div class="card-body bg-success text-white rounded" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                                    <div class="d-flex align-items-start flex-column flex-md-row">
                                        <div class="mr-3 mb-3 mb-md-0">
                                            <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-weight-bold mb-3 text-white">Application Submitted Successfully</h4>
                                            <p class="mb-2" style="color: #FFFFFF;">Dear <strong>{{ Auth::user()->name }}</strong>,</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Thank you for submitting your <strong>{{ $scholarshipName }}</strong> application.</p>
                                            <p class="mb-2" style="color: #FFFFFF;">Your application has been received and is currently under review. Please be patient while our Scholarship Committee evaluates all applications. You will be notified once the review process is complete.</p>
                                            <p class="mb-0" style="color: #FFFFFF;">Thank you for your interest, and we wish you the very best.<br><strong>Best regards,</strong><br><strong>Direct Aid Scholarship Team</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endswitch
                    <div class="mt-3 text-center">
                        <a href="{{ route('applicant.my-application') }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View My Application
                        </a>
                        @if(in_array($status, ['submitted', 'pending']))
                            <a href="{{ route('applicant.application.edit') }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Application
                            </a>
                        @endif
                        @if($status == 'pending')
                            <a href="{{ route('applicant.application.review') }}" class="btn btn-info">
                                <i class="fas fa-pen"></i> Continue Editing
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Progress Bar -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="progress" style="height: 30px;">
                            @php
                                $progress = 0;
                                if($personalInfo) $progress += 20;
                                if($oLevel) $progress += 20;
                                if($aLevel) $progress += 20;
                                if($motivation) $progress += 20;
                                if($selectedScholarship) $progress += 20;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $progress }}% Complete
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="card">

            <div class="card-body">
                <ul class="nav nav-tabs" id="applicationTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $errors->any() ? '' : 'active' }}" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">
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
                    <li class="nav-item">
                        <a class="nav-link" id="scholarship-tab" data-toggle="tab" href="#scholarship" role="tab" aria-controls="scholarship" aria-selected="false">
                            <i class="fas fa-award"></i> Scholarship
                            @if($selectedScholarship)
                                <span class="badge badge-success"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                            @endif
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="applicationTabsContent">
                    <!-- Tab 1: Personal Information -->
                    <div class="tab-pane fade {{ $errors->any() ? '' : 'show active' }}" id="personal" role="tabpanel" aria-labelledby="personal-tab">
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
                                    @if(!$isApproved)
                                        <a href="{{ route('applicant.personal_information') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Personal Info
                                        </a>
                                    @endif
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
                                    @if(!$isApproved)
                                        <a href="{{ route('applicant.o-level-education') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit O-Level Info
                                        </a>
                                    @endif
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
                                    @if(!$isApproved)
                                        <a href="{{ route('applicant.a-level-education') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit A-Level Info
                                        </a>
                                    @endif
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
                                    @if(!$isApproved)
                                        <a href="{{ route('applicant.motivations.index') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Motivation
                                        </a>
                                    @endif
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

                    <!-- Tab 5: Scholarship Selection -->
                    <div class="tab-pane fade {{ session('scholarship_error') || $errors->has('scholarship_id') ? 'show active' : '' }}" id="scholarship" role="tabpanel" aria-labelledby="scholarship-tab">
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Select a scholarship to apply for.</strong>
                                You can only apply for scholarships that are currently open and accepting applications.
                            </div>

                            @if($hasSubmittedApplication)
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-ban fa-2x"></i>
                                    <h5 class="mt-2">You already have a submitted application</h5>
                                    <p>You cannot select a new scholarship while you have an existing application.</p>
                                    <a href="{{ route('applicant.my-application') }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> View My Application
                                    </a>
                                </div>
                            @elseif($openScholarships && $openScholarships->count() > 0)
                                <form method="POST" action="{{ route('applicant.application.select-scholarship') }}" id="scholarshipForm">
                                    @csrf

                                    <div class="row">
                                        @foreach($openScholarships as $scholarship)
                                            <div class="col-lg-6 col-md-12 mb-3">
                                                <div class="card {{ $selectedScholarship && $selectedScholarship->id == $scholarship->id ? 'border-success shadow' : 'border' }}">
                                                    <div class="card-body">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio"
                                                                   class="custom-control-input"
                                                                   name="scholarship_id"
                                                                   id="scholarship_{{ $scholarship->id }}"
                                                                   value="{{ $scholarship->id }}"
                                                                   {{ $selectedScholarship && $selectedScholarship->id == $scholarship->id ? 'checked' : '' }}
                                                                   required>
                                                            <label class="custom-control-label" for="scholarship_{{ $scholarship->id }}">
                                                                <h5 class="mb-1">{{ $scholarship->title }}</h5>
                                                            </label>
                                                        </div>

                                                        <div class="mt-3 ml-4">
                                                            <p class="text-muted small">{{ Str::limit($scholarship->description, 150) }}</p>

                                                            <div class="row small">
                                                                <div class="col-md-6">
                                                                    <strong>Academic Year:</strong> {{ $scholarship->academic_year }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Deadline:</strong>
                                                                    <span class="badge badge-{{ now()->gt($scholarship->deadline) ? 'danger' : 'warning' }}">
                                                                        {{ $scholarship->deadline->format('M d, Y') }}
                                                                        @if(now()->gt($scholarship->deadline))
                                                                            <i class="fas fa-exclamation-circle"></i> Expired
                                                                        @else
                                                                            <i class="fas fa-clock"></i> {{ $scholarship->deadline->diffForHumans() }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="mt-2">
                                                                <strong>Status:</strong>
                                                                <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($scholarship->status) }}
                                                                </span>
                                                            </div>

                                                            @if($scholarship->eligibility_criteria)
                                                                <div class="mt-2">
                                                                    <a href="#" class="text-primary" data-toggle="collapse" data-target="#criteria_{{ $scholarship->id }}">
                                                                        <i class="fas fa-chevron-down"></i> View Eligibility Criteria
                                                                    </a>
                                                                    <div id="criteria_{{ $scholarship->id }}" class="collapse mt-2">
                                                                        <div class="bg-light p-3 rounded">
                                                                            {{ $scholarship->eligibility_criteria }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @error('scholarship_id')
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-primary btn-lg px-5" id="selectScholarshipBtn">
                                            <i class="fas fa-check-circle"></i> Select Scholarship
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-danger text-center">
                                    <i class="fas fa-times-circle fa-3x"></i>
                                    <h4 class="mt-3">No Open Scholarships Available</h4>
                                    <p class="mb-0">There are currently no open scholarships accepting applications. Please check back later.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($selectedScholarship && !$hasSubmittedApplication)
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle"></i>
                                    <strong>Selected Scholarship:</strong> {{ $selectedScholarship->title }}
                                    <br>
                                    <small>You have selected this scholarship for your application.</small>
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
                            $allCompleted = ($personalInfo && $oLevel && $aLevel && $motivation && $selectedScholarship);
                            $hasOpenScholarships = $openScholarships && $openScholarships->count() > 0;
                        @endphp

                        @if($hasSubmittedApplication)
                            @if(in_array($existingApplication->status, ['approved_full', 'approved_partial']))
                            <div class="alert alert-success text-center" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); border: none; color: white;">
                                <i class="fas fa-trophy fa-2x mb-2" style="color: white;"></i>
                                <h5 style="color: white; font-weight: bold;">
                                    🎉 Congratulations, {{ Auth::user()->name }}!
                                </h5>
                                <p style="color: white;">
                                    Your <strong>{{ $existingApplication->scholarship->title ?? 'Scholarship' }}</strong> application has been approved!
                                </p>
                                <p style="color: white;">
                                    This achievement reflects your hard work and dedication. We encourage you to stay focused, work hard, and make the most of this opportunity to achieve your academic goals and inspire others.
                                </p>
                                <p style="color: white;">
                                    Please continue monitoring your application status for the next steps.
                                    <br>
                                    <strong>Best wishes for your academic success!</strong>
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('applicant.my-application') }}" class="btn btn-light">
                                        <i class="fas fa-eye"></i> View My Application
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-info-circle fa-2x"></i>
                                <h5>Application Already Submitted</h5>
                                <p>You have already submitted an application. You cannot submit another one.</p>
                                <div class="mt-3">
                                    <a href="{{ route('applicant.my-application') }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> View My Application
                                    </a>
                                    @if(!$isApproved && ($existingApplication->status == 'submitted' || $existingApplication->status == 'pending'))
                                        <a href="{{ route('applicant.application.edit') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Application
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @elseif(!$hasOpenScholarships)
                            <div class="alert alert-danger text-center">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                                <h5>No Open Scholarships Available</h5>
                                <p>You cannot submit an application because there are no open scholarships at this time.</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                                </a>
                            </div>
                        @elseif($allCompleted)
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                                <h5>All sections are complete! You are ready to submit your application.</h5>
                                <p><strong>Selected Scholarship:</strong> {{ $selectedScholarship->title }}</p>
                            </div>
                            <form method="POST" action="{{ route('applicant.application.submit') }}" onsubmit="return confirm('Are you sure you want to submit your application for {{ $selectedScholarship->title }}? This action cannot be undone.')">
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
                                    @if(!$selectedScholarship) <span class="badge badge-danger">Scholarship Selection</span> @endif
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


<script>
    $(document).ready(function() {
        // Auto-submit scholarship selection when radio is clicked
        $('input[name="scholarship_id"]').on('change', function() {
            // Show loading state
            $('#selectScholarshipBtn').html('<i class="fas fa-spinner fa-spin"></i> Selecting...');
            $('#selectScholarshipBtn').prop('disabled', true);

            // Submit the form
            $('#scholarshipForm').submit();
        });

        // If scholarship is already selected, highlight it
        @if($selectedScholarship)
            $('#scholarship_{{ $selectedScholarship->id }}').prop('checked', true);
        @endif
    });
</script>
@endsection

@push('styles')

@endpush

@push('scripts')

@endpush
