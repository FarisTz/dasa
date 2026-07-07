@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Payment Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('beneficiary.payments.index') }}">Installments</a></div>
            <div class="breadcrumb-item">Details</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-info-circle text-primary"></i> Payment Information</h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $payment->status_color }} badge-lg">
                        <i class="fas fa-{{ $payment->status_icon }}"></i>
                        {{ ucfirst($payment->status) }}
                    </span>

                    <a href="{{ route('beneficiary.payments.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Payment ID</th>
                                        <td>#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Installment Number</th>
                                        <td><strong>{{ $payment->installment->inst_number ?? 'N/A' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Academic Year</th>
                                        <td>{{ $payment->installment->academic_year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Student Year</th>
                                        <td>Year {{ $payment->installment->student_year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td><strong class="text-success">TSh {{ number_format($payment->amount, 2) }}</strong></td>
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
                                        <th width="40%">Status</th>
                                        <td>
                                            <span class="badge badge-{{ $payment->status_color }}">
                                                <i class="fas fa-{{ $payment->status_icon }}"></i>
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Signed Date</th>
                                        <td>
                                            @if($payment->confirmed_at)
                                                {{ $payment->confirmed_at->format('F d, Y H:i A') }}
                                                <br>
                                                <small class="text-muted">{{ $payment->confirmed_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Not signed yet</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created Date</th>
                                        <td>
                                            {{ $payment->created_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>
                                            {{ $payment->updated_at->format('F d, Y H:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $payment->updated_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        @if($payment->status == 'pending')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i>
                                <strong>Pending:</strong> Your signature is pending admin approval.
                            </div>
                        @elseif($payment->status == 'confirmed')
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle"></i>
                                <strong>Confirmed:</strong> Your signature has been confirmed and is awaiting final approval.
                            </div>
                        @elseif($payment->status == 'approved')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Approved!</strong> Your installment has been approved successfully.
                            </div>
                        @elseif($payment->status == 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Rejected:</strong> Your installment request was rejected. Please contact the administrator.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{ route('beneficiary.payments.index') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="fas fa-arrow-left"></i> Back to Installments
                        </a>

                        @if($payment->status=='pending')
                                                <a href="{{ route('beneficiary.payments.sign', $payment->installment_id) }}" class="btn btn-lg btn-success text-white">
                                                    <i  class="fas fa-check-circle"></i> Sign Now
                                                </a>
                                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
