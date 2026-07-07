
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

                <div class="row text-center">

                    <div class="col-md-2 col-6 mb-4">
                        <div class="p-3 border rounded milestone-card">
                            <div class="mb-2">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <h6 class="mb-0">Personal Information</h6>
                        </div>
                    </div>

                    <div class="col-md-2 col-6 mb-4">
                        <div class="p-3 border rounded">
                            <div class="mb-2">
                                <i class="fas fa-school fa-2x text-primary"></i>
                            </div>
                            <h6 class="mb-0">O-Level Education</h6>
                        </div>
                    </div>

                    <div class="col-md-2 col-6 mb-4">
                        <div class="p-3 border rounded">
                            <div class="mb-2">
                                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                            </div>
                            <h6 class="mb-0">A-Level Education</h6>
                        </div>
                    </div>

                    <div class="col-md-2 col-6 mb-4">
                        <div class="p-3 border rounded">
                            <div class="mb-2">
                                <i class="fas fa-file-alt fa-2x text-primary"></i>
                            </div>
                            <h6 class="mb-0">Motivation Letter</h6>
                        </div>
                    </div>

                    <div class="col-md-2 col-6 mb-4">
                        <div class="p-3 border rounded">
                            <div class="mb-2">
                                <i class="fas fa-search fa-2x text-warning"></i>
                            </div>
                            <h6 class="mb-0">Review</h6>
                        </div>
                    </div>

                    <div class="col-md-2 col-6 mb-4">
                        <div class="p-3 border rounded">
                            <div class="mb-2">
                                <i class="fas fa-paper-plane fa-2x text-success"></i>
                            </div>
                            <h6 class="mb-0">Submit</h6>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
@endsection

