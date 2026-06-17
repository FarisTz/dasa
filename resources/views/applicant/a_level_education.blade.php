@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{ isset($aLevelEducation) ? 'Edit' : 'Add' }} A-Level Education Information</h4>
                @if(isset($aLevelEducation))
                    <div class="card-header-action">
                        
                    </div>
                @endif
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

                <form id="aLevelEducationForm"
                      method="POST"
                      action="{{ route('applicant.a-level-education.store') }}"
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
                                       value="{{ old('school_name', $aLevelEducation->school_name ?? '') }}"
                                       required>
                                @error('school_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Form Six Index Number -->
                            <div class="form-group">
                                <label>Form Six Index Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('form_six_index_number') is-invalid @enderror"
                                           name="form_six_index_number"
                                           placeholder="e.g., S1234/0001/2020"
                                           value="{{ old('form_six_index_number', $aLevelEducation->form_six_index_number ?? '') }}"
                                           required>
                                </div>
                                @error('form_six_index_number')
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
                                        <option value="{{ $year }}" {{ old('end_of_study_year', $aLevelEducation->end_of_study_year ?? '') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('end_of_study_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Preferred University -->
                            <div class="form-group">
                                <label>Preferred University</label>
                                <input type="text"
                                       class="form-control @error('preferred_university') is-invalid @enderror"
                                       name="preferred_university"
                                       placeholder="Enter preferred university"
                                       value="{{ old('preferred_university', $aLevelEducation->preferred_university ?? '') }}">
                                @error('preferred_university')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Which university do you prefer to join?</small>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Division -->
                            <div class="form-group">
                                <label>Division <span class="text-danger">*</span></label>
                                <select class="form-control @error('division') is-invalid @enderror" name="division" required>
                                    <option value="">Select Division</option>
                                    <option value="I" {{ old('division', $aLevelEducation->division ?? '') == 'I' ? 'selected' : '' }}>Division I (Excellent)</option>
                                    <option value="II" {{ old('division', $aLevelEducation->division ?? '') == 'II' ? 'selected' : '' }}>Division II (Very Good)</option>
                                    <option value="III" {{ old('division', $aLevelEducation->division ?? '') == 'III' ? 'selected' : '' }}>Division III (Good)</option>
                                    <option value="IV" {{ old('division', $aLevelEducation->division ?? '') == 'IV' ? 'selected' : '' }}>Division IV (Satisfactory)</option>
                                    <option value="0" {{ old('division', $aLevelEducation->division ?? '') == '0' ? 'selected' : '' }}>Division 0 (Failed)</option>
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
                                       value="{{ old('points', $aLevelEducation->points ?? '') }}">
                                @error('points')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Range: 7 (best) to 33 (worst)</small>
                            </div>

                            <!-- Form Six Certificate -->
                            <div class="form-group">
                                <label>Form Six Certificate</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                    </div>
                                    <input type="file"
                                           class="form-control @error('form_six_certificate_path') is-invalid @enderror"
                                           name="form_six_certificate_path"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                @if(isset($aLevelEducation) && $aLevelEducation->form_six_certificate_path)
                                    <div class="mt-2">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Current Certificate:</strong> {{ basename($aLevelEducation->form_six_certificate_path) }}
                                            <br>
                                            <a href="{{ $aLevelEducation->certificate_url }}"
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

                                @error('form_six_certificate_path')
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
                                <a  href="{{ route('applicant.o-level-education') }}" class="btn btn-secondary btn-lg px-4" style="background-color: #07dc72; color: #fff;">
                                    <i class="fas fa-arrow-left"></i> Back to O-Level Education
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i>
                                    {{ isset($aLevelEducation) ? 'Update' : 'Save' }} A-Level Education
                                </button>

                                <a href="#" class="btn btn-secondary btn-lg px-4" style="background-color: #07dc72; color: #fff;">
                                    <i class="fas fa-arrow-right"></i> Proceed to Motivation Letter
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
