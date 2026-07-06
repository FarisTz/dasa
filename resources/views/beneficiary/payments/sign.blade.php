@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Sign Installment</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('beneficiary.payments.index') }}">Installments</a></div>
            <div class="breadcrumb-item">Sign</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-8 col-md-12 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-handshake text-success"></i> Sign Installment</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Installment Details:</strong>
                            <div class="mt-2">
                                <p><strong>Installment Number:</strong> {{ $installment->inst_number }}</p>
                                <p><strong>Academic Year:</strong> {{ $installment->academic_year }}</p>
                                <p><strong>Student Year:</strong> Year {{ $installment->student_year }}</p>
                                <p><strong>Amount:</strong> <span class="text-success font-weight-bold">TSh {{ number_format($installment->amount, 2) }}</span></p>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Please Note:</strong>
                            <ul class="mb-0 mt-2">
                                <li>By signing, you confirm that you will receive the installment amount.</li>
                                <li>An OTP will be sent to your registered email address.</li>
                                <li>You must enter the OTP to complete the signing process.</li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('student.payments.submit-sign') }}">
                            @csrf
                            <input type="hidden" name="installment_id" value="{{ $installment->id }}">

                            <div class="form-group">
                                <label>Enter OTP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('otp') is-invalid @enderror"
                                           name="otp"
                                           placeholder="Enter 6-digit OTP sent to your email"
                                           maxlength="6"
                                           required>
                                </div>
                                @error('otp')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">
                                    An OTP has been sent to your registered email address.
                                    <a href="{{ route('student.payments.resend-otp', $installment->id) }}" class="text-primary">
                                        Resend OTP
                                    </a>
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                            <i class="fas fa-check-circle"></i> Confirm & Sign
                                        </button>
                                        <a href="{{ route('student.payments.index') }}" class="btn btn-secondary btn-lg px-4">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle text-primary"></i> How It Works</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon-circle bg-primary text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h6>1. Get OTP</h6>
                                    <p class="text-muted small">An OTP is sent to your registered email.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon-circle bg-success text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <h6>2. Enter OTP</h6>
                                    <p class="text-muted small">Enter the 6-digit OTP to verify your identity.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon-circle bg-info text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <h6>3. Confirm</h6>
                                    <p class="text-muted small">Your signature is submitted for admin approval.</p>
                                </div>
                            </div>
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
</style>
@endpush
