
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-body">
        <!-- Personal Information Card -->

<div class="card">
  <div class="card-header">
    <h4>Personal Information</h4>
  </div>
  <div class="card-body">
    <form id="personalInfoForm" method="POST" action="" enctype="multipart/form-data">
      @csrf

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
            <label>Gender</label>
            <select class="form-control" name="gender">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <!-- Birthdate -->
          <div class="form-group">
            <label>Birthdate</label>
            <input type="date" class="form-control" name="birthdate">
          </div>

          <!-- Place of Birth -->
          <div class="form-group">
            <label>Place of Birth</label>
            <input type="text" class="form-control" name="place_of_birth" placeholder="Enter place of birth">
          </div>

          <!-- Nationality -->
          <div class="form-group">
            <label>Nationality</label>
            <input type="text" class="form-control" name="nationality" placeholder="Enter nationality" maxlength="100">
          </div>

          <!-- Marital Status -->
          <div class="form-group">
            <label>Marital Status</label>
            <input type="text" class="form-control" name="marital_status" placeholder="Enter marital status" maxlength="50">
          </div>

          <!-- Religion -->
          <div class="form-group">
            <label>Religion</label>
            <input type="text" class="form-control" name="religion" placeholder="Enter religion" maxlength="100">
          </div>

          <!-- Address Group -->
          <div class="form-group">
            <label class="font-weight-bold">Address Information</label>
            <hr>
          </div>

          <!-- Address -->
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" name="address" rows="3" placeholder="Enter address"></textarea>
          </div>

          <!-- Region -->
          <div class="form-group">
            <label>Region</label>
            <input type="text" class="form-control" name="region" placeholder="Enter region" maxlength="100">
          </div>

          <!-- District -->
          <div class="form-group">
            <label>District</label>
            <input type="text" class="form-control" name="district" placeholder="Enter district" maxlength="100">
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
            <label>Phone Number</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-phone"></i>
                </div>
              </div>
              <input type="text" class="form-control phone-number" name="phone_number" placeholder="Enter phone number" maxlength="20">
            </div>
          </div>

          <!-- ID Type -->
          <div class="form-group">
            <label>ID Type</label>
            <input type="text" class="form-control" name="id_type" placeholder="e.g., National ID, Passport" maxlength="50">
          </div>

          <!-- ID Number -->
          <div class="form-group">
            <label>ID Number</label>
            <input type="text" class="form-control" name="id_number" placeholder="Enter ID number" maxlength="100">
          </div>

          <!-- Disability -->
          <div class="form-group">
            <label>Disability</label>
            <input type="text" class="form-control" name="disability" placeholder="Enter disability information (if any)">
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
              <input type="file" class="form-control" name="birth_certificate_path" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <small class="text-muted">Upload birth certificate (PDF, JPG, JPEG, PNG)</small>
          </div>

          <!-- Next of Kin Group -->
          <div class="form-group">
            <label class="font-weight-bold">Next of Kin Information</label>
            <hr>
          </div>

          <!-- Kin Full Name -->
          <div class="form-group">
            <label>Kin Full Name</label>
            <input type="text" class="form-control" name="kin_full_name" placeholder="Enter next of kin full name">
          </div>

          <!-- Kin Relationship -->
          <div class="form-group">
            <label>Kin Relationship</label>
            <input type="text" class="form-control" name="kin_relationship" placeholder="e.g., Spouse, Parent, Sibling" maxlength="100">
          </div>

          <!-- Kin Phone Number -->
          <div class="form-group">
            <label>Kin Phone Number</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-phone"></i>
                </div>
              </div>
              <input type="text" class="form-control phone-number" name="kin_phone_number" placeholder="Enter kin phone number" maxlength="20">
            </div>
          </div>

          <!-- Kin Address -->
          <div class="form-group">
            <label>Kin Address</label>
            <textarea class="form-control" name="kin_address" rows="2" placeholder="Enter kin address"></textarea>
          </div>

          <!-- Kin District -->
          <div class="form-group">
            <label>Kin District</label>
            <input type="text" class="form-control" name="kin_district" placeholder="Enter kin district" maxlength="100">
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="row">
        <div class="col-12">
          <hr>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5">
              <i class="fas fa-save"></i> Save Personal Information
            </button>
            <button type="reset" class="btn btn-secondary btn-lg px-4">
              <i class="fas fa-undo"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
