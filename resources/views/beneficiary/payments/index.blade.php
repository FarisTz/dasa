@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>My Installments</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('student.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Installments</div>
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

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Active Installments to Sign -->
        @if($activeInstallments->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-handshake text-success"></i> Available Installments to Sign</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($activeInstallments as $installment)
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $installment->inst_number }}</h5>
                                        <p class="card-text">
                                            <strong>Academic Year:</strong> {{ $installment->academic_year }}<br>
                                            <strong>Student Year:</strong> Year {{ $installment->student_year }}<br>
                                            <strong>Amount:</strong> <span class="text-success font-weight-bold">TSh {{ number_format($installment->amount, 2) }}</span>
                                        </p>
                                        <a href="{{ route('student.payments.sign', $installment->id) }}" class="btn btn-success btn-block">
                                            <i class="fas fa-pen"></i> Sign Installment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- My Payments -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list"></i> My Installment History</h4>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Installment</th>
                                    <th>Academic Year</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Signed Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payments->firstItem() + $loop->index }}</td>
                                        <td><strong>{{ $payment->installment->inst_number ?? 'N/A' }}</strong></td>
                                        <td>{{ $payment->installment->academic_year ?? 'N/A' }}</td>
                                        <td>TSh {{ number_format($payment->amount, 2) }}</td>
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
                                            <a href="{{ route('student.payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="text-muted small">
                                Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} entries
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $payments->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">You have no installment history.</p>
                        @if($activeInstallments->count() > 0)
                            <p>You have active installments available to sign above.</p>
                        @else
                            <p>No installments are currently available for signing.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
