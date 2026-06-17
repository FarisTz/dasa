@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit User</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></div>
            <div class="breadcrumb-item">Edit User</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-edit"></i> Edit User : {{ $user->name }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6 col-md-12">
                            <!-- Name -->
                            <div class="form-group">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           placeholder="Enter full name"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                </div>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label>Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           placeholder="Enter email address"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Email must be unique in the system.</small>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="Enter password (min 8 characters)"
                                           value="{{ old('password') }}"
                                           required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Password must be at least 8 characters long.</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <input type="password"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           name="password_confirmation"
                                           placeholder="Confirm password"
                                           required>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6 col-md-12">
                            <!-- Role -->
                            <div class="form-group">
                                <label>Role <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="applicant" {{ old('role') == 'applicant' ? 'selected' : '' }}>Applicant</option>
                                        <option value="coordinator" {{ old('role') == 'coordinator' ? 'selected' : '' }}>Coordinator</option>
                                        <option value="beneficiary" {{ old('role') == 'beneficiary' ? 'selected' : '' }}>Beneficiary</option>
                                    </select>
                                </div>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Email Verified At -->
                            <div class="form-group">
                                <label>Email Verification Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="email_verified"
                                           name="email_verified"
                                           value="1"
                                           {{ old('email_verified') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="email_verified">
                                        <i class="fas fa-check-circle text-success"></i>
                                        Mark email as verified
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">If checked, the user's email will be marked as verified immediately.</small>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>Account Status <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>
                    </div>



                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Create User
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-times"></i> Cancel
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

@push('styles')
<style>
    .custom-control-label {
        cursor: pointer;
        font-weight: 500;
    }
    .custom-control-label i {
        font-size: 16px;
    }
    .form-group .input-group-text {
        min-width: 42px;
        justify-content: center;
    }
    .badge {
        font-size: 12px;
        padding: 4px 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    /**
     * Toggle password visibility
     */
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(fieldId + '-eye');

        if (field.type === 'password') {
            field.type = 'text';
            eye.className = 'fas fa-eye-slash';
        } else {
            field.type = 'password';
            eye.className = 'fas fa-eye';
        }
    }

    /**
     * Password strength indicator
     */
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.querySelector('input[name="password"]');
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'mt-2';
        strengthIndicator.innerHTML = `
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" id="passwordStrength"></div>
            </div>
            <small class="text-muted" id="passwordStrengthText">Enter a password</small>
        `;
        passwordInput.parentNode.parentNode.appendChild(strengthIndicator);

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');

            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'progress-bar';
                strengthText.textContent = 'Enter a password';
                return;
            }

            let strength = 0;

            // Length check
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 10;

            // Character variety
            if (/[a-z]/.test(password)) strength += 15;
            if (/[A-Z]/.test(password)) strength += 15;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 20;

            // Cap at 100
            strength = Math.min(strength, 100);

            strengthBar.style.width = strength + '%';

            // Color and text based on strength
            if (strength < 30) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak password';
            } else if (strength < 60) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Fair password';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-info';
                strengthText.textContent = 'Good password';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong password!';
            }
        });
    });
</script>
@endpush
