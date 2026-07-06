@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Installment</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.installments.index') }}">Installments</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.installments.show', $installment->id) }}">{{ $installment->inst_number }}</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
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

        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit text-primary"></i> Edit Installment: {{ $installment->inst_number }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.installments.show', $installment->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> View Installment
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.installments.update', $installment->id) }}" id="installmentForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Installment Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('inst_number') is-invalid @enderror"
                                           name="inst_number"
                                           placeholder="e.g., INST-2026-001"
                                           value="{{ old('inst_number', $installment->inst_number) }}"
                                           required>
                                </div>
                                @error('inst_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Unique installment identifier.</small>
                            </div>

                            <div class="form-group">
                                <label>Academic Year <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('academic_year') is-invalid @enderror" name="academic_year" required>
                                        <option value="">Select Academic Year</option>
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 1;
                                            $endYear = $currentYear + 5;
                                        @endphp
                                        @for($year = $endYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}/{{ $year + 1 }}" {{ old('academic_year', $installment->academic_year) == $year . '/' . ($year + 1) ? 'selected' : '' }}>
                                                {{ $year }}/{{ $year + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('academic_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Student Year <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('student_year') is-invalid @enderror" name="student_year" required>
                                        <option value="">Select Year</option>
                                        @for($year = 1; $year <= 6; $year++)
                                            <option value="{{ $year }}" {{ old('student_year', $installment->student_year) == $year ? 'selected' : '' }}>
                                                Year {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('student_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Amount (TSh) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                    </div>
                                    <input type="number"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           name="amount"
                                           placeholder="Enter amount"
                                           value="{{ old('amount', $installment->amount) }}"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Enter the amount in TSh (Tanzanian Shillings).</small>
                            </div>

                            <div class="form-group">
                                <label>Release Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <input type="date"
                                           class="form-control @error('release_date') is-invalid @enderror"
                                           name="release_date"
                                           value="{{ old('release_date', $installment->release_date ? $installment->release_date->format('Y-m-d') : '') }}">
                                </div>
                                @error('release_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $installment->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        <i class="fas fa-toggle-{{ $installment->is_active ? 'on text-success' : 'off text-danger' }}"></i>
                                        {{ $installment->is_active ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                                <small class="text-muted d-block">Toggle to activate or deactivate this installment.</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Changing the amount will <strong>not</strong> affect existing student assignments. Only new assignments will use the updated amount.
                        <br>
                        <small>To update existing student payments, you need to manually edit each student's payment record.</small>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                    <i class="fas fa-save"></i> Update Installment
                                </button>
                                <a href="{{ route('admin.installments.show', $installment->id) }}" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Warning Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-exclamation-triangle text-warning"></i> Important Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="icon-circle bg-primary text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-users"></i>
                            </div>
                            <h6>Student Assignments</h6>
                            <p class="text-muted small">
                                Total assigned: <strong>{{ $installment->total_students }}</strong> students
                                <br>
                                Signed: <strong>{{ $installment->signed_count }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="icon-circle bg-success text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h6>Amount Impact</h6>
                            <p class="text-muted small">
                                Changing amount only affects <strong>new</strong> assignments.
                                <br>
                                Existing payments keep their original amount.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="icon-circle bg-warning text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h6>Deactivation</h6>
                            <p class="text-muted small">
                                Deactivating prevents <strong>new</strong> sign-ups.
                                <br>
                                Existing signed payments remain unchanged.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('styles')
<style>
    .icon-circle {
        transition: transform 0.3s ease;
    }
    .icon-circle:hover {
        transform: scale(1.1);
    }
    .custom-control-label i {
        font-size: 16px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle status switch visual update
        $('#is_active').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).next('.custom-control-label').html(
                    '<i class="fas fa-toggle-on text-success"></i> Active'
                );
            } else {
                $(this).next('.custom-control-label').html(
                    '<i class="fas fa-toggle-off text-danger"></i> Inactive'
                );
            }
        });

        // Form submission validation
        $('#installmentForm').on('submit', function(e) {
            var isValid = true;
            var errorMessages = [];

            // Validate installment number
            var instNumber = $('input[name="inst_number"]').val().trim();
            if (!instNumber) {
                isValid = false;
                errorMessages.push('Installment number is required.');
                $('input[name="inst_number"]').addClass('is-invalid');
            } else {
                $('input[name="inst_number"]').removeClass('is-invalid');
            }

            // Validate academic year
            var academicYear = $('select[name="academic_year"]').val();
            if (!academicYear) {
                isValid = false;
                errorMessages.push('Academic year is required.');
                $('select[name="academic_year"]').addClass('is-invalid');
            } else {
                $('select[name="academic_year"]').removeClass('is-invalid');
            }

            // Validate student year
            var studentYear = $('select[name="student_year"]').val();
            if (!studentYear) {
                isValid = false;
                errorMessages.push('Student year is required.');
                $('select[name="student_year"]').addClass('is-invalid');
            } else {
                $('select[name="student_year"]').removeClass('is-invalid');
            }

            // Validate amount
            var amount = $('input[name="amount"]').val().trim();
            if (!amount || parseFloat(amount) <= 0) {
                isValid = false;
                errorMessages.push('Amount must be greater than 0.');
                $('input[name="amount"]').addClass('is-invalid');
            } else {
                $('input[name="amount"]').removeClass('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
                // Show error alert
                var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                alertHtml += '<strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>';
                alertHtml += '<ul class="mb-0 mt-2">';
                errorMessages.forEach(function(msg) {
                    alertHtml += '<li>' + msg + '</li>';
                });
                alertHtml += '</ul>';
                alertHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                alertHtml += '<span aria-hidden="true">&times;</span>';
                alertHtml += '</button>';
                alertHtml += '</div>';

                // Remove existing alerts and add new one
                $('.alert-danger').remove();
                $('.section-body .card:first').before(alertHtml);
                return false;
            }

            // Disable submit button to prevent double submission
            $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> Updating...');
            $('#submitBtn').prop('disabled', true);
        });
    });
</script>
@endpush
