
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-body">

        <!-- Welcome Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="font-weight-bold mb-2">
                    Welcome to KAFAAT Scholarship Application Portal 👋
                </h3>
                <p class="mb-0 text-muted">
                    Complete all required sections of your scholarship application.
                    You can save your progress at any time and return later before submission.
                    Follow the milestones below to track your application journey.
                </p>
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

