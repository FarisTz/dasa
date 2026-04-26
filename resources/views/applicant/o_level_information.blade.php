@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">O-Level Education</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ isset($oLevel) ? route('applicant.o-level.update', $oLevel->id) : route('applicant.o-level.store') }}" 
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($oLevel))
                                @method('PUT')
                            @endif

                            <!-- O-Level Education Information -->
                            <h5 class="card-title mb-3">O-Level Education Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_name">School Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="school_name" name="school_name" 
                                               value="{{ old('school_name', $oLevel->school_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_four_index_number">Form Four Index Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="form_four_index_number" name="form_four_index_number" 
                                               value="{{ old('form_four_index_number', $oLevel->form_four_index_number ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="division">Division <span class="text-danger">*</span></label>
                                        <select class="form-control" id="division" name="division" required>
                                            <option value="">Select Division</option>
                                            <option value="I" {{ old('division', $oLevel->division ?? '') == 'I' ? 'selected' : '' }}>Division I</option>
                                            <option value="II" {{ old('division', $oLevel->division ?? '') == 'II' ? 'selected' : '' }}>Division II</option>
                                            <option value="III" {{ old('division', $oLevel->division ?? '') == 'III' ? 'selected' : '' }}>Division III</option>
                                            <option value="IV" {{ old('division', $oLevel->division ?? '') == 'IV' ? 'selected' : '' }}>Division IV</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="points">Points <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="points" name="points" 
                                               value="{{ old('points', $oLevel->points ?? '') }}" min="0" max="40" required>
                                        <small class="form-text text-muted">Enter points between 0 and 40</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_of_study_year">End of Study Year <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="end_of_study_year" name="end_of_study_year" 
                                               value="{{ old('end_of_study_year', $oLevel->end_of_study_year ?? '') }}" 
                                               min="1950-01-01" max="{{ date('Y') }}-12-31" required>
                                        <small class="form-text text-muted">Enter the year you completed Form Four</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Documents -->
                            <h5 class="card-title mb-3">Upload Documents</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_four_certificate">Upload Form Four Certificate / Result Slips</label>
                                        <input type="file" class="form-control-file" id="form_four_certificate" name="form_four_certificate" 
                                               accept="application/pdf,image/jpeg,image/png">
                                        @if (isset($oLevel->form_four_certificate_path))
                                            <small class="text-muted">Current file: {{ basename($oLevel->form_four_certificate_path) }}</small>
                                        @endif
                                        <small class="form-text text-muted">Allowed formats: PDF, JPG, PNG (Max: 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($oLevel) ? 'Update Information' : 'Save Information' }}
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection