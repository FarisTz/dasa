@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create New Scholarship</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.scholarships.index') }}">Scholarships</a></div>
            <div class="breadcrumb-item">Create Scholarship</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-award"></i> Add New Scholarship</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.scholarships.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6 col-md-12">
                            <!-- Title -->
                            <div class="form-group">
                                <label>Scholarship Title <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           name="title"
                                           placeholder="Enter scholarship title"
                                           value="{{ old('title') }}"
                                           required>
                                </div>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Give your scholarship a clear and descriptive title.</small>
                            </div>

                            <!-- Academic Year -->
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
                                            $startYear = $currentYear - 5;
                                            $endYear = $currentYear + 5;
                                        @endphp
                                        @for($year = $endYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}-{{ $year + 1 }}" {{ old('academic_year') == $year . '-' . ($year + 1) ? 'selected' : '' }}>
                                                {{ $year }}-{{ $year + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('academic_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Select the academic year for this scholarship.</small>
                            </div>

                            <!-- Deadline -->
                            <div class="form-group">
                                <label>Application Deadline <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <input type="date"
                                           class="form-control @error('deadline') is-invalid @enderror"
                                           name="deadline"
                                           value="{{ old('deadline') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           required>
                                </div>
                                @error('deadline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">The deadline must be at least one day from today.</small>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6 col-md-12">
                            <!-- Description -->
                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="align-items: flex-start; padding-top: 12px;">
                                            <i class="fas fa-align-left"></i>
                                        </div>
                                    </div>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              name="description"
                                              rows="6"
                                              placeholder="Describe the scholarship program..."
                                              required>{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Provide a detailed description of the scholarship including benefits, requirements, and duration.</small>
                            </div>

                            <!-- Eligibility Criteria -->
                            <div class="form-group">
                                <label>Eligibility Criteria <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="align-items: flex-start; padding-top: 12px;">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <textarea class="form-control @error('eligibility_criteria') is-invalid @enderror"
                                              name="eligibility_criteria"
                                              rows="6"
                                              placeholder="List the eligibility criteria..."
                                              required>{{ old('eligibility_criteria') }}</textarea>
                                </div>
                                @error('eligibility_criteria')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">List all eligibility requirements applicants must meet.</small>
                            </div>
                        </div>
                    </div>

                  

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Create Scholarship
                                </button>
                                <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary btn-lg px-4">
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
    .form-group .input-group-text {
        min-width: 42px;
        justify-content: center;
    }
    .badge {
        font-size: 12px;
        padding: 4px 10px;
    }
    .preview-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 15px;
    }
    .preview-card p {
        margin-bottom: 8px;
    }
    .preview-card strong {
        color: #495057;
        min-width: 120px;
        display: inline-block;
    }
    #previewDescription, #previewEligibility {
        white-space: pre-wrap;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.6;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    textarea {
        resize: vertical;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time preview
        const titleInput = document.querySelector('input[name="title"]');
        const academicYearSelect = document.querySelector('select[name="academic_year"]');
        const deadlineInput = document.querySelector('input[name="deadline"]');
        const statusSelect = document.querySelector('select[name="status"]');
        const descriptionTextarea = document.querySelector('textarea[name="description"]');
        const eligibilityTextarea = document.querySelector('textarea[name="eligibility_criteria"]');

        const previewTitle = document.getElementById('previewTitle');
        const previewAcademicYear = document.getElementById('previewAcademicYear');
        const previewDeadline = document.getElementById('previewDeadline');
        const previewStatus = document.getElementById('previewStatus');
        const previewDescription = document.getElementById('previewDescription');
        const previewEligibility = document.getElementById('previewEligibility');

        // Update preview on input
        function updatePreview() {
            // Title
            previewTitle.textContent = titleInput.value || 'Not set';

            // Academic Year
            previewAcademicYear.textContent = academicYearSelect.value || 'Not set';

            // Deadline
            if (deadlineInput.value) {
                const date = new Date(deadlineInput.value + 'T00:00:00');
                previewDeadline.textContent = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } else {
                previewDeadline.textContent = 'Not set';
            }

            // Status with badge
            const statusText = statusSelect.value ? statusSelect.options[statusSelect.selectedIndex].text : 'Not set';
            const statusClass = statusSelect.value === 'open' ? 'badge-success' :
                               (statusSelect.value === 'draft' ? 'badge-warning' :
                               (statusSelect.value === 'closed' ? 'badge-danger' : 'badge-secondary'));
            previewStatus.innerHTML = statusSelect.value ?
                `<span class="badge ${statusClass}">${statusText}</span>` :
                'Not set';

            // Description
            previewDescription.innerHTML = descriptionTextarea.value ||
                '<span class="text-muted">Description will appear here</span>';

            // Eligibility
            previewEligibility.innerHTML = eligibilityTextarea.value ||
                '<span class="text-muted">Eligibility criteria will appear here</span>';
        }

        // Add event listeners
        titleInput.addEventListener('input', updatePreview);
        academicYearSelect.addEventListener('change', updatePreview);
        deadlineInput.addEventListener('change', updatePreview);
        statusSelect.addEventListener('change', updatePreview);
        descriptionTextarea.addEventListener('input', updatePreview);
        eligibilityTextarea.addEventListener('input', updatePreview);

        // Initial preview update if there are old values
        if (document.querySelector('form').dataset.hasOldValues === 'true') {
            updatePreview();
        }

        // Set minimum date for deadline
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];
        deadlineInput.setAttribute('min', minDate);

        // Status change confirmation
        statusSelect.addEventListener('change', function() {
            if (this.value === 'closed') {
                if (!confirm('Are you sure you want to set this scholarship to "Closed"? This will prevent new applications.')) {
                    this.value = 'draft';
                }
            }
        });
    });
</script>
@endpush
