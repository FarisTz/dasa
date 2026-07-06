@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Installment Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.installments.index') }}">Installments</a></div>
            <div class="breadcrumb-item">Details</div>
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

        <!-- Installment Details -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-info-circle text-primary"></i> Installment Information</h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $installment->is_active ? 'success' : 'danger' }} badge-lg">
                        <i class="fas fa-{{ $installment->is_active ? 'check-circle' : 'times-circle' }}"></i>
                        {{ $installment->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <a href="{{ route('admin.installments.edit', $installment->id) }}" class="btn btn-warning ml-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.installments.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Installment ID</th>
                                        <td><code>#{{ str_pad($installment->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Installment Number</th>
                                        <td><strong>{{ $installment->inst_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Academic Year</th>
                                        <td>{{ $installment->academic_year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Student Year</th>
                                        <td>Year {{ $installment->student_year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td><strong class="text-success">TSh {{ number_format($installment->amount, 2) }}</strong></td>
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
                                        <th width="40%">Release Date</th>
                                        <td>
                                            @if($installment->release_date)
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $installment->release_date->format('F d, Y') }}
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge badge-{{ $installment->is_active ? 'success' : 'danger' }}">
                                                {{ $installment->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $installment->creator->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $installment->creator->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="25"
                                                     height="25">
                                                {{ $installment->creator->name ?? 'Unknown' }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created Date</th>
                                        <td>
                                            <i class="fas fa-calendar-plus"></i>
                                            {{ $installment->created_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $installment->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            <i class="fas fa-clock"></i>
                                            {{ $installment->updated_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $installment->updated_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mt-3">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Students</h4>
                                </div>
                                <div class="card-body">
                                    {{ $installment->total_students }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Pending</h4>
                                </div>
                                <div class="card-body">
                                    {{ $installment->pending_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Signed</h4>
                                </div>
                                <div class="card-body">
                                    {{ $installment->signed_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-percent"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Completion Rate</h4>
                                </div>
                                <div class="card-body">
                                    @php
                                        $total = $installment->total_students;
                                        $signed = $installment->signed_count;
                                        $rate = $total > 0 ? round(($signed / $total) * 100) : 0;
                                    @endphp
                                    {{ $rate }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Student -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-plus text-primary"></i> Assign Student</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.installments.assign-student', $installment->id) }}" class="row">
                    @csrf
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Select Student <span class="text-danger">*</span></label>
                            <select class="form-control @error('student_id') is-invalid @enderror" name="student_id" required>
                                <option value="">Select a student...</option>
                                @php
                                    $assignedStudents = $installment->studentPayments->pluck('student_id')->toArray();
                                    $availableStudents = \App\Models\User::where('role', 'beneficiary')
                                        ->whereNotIn('id', $assignedStudents)
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                @foreach($availableStudents as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus"></i> Assign Student
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Student Payments Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list text-primary"></i> Student Payments</h4>
                <div class="card-header-action">
                    <span class="badge badge-primary">
                        Total: {{ $studentPayments->total() }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                @if($studentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>OTP</th>
                                    <th>Status</th>
                                    <th>Signed Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentPayments as $payment)
                                    <tr>
                                        <td>{{ $studentPayments->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $payment->student->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $payment->student->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="30"
                                                     height="30">
                                                <strong>{{ $payment->student->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $payment->student->email }}</td>
                                        <td><strong>TSh {{ number_format($payment->amount, 2) }}</strong></td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $payment->otp }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $payment->status_color }}">
                                                <i class="fas fa-{{ $payment->status_icon }}"></i>
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->confirmed_at)
                                                {{ $payment->confirmed_at->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $payment->confirmed_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Not signed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($payment->status == 'pending' || $payment->status == 'confirmed')
                                                    <form method="POST" action="" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this payment?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this payment?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('admin.users.show', $payment->student_id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="text-muted small">
                                Showing {{ $studentPayments->firstItem() ?? 0 }} to {{ $studentPayments->lastItem() ?? 0 }} of {{ $studentPayments->total() }} entries
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $studentPayments->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No students have been assigned to this installment yet.</p>
                        <p>Assign students using the form above.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.installments.edit', $installment->id) }}" class="btn btn-warning btn-lg px-4 mr-2">
                                <i class="fas fa-edit"></i> Edit Installment
                            </a>
                            <a href="{{ route('admin.installments.toggle-status', $installment->id) }}"
                               class="btn btn-{{ $installment->is_active ? 'danger' : 'success' }} btn-lg px-4 mr-2"
                               onclick="return confirm('Are you sure you want to {{ $installment->is_active ? 'deactivate' : 'activate' }} this installment?')">
                                <i class="fas fa-{{ $installment->is_active ? 'times' : 'check' }}"></i>
                                {{ $installment->is_active ? 'Deactivate' : 'Activate' }}
                            </a>
                            <button type="button" class="btn btn-danger btn-lg px-4" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h5>Are you sure you want to delete this installment?</h5>
                    <p><strong>{{ $installment->inst_number }}</strong></p>
                    <p><strong>Academic Year:</strong> {{ $installment->academic_year }}</p>
                    <p class="text-danger"><small>This action cannot be undone. All associated student payments will also be deleted.</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.installments.destroy', $installment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Installment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .card-statistic-1 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        padding: 15px;
        height: 100%;
    }
    .card-statistic-1:hover {
        transform: translateY(-5px);
    }
    .card-statistic-1 .card-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 24px;
    }
    .card-statistic-1 .card-wrap {
        padding-left: 10px;
    }
    .card-statistic-1 .card-header {
        padding: 0;
        background: none;
        border: none;
    }
    .card-statistic-1 .card-header h4 {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        margin: 0;
    }
    .card-statistic-1 .card-body {
        font-size: 20px;
        font-weight: 700;
        padding: 5px 0;
        color: #2d2d2d;
    }
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
</style>
@endpush
