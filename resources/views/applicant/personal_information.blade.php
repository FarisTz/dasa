@extends('layouts.app')

@section('content')
<section class="section">
<div class="section-header">
        <h1>Personal Information</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Personal Information</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Personal Information Card -->
        <div class="card">
            <div class="card-header">
                <h4>{{ isset($personalInfo) ? 'Edit' : 'Add' }} Personal Information</h4>
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

                <form id="personalInfoForm" method="POST" action="{{ route('applicant.personal-information.store') }}" enctype="multipart/form-data">
                    @csrf


                    <!-- Display General Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Two Column Layout with Flex -->
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Personal Details Group -->
                            <div class="form-group">
                                <label class="font-weight-bold">Personal Details</label>
                                <hr>
                            </div>

                            <!-- Gender -->
                            <div class="form-group">
                                <label>Gender <span class="text-danger">*</span></label>
                                <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $personalInfo->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $personalInfo->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $personalInfo->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Birthdate -->
                            <div class="form-group">
                                <label>Birthdate <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate', isset($personalInfo) && $personalInfo->birthdate ? $personalInfo->birthdate->format('Y-m-d') : '') }}" required>
                                @error('birthdate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Place of Birth -->
                            <div class="form-group">
                                <label>Place of Birth <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" name="place_of_birth" placeholder="Enter place of birth" value="{{ old('place_of_birth', $personalInfo->place_of_birth ?? '') }}" required>
                                @error('place_of_birth')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nationality -->
                            <div class="form-group">
                                <label>Nationality <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" name="nationality" placeholder="Enter nationality" maxlength="100" value="{{ old('nationality', $personalInfo->nationality ?? '') }}" required>
                                @error('nationality')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Marital Status -->
                            <div class="form-group">
                                <label>Marital Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('marital_status') is-invalid @enderror" name="marital_status" required>
                                    <option value="">Select Marital Status</option>
                                    <option value="single" {{ old('marital_status', $personalInfo->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status', $personalInfo->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status', $personalInfo->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status', $personalInfo->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('marital_status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Religion -->
                            <div class="form-group">
                                <label>Religion <span class="text-danger">*</span></label>
                                <select class="form-control @error('religion') is-invalid @enderror" name="religion" required>
                                    <option value="">Select Religion</option>
                                    <option value="muslim" {{ old('religion', $personalInfo->religion ?? '') == 'muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="christian" {{ old('religion', $personalInfo->religion ?? '') == 'christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="hindu" {{ old('religion', $personalInfo->religion ?? '') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="buddhist" {{ old('religion', $personalInfo->religion ?? '') == 'buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="other" {{ old('religion', $personalInfo->religion ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('religion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Address Group -->
                            <div class="form-group">
                                <label class="font-weight-bold">Address Information</label>
                                <hr>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" placeholder="Enter address" required>{{ old('address', $personalInfo->address ?? '') }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Region -->
                            <div class="form-group">
                                <label>Region <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('region') is-invalid @enderror" name="region" placeholder="Enter region" maxlength="100" value="{{ old('region', $personalInfo->region ?? '') }}" required>
                                @error('region')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- District -->
                            <div class="form-group">
                                <label>District <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('district') is-invalid @enderror" name="district" placeholder="Enter district" maxlength="100" value="{{ old('district', $personalInfo->district ?? '') }}" required>
                                @error('district')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <!-- Contact & ID Group -->
                            <div class="form-group">
                                <label class="font-weight-bold">Contact & Identification</label>
                                <hr>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label>Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control phone-number @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Enter phone number" maxlength="20" value="{{ old('phone_number', $personalInfo->phone_number ?? '') }}" required>
                                </div>
                                @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- ID Type -->
                            <div class="form-group">
                                <label>ID Type</label>
                                <select class="form-control @error('id_type') is-invalid @enderror" name="id_type">
                                    <option value="">Select ID Type</option>
                                    <option value="National" {{ old('id_type', $personalInfo->id_type ?? '') == 'National' ? 'selected' : '' }}>National ID</option>
                                    <option value="zanID" {{ old('id_type', $personalInfo->id_type ?? '') == 'zanID' ? 'selected' : '' }}>ZanID</option>
                                    <option value="Passport" {{ old('id_type', $personalInfo->id_type ?? '') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="Voter" {{ old('id_type', $personalInfo->id_type ?? '') == 'Voter' ? 'selected' : '' }}>Voter ID</option>
                                    <option value="Driving" {{ old('id_type', $personalInfo->id_type ?? '') == 'Driving' ? 'selected' : '' }}>Driving License</option>
                                </select>
                                @error('id_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- ID Number -->
                            <div class="form-group">
                                <label>ID Number</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" placeholder="Enter ID number" maxlength="100" value="{{ old('id_number', $personalInfo->id_number ?? '') }}">
                                @error('id_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Disability -->
                            <div class="form-group">
                                <label>Disability</label>
                                <select class="form-control @error('disability') is-invalid @enderror" name="disability">
                                    <option value="">Select Disability Status</option>
                                    <option value="none" {{ old('disability', $personalInfo->disability ?? '') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="physical" {{ old('disability', $personalInfo->disability ?? '') == 'physical' ? 'selected' : '' }}>Physical Disability</option>
                                    <option value="visual" {{ old('disability', $personalInfo->disability ?? '') == 'visual' ? 'selected' : '' }}>Visual Impairment</option>
                                    <option value="hearing" {{ old('disability', $personalInfo->disability ?? '') == 'hearing' ? 'selected' : '' }}>Hearing Impairment</option>
                                    <option value="other" {{ old('disability', $personalInfo->disability ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('disability')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Birth Certificate -->
<div class="form-group">
                                <label>Birth Certificate</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control @error('birth_certificate_path') is-invalid @enderror" name="birth_certificate_path" accept=".pdf,.jpg,.jpeg,.png">
                                </div>


@if(isset($personalInfo) && $personalInfo->birth_certificate_path)
                                    <div class="mt-2">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Current Certificate:</strong> {{ basename($personalInfo->birth_certificate_path) }}
                                            <br>
                                            <a href="{{ Storage::url($personalInfo->birth_certificate_path) }}"
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




                            <!-- Next of Kin Group -->
                            <div class="form-group">
                                <label class="font-weight-bold">Next of Kin Information</label>
                                <hr>
                            </div>

                            <!-- Kin Full Name -->
                            <div class="form-group">
                                <label>Kin Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kin_full_name') is-invalid @enderror" name="kin_full_name" placeholder="Enter next of kin full name" value="{{ old('kin_full_name', $personalInfo->kin_full_name ?? '') }}" required>
                                @error('kin_full_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kin Relationship -->
                            <div class="form-group">
                                <label>Kin Relationship <span class="text-danger">*</span></label>
                                <select class="form-control @error('kin_relationship') is-invalid @enderror" name="kin_relationship" required>
                                    <option value="">Select Relationship</option>
                                    <option value="father" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'father' ? 'selected' : '' }}>Father</option>
                                    <option value="mother" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="uncle" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'uncle' ? 'selected' : '' }}>Uncle</option>
                                    <option value="guardian" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                                    <option value="spouse" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                                    <option value="sibling" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'sibling' ? 'selected' : '' }}>Sibling</option>
                                    <option value="other" {{ old('kin_relationship', $personalInfo->kin_relationship ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('kin_relationship')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kin Phone Number -->
                            <div class="form-group">
                                <label>Kin Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control phone-number @error('kin_phone_number') is-invalid @enderror" name="kin_phone_number" placeholder="Enter kin phone number" maxlength="20" value="{{ old('kin_phone_number', $personalInfo->kin_phone_number ?? '') }}" required>
                                </div>
                                @error('kin_phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kin Address -->
                            <div class="form-group">
                                <label>Kin Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('kin_address') is-invalid @enderror" name="kin_address" rows="2" placeholder="Enter kin address" required>{{ old('kin_address', $personalInfo->kin_address ?? '') }}</textarea>
                                @error('kin_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kin District -->
                            <div class="form-group">
                                <label>Kin District <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kin_district') is-invalid @enderror" name="kin_district" placeholder="Enter kin district" maxlength="100" value="{{ old('kin_district', $personalInfo->kin_district ?? '') }}" required>
                                @error('kin_district')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> {{ isset($personalInfo) ? 'Update' : 'Save' }} Personal Information
                                </button>


                                <a href="{{ route('applicant.o-level-education') }}" class="btn btn-secondary btn-lg px-4" >
                                    <i class="fas fa-arrow-right"></i> Proceed to O-Level Education
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
