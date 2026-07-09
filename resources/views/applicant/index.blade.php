
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-body">
        @php
            $application = \App\Models\Application::where('user_id', Auth::id())->first();
            $isApproved = $application && in_array($application->status, ['approved_full', 'approved_partial']);
            $isSubmitted = $application && in_array($application->status, ['submitted', 'under_review', 'rejected']);
            $scholarshipName = $application && $application->scholarship ? $application->scholarship->title : 'Scholarship';
        @endphp

        @if($application)
            @php
                $scholarshipName = $application->scholarship ? $application->scholarship->title : 'Direct Aid Scholarship';
                $status = $application->status;
            @endphp
            @switch($status)
                @case('approved_full')
                    <div class="card mb-4 border-success shadow-lg">
                        <div class="card-body bg-success text-white rounded" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                            <div class="d-flex align-items-start flex-column flex-md-row">
                                <div class="mr-3 mb-3 mb-md-0">
                                    <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-weight-bold mb-3 text-white" style="font-size: 28px;">
                                        🎉 <strong>Application Status: Approved – Full Scholarship</strong>
                                    </h3>
                                    <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                        Dear <strong>{{ Auth::user()->name }}</strong>,
                                    </p>
                                    <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                        Congratulations! We are pleased to inform you that your application for the <strong>{{ $scholarshipName }}</strong> has been <strong>approved for a Full Scholarship</strong>.
                                    </p>
                                    <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                        Your scholarship package covers <strong>Full Tuition Fees, Accommodation, and Meals</strong>.
                                    </p>
                                    <p class="mb-3 text-white" style="font-size: 14px; line-height: 1.6;">
                                        This award recognizes your academic potential and dedication. We encourage you to remain committed to your studies and make the most of this opportunity.
                                    </p>
                                    <p class="mb-0 text-white" style="font-size: 14px; line-height: 1.6;">
                                        Please continue to monitor your application portal for important updates and the next steps.
                                        <br>
                                        <strong>Best regards,</strong>
                                        <br>
                                        <strong>Direct Aid Scholarship Team</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @break

                @case('approved_partial')
                    <div class="card mb-4 border-info shadow-lg">
                        <div class="card-body bg-info text-white rounded" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                            <div class="d-flex align-items-start flex-column flex-md-row">
                                <div class="mr-3 mb-3 mb-md-0">
                                    <div class="rounded-circle bg-white text-info d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                        <i class="fas fa-award"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-weight-bold mb-3 text-white" style="font-size: 28px;">
                                        🎉 <strong>Application Status: Approved – Partial Scholarship</strong>
                                    </h3>
                                    <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                        Dear <strong>{{ Auth::user()->name }}</strong>,
                                    </p>
                                    <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                        Congratulations! We are delighted to inform you that your application for the <strong>{{ $scholarshipName }}</strong> has been <strong>approved for a Partial Scholarship</strong>.
                                    </p>
                                    <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                        Your scholarship package covers <strong>Full Tuition Fees only</strong>.
                                    </p>
                                    <p class="mb-3 text-white" style="font-size: 14px; line-height: 1.6;">
                                        Please note that accommodation, meals, and other personal expenses are the responsibility of the student. We encourage you to remain focused on your studies and make the most of this opportunity.
                                    </p>
                                    <p class="mb-0 text-white" style="font-size: 14px; line-height: 1.6;">
                                        Please continue to monitor your application portal for important updates and the next steps.
                                        <br>
                                        <strong>Best regards,</strong>
                                        <br>
                                        <strong>Direct Aid Scholarship Team</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @break

                @case('under_review')
                    <div class="alert alert-primary mt-4 mb-4" role="alert" style="border-left: 5px solid #007bff; color: #FFFFFF; background: linear-gradient(135deg, #007bff 0%, #0069d9 100%);">
                        <h5 class="font-weight-bold mb-2"><i class="fas fa-search mr-2"></i>Application Status: Under Review</h5>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Dear <strong>{{ Auth::user()->name }}</strong>,
                        </p>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Thank you for submitting your <strong>{{ $scholarshipName }}</strong> application.
                        </p>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Your application is currently under review by the Scholarship Committee. Each application is carefully evaluated to ensure a fair and transparent selection process.
                        </p>
                        <p class="mb-0" style="color: #FFFFFF;">
                            No further action is required from you at this time. Please continue to monitor your application portal for updates regarding your application status.
                        </p>
                        <p class="mb-0 mt-3" style="color: #FFFFFF;">
                            Thank you for your patience, and we wish you the very best.
                            <br>
                            <strong>Best regards,</strong>
                            <br>
                            <strong>Direct Aid Scholarship Team</strong>
                        </p>
                    </div>
                    @break

                @case('rejected')
                    <div class="alert alert-danger mt-4 mb-4" role="alert" style="border-left: 5px solid #dc3545; color: #FFFFFF; background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);">
                        <h5 class="font-weight-bold mb-2"><i class="fas fa-times-circle mr-2"></i>Application Status: Not Selected</h5>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Dear <strong>{{ Auth::user()->name }}</strong>,
                        </p>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Thank you for applying for the <strong>{{ $scholarshipName }}</strong>.
                        </p>
                        <p class="mb-2" style="color: #FFFFFF;">
                            After careful review of all applications, we regret to inform you that your application has <strong>not been selected</strong> for this scholarship cycle.
                        </p>
                        <p class="mb-0" style="color: #FFFFFF;">
                            We sincerely appreciate your interest and the effort you invested in your application. We encourage you to continue pursuing your academic goals and to apply again when future scholarship opportunities become available.
                        </p>
                        <p class="mb-0 mt-3" style="color: #FFFFFF;">
                            We wish you every success in your studies and future endeavors.
                            <br>
                            <strong>Best regards,</strong>
                            <br>
                            <strong>Direct Aid Scholarship Team</strong>
                        </p>
                    </div>
                    @break

                @case('submitted')
                @case('pending')
                @default
                    <div class="alert alert-success mt-4 mb-4" role="alert" style="border-left: 5px solid #28a745; color: #FFFFFF; background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                        <h5 class="font-weight-bold mb-2"><i class="fas fa-check-circle mr-2"></i>Application Submitted Successfully!</h5>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Dear <strong>{{ Auth::user()->name }}</strong>,
                        </p>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Thank you for submitting your <strong>{{ $scholarshipName }}</strong> application.
                        </p>
                        <p class="mb-2" style="color: #FFFFFF;">
                            Your application has been received and is currently under review. Please be patient while our Scholarship Committee evaluates all applications. You will be notified once the review process is complete.
                        </p>
                        <p class="mb-0" style="color: #FFFFFF;">
                            Thank you for your interest, and we wish you the very best.
                            <br>
                            <strong>Best regards,</strong>
                            <br>
                            <strong>Direct Aid Scholarship Team</strong>
                        </p>
                    </div>
            @endswitch
        @else
            <!-- Welcome Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div>
                            <h3 class="font-weight-bold mb-2">
                                Hello {{ Auth::user()->name }},
                                Welcome to KAFAAT Scholarship Application Portal
                            </h3>
                            <p class="mb-0 text-muted">
                                Complete all required sections of your scholarship application.
                                You can save your progress at any time and return later before submission.
                                Follow the milestones below to track your application journey.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Application Milestones -->
        <div class="card">
            <div class="card-header">
                <h4>Application Progress</h4>
            </div>

            <div class="card-body">
                @php
                    $personalInfo = \App\Models\PersonalInfo::where('user_id', Auth::id())->first();
                    $oLevel = \App\Models\OLevelEducation::where('user_id', Auth::id())->first();
                    $aLevel = \App\Models\ALevelEducation::where('user_id', Auth::id())->first();
                    $motivation = \App\Models\Motivation::where('user_id', Auth::id())->first();
                    $reviewCompleted = $personalInfo && $oLevel && $aLevel && $motivation && $application && $application->scholarship_id;
                    $submitCompleted = $isSubmitted;
                    $stepPosition = 0;
                    if ($personalInfo) $stepPosition = 1;
                    if ($oLevel) $stepPosition = 2;
                    if ($aLevel) $stepPosition = 3;
                    if ($motivation) $stepPosition = 4;
                    if ($reviewCompleted) $stepPosition = 5;
                    if ($submitCompleted) $stepPosition = 6;

                    $milestones = [
                        ['title' => 'Personal Information', 'icon' => 'user-circle', 'iconColor' => 'text-primary'],
                        ['title' => 'O-Level Education', 'icon' => 'school', 'iconColor' => 'text-primary'],
                        ['title' => 'A-Level Education', 'icon' => 'graduation-cap', 'iconColor' => 'text-primary'],
                        ['title' => 'Motivation Letter', 'icon' => 'file-alt', 'iconColor' => 'text-primary'],
                        ['title' => 'Review', 'icon' => 'search', 'iconColor' => 'text-warning'],
                        ['title' => 'Submit', 'icon' => 'paper-plane', 'iconColor' => 'text-success'],
                    ];
                @endphp

                <div class="row text-center">
                    @foreach($milestones as $index => $milestone)
                        @php
                            $stepNumber = $index + 1;
                            $status = $stepNumber <= $stepPosition ? 'completed' : ($stepNumber == $stepPosition + 1 ? 'current' : 'pending');
                            $isCompleted = $status === 'completed';
                            $isCurrent = $status === 'current';
                        @endphp
                        <div class="col-md-2 col-6 mb-4">
                            <div class="p-3 border rounded h-100 {{ $isCompleted ? 'border-success bg-success text-white shadow-sm' : ($isCurrent ? 'border-primary bg-primary text-white shadow-sm' : 'border-light bg-light') }}">
                                <div class="mb-2">
                                    <i class="fas fa-{{ $milestone['icon'] }} fa-2x {{ $isCompleted || $isCurrent ? 'text-white' : $milestone['iconColor'] }}"></i>
                                </div>
                                <h6 class="mb-0">{{ $milestone['title'] }}</h6>
                                <small class="d-block mt-2 {{ $isCompleted || $isCurrent ? 'text-white-50' : 'text-muted' }}">
                                    {{ $isCompleted ? 'Completed' : ($isCurrent ? 'In Progress' : 'Pending') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

