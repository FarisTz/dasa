
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-body">
        @php
            $application = \App\Models\Application::where('user_id', Auth::id())->first();
            $isApproved = $application && in_array($application->status, ['approved_full', 'approved_partial']);
            $scholarshipName = $application && $application->scholarship ? $application->scholarship->title : 'Scholarship';
        @endphp

        <!-- Welcome Card -->
        <div class="card mb-4 {{ $isApproved ? 'border-success shadow-lg' : '' }}">
            <div class="card-body {{ $isApproved ? 'bg-success text-white rounded' : '' }}" style="{{ $isApproved ? 'background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);' : '' }}">
                <div class="d-flex align-items-start {{ $isApproved ? 'flex-column flex-md-row' : '' }}">
                    @if($isApproved)
                        <div class="mr-3 mb-3 mb-md-0">
                            <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 28px;">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                    @endif
                    <div>
                        @if($isApproved)
                            <h3 class="font-weight-bold mb-3 text-white" style="font-size: 28px;">
                                🎉 <strong>Congratulations, {{ Auth::user()->name }}!</strong>
                            </h3>
                            <p class="mb-3 text-white" style="font-size: 16px; line-height: 1.6;">
                                Your <strong>{{ $scholarshipName }}</strong> application has been approved!
                            </p>
                            <p class="mb-3 text-white" style="font-size: 14px; line-height: 1.6;">
                                This achievement reflects your hard work and dedication. We encourage you to stay focused, work hard, and make the most of this opportunity to achieve your academic goals and inspire others.
                            </p>
                            <p class="mb-0 text-white" style="font-size: 14px; line-height: 1.6;">
                                Please continue monitoring your application status for the next steps.
                                <br>
                                <strong>Best wishes for your academic success!</strong>
                            </p>
                        @else
                            <h3 class="font-weight-bold mb-2">
                                Hello {{ Auth::user()->name }},
                                Welcome to KAFAAT Scholarship Application Portal
                            </h3>
                            <p class="mb-0 text-muted">
                                Complete all required sections of your scholarship application.
                                You can save your progress at any time and return later before submission.
                                Follow the milestones below to track your application journey.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
                    $submitCompleted = $application && in_array($application->status, ['submitted', 'under_review', 'approved_full', 'approved_partial', 'rejected']);
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

