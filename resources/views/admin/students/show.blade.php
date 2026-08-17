@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Student Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></div>
            <div class="breadcrumb-item">Student Details</div>
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

        <!-- Student Header -->
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-user-graduate text-primary"></i>
                    {{ $student->name }}
                </h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $student->status == 'active' ? 'success' : ($student->status == 'inactive' ? 'warning' : 'danger') }} badge-lg">
                        <i class="fas fa-circle"></i> {{ ucfirst($student->status) }}
                    </span>
                    @if($student->is_academic_suspended)
                        <span class="badge badge-danger badge-lg ml-2">
                            <i class="fas fa-ban"></i> Suspended
                        </span>
                    @else
                        <span class="badge badge-success badge-lg ml-2">
                            <i class="fas fa-check"></i> Active
                        </span>
                    @endif
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-12 text-center">
                        <img src="{{ $student->profile_photo_url ?? asset('images/default-avatar.png') }}"
                             alt="{{ $student->name }}"
                             class="rounded-circle"
                             width="120"
                             height="120"
                             style="object-fit: cover; border: 4px solid #f8f9fa; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <h5 class="mt-3">{{ $student->name }}</h5>
                        <p class="text-muted">{{ $student->email }}</p>
                        <div class="mt-2">
                            <span class="badge badge-{{ $student->role == 'beneficiary' ? 'success' : 'info' }}">
                                {{ ucfirst($student->role) }}
                            </span>
                            <span class="badge badge-secondary">
                                <i class="fas fa-calendar-alt"></i> Joined {{ $student->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="25%">Student ID</th>
                                        <td><code>#{{ str_pad($student->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><a href="mailto:{{ $student->email }}">{{ $student->email }}</a></td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td>{{ $student->phone_number ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Scholarship</th>
                                        <td>
                                            @if($application && $application->scholarship)
                                                <strong>{{ $application->scholarship->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $application->scholarship->academic_year }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Application Status</th>
                                        <td>
                                            @if($application)
                                                <span class="badge badge-{{ $application->status == 'approved_full' ? 'success' : ($application->status == 'approved_partial' ? 'info' : 'warning') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">No application</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Acknowledgement Status</th>
                                        <td>
                                            @if($application)
                                                <span class="badge badge-{{ $application->acknowledgement_status_color }}">
                                                    <i class="fas fa-{{ $application->acknowledgement_status_icon }}"></i>
                                                    {{ ucfirst($application->acknowledgement_status) }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Academic Status</th>
                                        <td>
                                            @if($student->is_academic_suspended)
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Suspended
                                                    @if($student->academic_suspension_reason)
                                                        <br>
                                                        <small><strong>Reason:</strong> {{ $student->academic_suspension_reason }}</small>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Active
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            <i class="fas fa-clock"></i>
                                            {{ $student->updated_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $student->updated_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Form -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit text-primary"></i> Update Student Status</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.students.update-status', $student->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Account Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ $student->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Academic Suspension</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_academic_suspended" name="is_academic_suspended" value="1" {{ $student->is_academic_suspended ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_academic_suspended">
                                        {{ $student->is_academic_suspended ? 'Suspended' : 'Active' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="suspensionReasonGroup" style="{{ $student->is_academic_suspended ? '' : 'display: none;' }}">
                                <label>Suspension Reason <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('suspension_reason') is-invalid @enderror" name="suspension_reason" placeholder="Enter reason for suspension..." value="{{ $student->academic_suspension_reason }}">
                                @error('suspension_reason')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabs -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-folder-open text-primary"></i> Student Information</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="studentTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                            <i class="fas fa-user"></i> Personal Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab">
                            <i class="fas fa-graduation-cap"></i> Academic Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab">
                            <i class="fas fa-money-bill-wave"></i> Payments
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="studentTabsContent">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        @if($student->personalInfo)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th width="40%">Gender</th>
                                                    <td>{{ ucfirst($student->personalInfo->gender) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Birthdate</th>
                                                    <td>{{ $student->personalInfo->birthdate ? $student->personalInfo->birthdate->format('F d, Y') : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Place of Birth</th>
                                                    <td>{{ $student->personalInfo->place_of_birth ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nationality</th>
                                                    <td>{{ $student->personalInfo->nationality ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Marital Status</th>
                                                    <td>{{ ucfirst($student->personalInfo->marital_status ?? '-') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Religion</th>
                                                    <td>{{ ucfirst($student->personalInfo->religion ?? '-') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th width="40%">Address</th>
                                                    <td>{{ $student->personalInfo->address ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Region</th>
                                                    <td>{{ $student->personalInfo->region ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>District</th>
                                                    <td>{{ $student->personalInfo->district ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone Number</th>
                                                    <td>{{ $student->personalInfo->phone_number ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>ID Type</th>
                                                    <td>{{ $student->personalInfo->id_type ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>ID Number</th>
                                                    <td>{{ $student->personalInfo->id_number ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h6 class="font-weight-bold">Next of Kin</h6>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">Full Name</th>
                                                        <td>{{ $student->personalInfo->kin_full_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Relationship</th>
                                                        <td>{{ ucfirst($student->personalInfo->kin_relationship ?? '-') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone Number</th>
                                                        <td>{{ $student->personalInfo->kin_phone_number ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%">Address</th>
                                                        <td>{{ $student->personalInfo->kin_address ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>District</th>
                                                        <td>{{ $student->personalInfo->kin_district ?? '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>Personal information not provided.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Academic Results Tab -->
                    <div class="tab-pane fade" id="academic" role="tabpanel">
                        @if($academicResults->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Academic Year</th>
                                            <th>Year</th>
                                            <th>GPA</th>
                                            <th>CGPA</th>
                                            <th>Division</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($academicResults as $result)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $result->academic_year }}</strong></td>
                                                <td>Year {{ $result->student_year }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $result->gpa >= 3.5 ? 'success' : ($result->gpa >= 2.5 ? 'warning' : 'danger') }}">
                                                        {{ $result->formatted_gpa }}
                                                    </span>
                                                </td>
                                                <td>{{ $result->formatted_cgpa }}</td>
                                                <td>{{ $result->division ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $result->status_color }}">
                                                        {{ ucfirst($result->status) }}
                                                    </span>
                                                    @if($result->is_suspended)
                                                        <span class="badge badge-danger">Suspended</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($result->result_file_path)
                                                        <a href="{{ asset('storage/' . $result->result_file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No academic results found.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Payments Tab -->
                    <div class="tab-pane fade" id="payments" role="tabpanel">
                        @if($payments->count() > 0)
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $paymentStats['total'] }}</h3>
                                            <p>Total Payments</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $paymentStats['approved'] }}</h3>
                                            <p>Approved</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $paymentStats['pending'] }}</h3>
                                            <p>Pending</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3>TSh {{ number_format($paymentStats['total_amount'], 2) }}</h3>
                                            <p>Total Amount</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Installment</th>
                                            <th>Academic Year</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Signed Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $payment->installment->inst_number ?? 'N/A' }}</td>
                                                <td>{{ $payment->installment->academic_year ?? 'N/A' }}</td>
                                                <td><strong>TSh {{ number_format($payment->amount, 2) }}</strong></td>
                                                <td>
                                                    <span class="badge badge-{{ $payment->status_color }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($payment->confirmed_at)
                                                        {{ $payment->confirmed_at->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">Not signed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No payment records found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-lg px-4 mr-2">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('admin.applications.show', $application->id ?? 0) }}" class="btn btn-info btn-lg px-4 mr-2">
                                <i class="fas fa-file-alt"></i> View Application
                            </a>
                            <button type="button" class="btn btn-secondary btn-lg px-4" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
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
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
    .small-box {
        border-radius: 10px;
        padding: 20px;
        color: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .small-box .inner h3 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }
    .small-box .inner p {
        margin: 5px 0 0;
        opacity: 0.8;
    }
    .bg-info { background: linear-gradient(135deg, #17a2b8, #0f7a8a); }
    .bg-success { background: linear-gradient(135deg, #28a745, #1e7e34); }
    .bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800); }
    .bg-primary { background: linear-gradient(135deg, #4e73df, #224abe); }
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle suspension reason field
        $('#is_academic_suspended').on('change', function() {
            if ($(this).is(':checked')) {
                $('#suspensionReasonGroup').show();
                $('input[name="suspension_reason"]').prop('required', true);
            } else {
                $('#suspensionReasonGroup').hide();
                $('input[name="suspension_reason"]').prop('required', false);
            }
        });
    });
</script>
@endpush
