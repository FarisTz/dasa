@extends('layouts.app')

@section('content')
<section class="section">
<div class="section-header">
        <h1>O-Level Education</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">O-Level Education</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{ isset($oLevelEducation) ? 'Edit' : 'Add' }} O-Level Education Information</h4>
            </div>
            <div class="card-body">
                <!-- Display Success/Error Messages -->
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

                <form id="oLevelEducationForm"
                      method="POST"
                      action="{{ route('applicant.o-level-education.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- School Name -->
                            <div class="form-group">
                                <label>School Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('school_name') is-invalid @enderror"
                                       name="school_name"
                                       placeholder="Enter school name"
                                       value="{{ old('school_name', $oLevelEducation->school_name ?? '') }}"
                                       required>
                                @error('school_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Form Four Index Number -->
                            <div class="form-group">
                                <label>Form Four Index Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('form_four_index_number') is-invalid @enderror"
                                           name="form_four_index_number"
                                           placeholder="e.g., S1234/0001/2020"
                                           value="{{ old('form_four_index_number', $oLevelEducation->form_four_index_number ?? '') }}"
                                           required>
                                </div>
                                @error('form_four_index_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Format: Region Code/Candidate Number/Year (e.g., S1234/0001/2020)</small>
                            </div>

                            <!-- End of Study Year -->
                            <div class="form-group">
                                <label>End of Study Year <span class="text-danger">*</span></label>
                                <select class="form-control @error('end_of_study_year') is-invalid @enderror" name="end_of_study_year" required>
                                    <option value="">Select Year</option>
                                    @for($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}" {{ old('end_of_study_year', $oLevelEducation->end_of_study_year ?? '') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('end_of_study_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Division -->
                            <div class="form-group">
                                <label>Division <span class="text-danger">*</span></label>
                                <select class="form-control @error('division') is-invalid @enderror" name="division" required>
                                    <option value="">Select Division</option>
                                    <option value="I" {{ old('division', $oLevelEducation->division ?? '') == 'I' ? 'selected' : '' }}>Division I (Excellent)</option>
                                    <option value="II" {{ old('division', $oLevelEducation->division ?? '') == 'II' ? 'selected' : '' }}>Division II (Very Good)</option>
                                    <option value="III" {{ old('division', $oLevelEducation->division ?? '') == 'III' ? 'selected' : '' }}>Division III (Good)</option>
                                    <option value="IV" {{ old('division', $oLevelEducation->division ?? '') == 'IV' ? 'selected' : '' }}>Division IV (Satisfactory)</option>
                                    <option value="0" {{ old('division', $oLevelEducation->division ?? '') == '0' ? 'selected' : '' }}>Division 0 (Failed)</option>
                                </select>
                                @error('division')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Points -->
                            <div class="form-group">
                                <label>Total Points</label>
                                <input type="number"
                                       class="form-control @error('points') is-invalid @enderror"
                                       name="points"
                                       placeholder="Enter total points (e.g., 7-33)"
                                       min="7"
                                       max="33"
                                       value="{{ old('points', $oLevelEducation->points ?? '') }}">
                                @error('points')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Range: 7 (best) to 33 (worst)</small>
                            </div>

                            <!-- Form Four Certificate -->
                            <div class="form-group">
                                <label>Form Four Certificate</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                    </div>
                                    <input type="file"
                                           class="form-control @error('form_four_certificate_path') is-invalid @enderror"
                                           name="form_four_certificate_path"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                @if(isset($oLevelEducation) && $oLevelEducation->form_four_certificate_path)
                                    <div class="mt-2">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Current Certificate:</strong> {{ basename($oLevelEducation->form_four_certificate_path) }}
                                            <br>
                                            <a href="{{ asset('storage/' . $oLevelEducation->form_four_certificate_path) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-eye"></i> View Certificate
                                            </a>
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle"></i> Upload a new file to replace the current one
                                            </small>
                                        </div>
                                    </div>
                                @endif

                                @error('form_four_certificate_path')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block">Upload certificate (PDF, JPG, JPEG, PNG) - Max 2MB</small>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <a href="{{ route('applicant.personal_information') }}" class="btn btn-secondary btn-lg px-4" >
                                    <i class="fas fa-arrow-left"></i> Back to Personal Information
                                </a>
                                @if(!$isApproved)
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save"></i>
                                        {{ isset($oLevelEducation) ? 'Update' : 'Save' }} O-Level Education
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-secondary btn-lg px-5" disabled>
                                        <i class="fas fa-save"></i>
                                        Editing Disabled
                                    </button>
                                @endif

                                <a href="{{ route('applicant.a-level-education') }}" class="btn btn-secondary btn-lg px-5" >
                                    <i class="fas fa-arrow-right"></i> Proceed to A-Level
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
