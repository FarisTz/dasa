@extends('layouts.app')

@section('content')
<section class="section">
<div class="section-header">
        <h1>Motivation Letter</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Motivation Letter</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{ isset($motivation) ? 'Edit' : 'Add' }} Motivation Letter</h4>
            </div>
            <div class="card-body">
                <!-- Display Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @php
                    $existingApplication = \App\Models\Application::where('user_id', Auth::id())->first();
                    $isApproved = $existingApplication && in_array($existingApplication->status, ['approved_full', 'approved_partial']);
                @endphp

                @if($isApproved)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Your application has been approved and editing is disabled.
                    </div>
                @endif

                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>There is error in form  Please fix it</strong>
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('applicant.motivations.store') }}" enctype="multipart/form-data">
                    @csrf


                    <div class="row">
                        <!-- Motivation Letter -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Motivation Letter <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('motivation_letter') is-invalid @enderror"
                                    name="motivation_letter"
                                    rows="8"
                                    placeholder="Write your motivation letter here...">{{ old('motivation_letter', $motivation->motivation_letter ?? '') }}</textarea>
                                @error('motivation_letter')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Explain why you are interested and why you are a good candidate.</small>
                            </div>
                        </div>

                        <!-- Academic Goals -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Academic Goals</label>
                                <textarea class="form-control @error('academic_goals') is-invalid @enderror"
                                    name="academic_goals"
                                    rows="4"
                                    placeholder="What are your academic goals?">{{ old('academic_goals', $motivation->academic_goals ?? '') }}</textarea>
                                @error('academic_goals')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Describe your short-term and long-term academic goals.</small>
                            </div>
                        </div>

                        <!-- Community Contribution -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Community Contribution</label>
                                <textarea class="form-control @error('community_contribution') is-invalid @enderror"
                                    name="community_contribution"
                                    rows="4"
                                    placeholder="How will you contribute to your community after graduation?">{{ old('community_contribution', $motivation->community_contribution ?? '') }}</textarea>
                                @error('community_contribution')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Share your community involvement and contributions.</small>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Additional Information</label>
                                <textarea class="form-control @error('additional_information') is-invalid @enderror"
                                    name="additional_information"
                                    rows="3"
                                    placeholder="Any additional information you would like to share?">{{ old('additional_information', $motivation->additional_information ?? '') }}</textarea>
                                @error('additional_information')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Any other relevant information that supports your application.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">

                                <a href="{{ route('applicant.o-level-education') }}" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-arrow-left"></i>Go Back to O-Level Education
                                </a>
                                @if(!$isApproved)
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save"></i> {{ isset($motivation) ? 'Update' : 'Save' }} Motivation
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-secondary btn-lg px-5" disabled>
                                        <i class="fas fa-save"></i> Editing Disabled
                                    </button>
                                @endif
                                <a href="{{ route('applicant.application.review') }}" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-arrow-right"></i>Proceed to Review
                                </a>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
