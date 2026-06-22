@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Scholarship Acknowledgement</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Acknowledgement</div>
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

        <!-- Application Status Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-info-circle text-primary"></i> Application Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Application ID</th>
                                        <td>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Scholarship</th>
                                        <td>{{ $application->scholarship->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Academic Year</th>
                                        <td>{{ $application->scholarship->academic_year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Award Status</th>
                                        <td>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $application->status == 'approved_full' ? 'Full Scholarship' : 'Partial Scholarship' }}
                                            </span>
                                        </td>
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
                                        <th width="40%">Acknowledgement Status</th>
                                        <td>
                                            <span class="badge badge-{{ $application->acknowledgement_status_color }}">
                                                <i class="fas fa-{{ $application->acknowledgement_status_icon }}"></i>
                                                {{ ucfirst($application->acknowledgement_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @if($application->acknowledgement_letter_submitted_at)
                                        <tr>
                                            <th>Submitted Date</th>
                                            <td>
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $application->acknowledgement_letter_submitted_at->format('F d, Y H:i A') }}
                                                <br>
                                                <small class="text-muted">{{ $application->acknowledgement_letter_submitted_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($application->acknowledgement_letter_path)
                                        <tr>
                                            <th>Uploaded File</th>
                                            <td>
                                                <a href="{{ asset('storage/' . $application->acknowledgement_letter_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-pdf"></i> View Uploaded Letter
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acknowledgement Letter Section -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-file-signature text-primary"></i> Acknowledgement Letter</h4>
            </div>
            <div class="card-body">
                @if($application->acknowledgement_status == 'pending')
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Action Required:</strong> Please download the acknowledgement letter template, sign it, and upload the signed copy.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                    <h5>Step 1: Download Template</h5>
                                    <p class="text-muted">Download the acknowledgement letter template.</p>
                                    <a href="{{ route('applicant.acknowledgement-letter.download') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-file-word"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-upload fa-3x text-success mb-3"></i>
                                    <h5>Step 2: Upload Signed Letter</h5>
                                    <p class="text-muted">Upload your signed acknowledgement letter.</p>
                                    <form method="POST" action="{{ route('applicant.acknowledgement-letter.submit') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('acknowledgement_letter') is-invalid @enderror"
                                                       id="acknowledgement_letter"
                                                       name="acknowledgement_letter"
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                       required>
                                                <label class="custom-file-label" for="acknowledgement_letter">Choose file...</label>
                                            </div>
                                            @error('acknowledgement_letter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <small class="text-muted">Allowed: PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-paper-plane"></i> Submit Acknowledgement
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($application->acknowledgement_status == 'submitted')
                    <div class="alert alert-info text-center">
                        <i class="fas fa-clock fa-3x"></i>
                        <h5 class="mt-3">Your acknowledgement letter is under review</h5>
                        <p>Our team is reviewing your submission. You will be notified once it's approved.</p>
                        @if($application->acknowledgement_letter_path)
                            <a href="{{ asset('storage/' . $application->acknowledgement_letter_path) }}" target="_blank" class="btn btn-primary mt-2">
                                <i class="fas fa-eye"></i> View Submitted Letter
                            </a>
                        @endif
                    </div>
                @elseif($application->acknowledgement_status == 'approved')
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-3x"></i>
                        <h5 class="mt-3">Acknowledgement Letter Approved!</h5>
                        <p>Your acknowledgement letter has been reviewed and approved.</p>
                        @if($application->acknowledgement_letter_path)
                            <a href="{{ asset('storage/' . $application->acknowledgement_letter_path) }}" target="_blank" class="btn btn-success mt-2">
                                <i class="fas fa-file-pdf"></i> View Approved Letter
                            </a>
                        @endif
                    </div>
                @elseif($application->acknowledgement_status == 'rejected')
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-times-circle fa-3x"></i>
                        <h5 class="mt-3">Acknowledgement Letter Rejected</h5>
                        <p>Your acknowledgement letter was rejected. Please review the feedback below and resubmit.</p>
                        @if($application->acknowledgement_admin_notes)
                            <div class="alert alert-warning mt-3">
                                <strong>Feedback:</strong>
                                <p class="mb-0">{{ $application->acknowledgement_admin_notes }}</p>
                            </div>
                        @endif
                        <a href="{{ route('applicant.acknowledgement.index') }}" class="btn btn-warning mt-2">
                            <i class="fas fa-redo"></i> Resubmit Acknowledgement
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-info-circle text-primary"></i> Important Instructions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="icon-circle bg-primary text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-file-word"></i>
                            </div>
                            <h6>1. Download Template</h6>
                            <p class="text-muted small">Download the acknowledgement letter template.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="icon-circle bg-success text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-pen"></i>
                            </div>
                            <h6>2. Sign the Letter</h6>
                            <p class="text-muted small">Print, sign, and scan the letter or sign digitally.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="icon-circle bg-info text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-upload"></i>
                            </div>
                            <h6>3. Upload Signed Copy</h6>
                            <p class="text-muted small">Upload the signed document to complete the process.</p>
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
    .custom-file-label::after {
        content: "Browse";
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Update file input label
        $('input[type="file"]').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endpush
