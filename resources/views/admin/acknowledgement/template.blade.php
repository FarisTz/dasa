@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Acknowledgement Letter Template</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.acknowledgement.index') }}">Acknowledgement</a></div>
            <div class="breadcrumb-item">Template</div>
        </div>
    </div>
        <!-- Instructions Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle text-primary"></i> Instructions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon-circle bg-primary text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <h6>1. Upload Template</h6>
                                    <p class="text-muted small">Upload a PDF template for the acknowledgement letter.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon-circle bg-success text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <h6>2. Make Available</h6>
                                    <p class="text-muted small">The template will be available for applicants to download.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon-circle bg-warning text-white mb-3" style="width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;">
                                        <i class="fas fa-sync"></i>
                                    </div>
                                    <h6>3. Update Anytime</h6>
                                    <p class="text-muted small">You can update the template at any time by uploading a new one.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

        <div class="row">
            <!-- Upload Template Card -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-upload text-primary"></i> Upload New Template</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.acknowledgement.upload-template') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>Template File <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input @error('template') is-invalid @enderror"
                                           id="template"
                                           name="template"
                                           accept=".pdf"
                                           required>
                                    <label class="custom-file-label" for="template">Choose PDF file...</label>
                                </div>
                                @error('template')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Allowed: PDF only. Max size: 10MB.</small>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note:</strong> Uploading a new template will <strong>replace</strong> the existing template.
                                @if($templateExists)
                                    <br>
                                    <span class="text-success"><i class="fas fa-check-circle"></i> A template currently exists.</span>
                                @else
                                    <br>
                                    <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> No template currently exists.</span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-upload"></i> Upload Template
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Template Management Card -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-pdf text-danger"></i> Current Template</h4>
                    </div>
                    <div class="card-body text-center">
                        @if($templateExists)
                            <div class="mb-4">
                                <i class="fas fa-file-pdf text-danger" style="font-size: 80px;"></i>
                                <h5 class="mt-3">acknowledgement.pdf</h5>
                                <p class="text-muted">Template is ready for download</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('admin.acknowledgement.download-template') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-download"></i> Download Template
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <form method="POST" action="{{ route('admin.acknowledgement.delete-template') }}" onsubmit="return confirm('Are you sure you want to delete the current template?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-trash"></i> Delete Template
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <i class="fas fa-file-pdf text-muted" style="font-size: 80px;"></i>
                                <h5 class="mt-3">No Template Uploaded</h5>
                                <p class="text-muted">Upload a template to make it available for applicants.</p>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Applicants cannot download the acknowledgement letter template until you upload one.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-arrow-left text-primary"></i> Quick Navigation</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.acknowledgement.index') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-list"></i> View Submissions
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-dashboard"></i> Dashboard
                                </a>
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
    .custom-file-label::after {
        content: "Browse";
    }
    .btn-block {
        padding: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Update file input label
        $('#template').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose PDF file...');
        });
    });
</script>
@endpush
