@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Personal Information</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Complete Your Personal Information</h4>
                    </div>
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

                        <form action="{{ isset($applicant) ? route('applicant.personal-information.update', $applicant->id) : route('applicant.personal-information.store') }}" 
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($applicant))
                                @method('PUT')
                            @endif

                            <!-- Basic Personal Information -->
                            <h5 class="card-title mb-3">1. Basic Personal Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                               value="{{ old('full_name', $applicant->full_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $applicant->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $applicant->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $applicant->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birthdate">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                               value="{{ old('birthdate', $applicant->birthdate ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="place_of_birth">Place of Birth <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" 
                                               value="{{ old('place_of_birth', $applicant->place_of_birth ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nationality">Nationality <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nationality" name="nationality" 
                                               value="{{ old('nationality', $applicant->nationality ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="marital_status" name="marital_status" required>
                                            <option value="">Select Status</option>
                                            <option value="single" {{ old('marital_status', $applicant->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('marital_status', $applicant->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('marital_status', $applicant->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="widowed" {{ old('marital_status', $applicant->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="religion">Religion <span class="text-danger">*</span></label>
                                        <select class="form-control" id="religion" name="religion" required>
                                            <option value="">Select Religion</option>
                                            <option value="muslim" {{ old('religion', $applicant->religion ?? '') == 'muslim' ? 'selected' : '' }}>Muslim</option>
                                            <option value="christian" {{ old('religion', $applicant->religion ?? '') == 'christian' ? 'selected' : '' }}>Christian</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Contact Information -->
                            <h5 class="card-title mb-3">2. Contact Information</h5>
                            
                            <div class="form-group">
                                <label for="address">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" required value="{{ old('address', $applicant->address ?? '') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="region">Region <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="region" name="region" 
                                               value="{{ old('region', $applicant->region ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="district">District <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="district" name="district" 
                                               value="{{ old('district', $applicant->district ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                               value="{{ old('phone_number', $applicant->phone_number ?? '') }}" required >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email', $applicant->email ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Identification Details -->
                            <h5 class="card-title mb-3">3. Identification Details</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zanzibar_national_id">Zanzibar/National ID</label>
                                        <input type="text" class="form-control" id="zanzibar_national_id" name="zanzibar_national_id" 
                                               value="{{ old('zanzibar_national_id', $applicant->zanzibar_national_id ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport_number">Passport Number</label>
                                        <input type="text" class="form-control" id="passport_number" name="passport_number" 
                                               value="{{ old('passport_number', $applicant->passport_number ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Additional Information & Documents -->
                            <h5 class="card-title mb-3">4. Additional Information & Documents</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="disability" name="disability" 
                                                   value="1" {{ old('disability', $applicant->disability ?? false) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="disability">Do you have any disability?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_certificate">Upload Birth Certificate</label>
                                        <input type="file" class="form-control-file" id="birth_certificate" name="birth_certificate" 
                                               accept="application/pdf,image/jpeg,image/png">
                                        @if (isset($applicant->birth_certificate_path))
                                            <small class="text-muted">Current file: {{ basename($applicant->birth_certificate_path) }}</small>
                                        @endif
                                        <small class="form-text text-muted">Allowed formats: PDF, JPG, PNG (Max: 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Next of Kin Information -->
                            <h5 class="card-title mb-3">5. Next of Kin Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kin_full_name">Full Name of Kin <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kin_full_name" name="kin_full_name" 
                                               value="{{ old('kin_full_name', $applicant->kin_full_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kin_relationship">Relationship <span class="text-danger">*</span></label>
                                        <select class="form-control" id="kin_relationship" name="kin_relationship" required>
                                            <option value="">Select Relationship</option>
                                            <option value="father" {{ old('kin_relationship', $applicant->kin_relationship ?? '') == 'father' ? 'selected' : '' }}>Father</option>
                                            <option value="mother" {{ old('kin_relationship', $applicant->kin_relationship ?? '') == 'mother' ? 'selected' : '' }}>Mother</option>
                                            <option value="uncle" {{ old('kin_relationship', $applicant->kin_relationship ?? '') == 'uncle' ? 'selected' : '' }}>Uncle</option>
                                            <option value="guardian" {{ old('kin_relationship', $applicant->kin_relationship ?? '') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kin_phone_number">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kin_phone_number" name="kin_phone_number" 
                                               value="{{ old('kin_phone_number', $applicant->kin_phone_number ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kin_religion">Religion <span class="text-danger">*</span></label>
                                        <select class="form-control" id="kin_religion" name="kin_religion" required>
                                            <option value="">Select Religion</option>
                                            <option value="muslim" {{ old('kin_religion', $applicant->kin_religion ?? '') == 'muslim' ? 'selected' : '' }}>Muslim</option>
                                            <option value="christian" {{ old('kin_religion', $applicant->kin_religion ?? '') == 'christian' ? 'selected' : '' }}>Christian</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="kin_address">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kin_address" name="kin_address" required value="{{ old('kin_address', $applicant->kin_address ?? '') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kin_region">Region <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kin_region" name="kin_region" 
                                               value="{{ old('kin_region', $applicant->kin_region ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kin_district">District <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kin_district" name="kin_district" 
                                               value="{{ old('kin_district', $applicant->kin_district ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($applicant) ? 'Update Information' : 'Save Information' }}
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