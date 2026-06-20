@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Scholarship</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.scholarships.index') }}">Scholarships</a></div>
            <div class="breadcrumb-item">Edit Scholarship</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Scholarship: {{ $scholarship->title }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.scholarships.show', $scholarship->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> View Scholarship
                    </a>
                    <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : ($scholarship->status == 'draft' ? 'warning' : 'danger') }} badge-lg ml-2">
                        <i class="fas fa-{{ $scholarship->status == 'open' ? 'door-open' : ($scholarship->status == 'draft' ? 'edit' : 'door-closed') }}"></i>
                        Current: {{ ucfirst($scholarship->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.scholarships.update', $scholarship->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                           value="{{ old('title', $scholarship->title) }}"
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
                                            @php
                                                $yearValue = $year . '-' . ($year + 1);
                                            @endphp
                                            <option value="{{ $yearValue }}" {{ old('academic_year', $scholarship->academic_year) == $yearValue ? 'selected' : '' }}>
                                                {{ $yearValue }}
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
                                           value="{{ old('deadline', $scholarship->deadline ? $scholarship->deadline->format('Y-m-d') : '') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                </div>
                                @error('deadline')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">
                                    Current deadline: <strong>{{ $scholarship->deadline ? $scholarship->deadline->format('F d, Y') : 'Not set' }}</strong>
                                    @if($scholarship->deadline && now()->gt($scholarship->deadline))
                                        <span class="text-danger">(Expired)</span>
                                    @endif
                                </small>
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
                                        <option value="draft" {{ old('status', $scholarship->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="open" {{ old('status', $scholarship->status) == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ old('status', $scholarship->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Created By (Read-only) -->
                            <div class="form-group">
                                <label>Created By</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ $scholarship->creator->name ?? 'Unknown' }}"
                                           disabled>
                                </div>
                                <small class="text-muted">
                                    Created on: {{ $scholarship->created_at->format('F d, Y H:i A') }}
                                    @if($scholarship->created_at != $scholarship->updated_at)
                                        <br>Last updated: {{ $scholarship->updated_at->format('F d, Y H:i A') }}
                                    @endif
                                </small>
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
                                              required>{{ old('description', $scholarship->description) }}</textarea>
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
                                              required>{{ old('eligibility_criteria', $scholarship->eligibility_criteria) }}</textarea>
                                </div>
                                @error('eligibility_criteria')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">List all eligibility requirements applicants must meet.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Section -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <h5><i class="fas fa-chart-bar"></i> Scholarship Statistics</h5>
                            <hr>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Applications</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $scholarship->applications_count ?? $scholarship->applications()->count() }}
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
                                        {{ $scholarship->applications()->where('status', 'pending')->count() }}
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
                                        <h4>Approved</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $scholarship->applications()->whereIn('status', ['approved_full', 'approved_partial'])->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Rejected</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $scholarship->applications()->where('status', 'rejected')->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <h5><i class="fas fa-eye"></i> Live Preview</h5>
                            <hr>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Title:</strong> <span id="previewTitle">{{ $scholarship->title }}</span></p>
                                            <p><strong>Academic Year:</strong> <span id="previewAcademicYear">{{ $scholarship->academic_year }}</span></p>
                                            <p><strong>Deadline:</strong> <span id="previewDeadline">{{ $scholarship->deadline ? $scholarship->deadline->format('F d, Y') : 'Not set' }}</span></p>
                                            <p><strong>Status:</strong>
                                                <span id="previewStatus">
                                                    <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : ($scholarship->status == 'draft' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($scholarship->status) }}
                                                    </span>
                                                </span>
                                            </p>
                                            <p><strong>Created By:</strong> {{ $scholarship->creator->name ?? 'Unknown' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Description:</strong></p>
                                            <div id="previewDescription" class="bg-white p-2 rounded" style="min-height: 60px; max-height: 150px; overflow-y: auto; white-space: pre-wrap;">
                                                {{ $scholarship->description }}
                                            </div>
                                            <p class="mt-2"><strong>Eligibility Criteria:</strong></p>
                                            <div id="previewEligibility" class="bg-white p-2 rounded" style="min-height: 60px; max-height: 150px; overflow-y: auto; white-space: pre-wrap;">
                                                {{ $scholarship->eligibility_criteria }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Update Scholarship
                                </button>
                                <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                @if($scholarship->status != 'closed')
                                    <a href="{{ route('admin.scholarships.toggle-status', $scholarship->id) }}"
                                       class="btn btn-{{ $scholarship->status == 'draft' ? 'success' : 'danger' }} btn-lg px-4"
                                       onclick="return confirm('Are you sure you want to {{ $scholarship->status == 'draft' ? 'open' : 'close' }} this scholarship?')">
                                        <i class="fas fa-{{ $scholarship->status == 'draft' ? 'door-open' : 'door-closed' }}"></i>
                                        {{ $scholarship->status == 'draft' ? 'Open' : 'Close' }} Scholarship
                                    </a>
                                @endif
                                @if($scholarship->applications()->count() == 0)
                                    <button type="button" class="btn btn-danger btn-lg px-4" data-toggle="modal" data-target="#deleteModal">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-lg px-4" disabled data-toggle="tooltip" title="Cannot delete scholarship with existing applications">
                                        <i class="fas fa-trash"></i> Delete (Has Applications)
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
@if($scholarship->applications()->count() == 0)
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <h5>Are you sure you want to delete this scholarship?</h5>
                    <p><strong>{{ $scholarship->title }}</strong></p>
                    <p><strong>Academic Year:</strong> {{ $scholarship->academic_year }}</p>
                    <p class="text-danger"><small>This action cannot be undone. All associated data will be permanently removed.</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.scholarships.destroy', $scholarship->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Scholarship
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
    .card-statistic-1 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        padding: 15px;
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

        // Status change confirmation
        statusSelect.addEventListener('change', function() {
            if (this.value === 'closed') {
                if (!confirm('Are you sure you want to set this scholarship to "Closed"? This will prevent new applications.')) {
                    this.value = '{{ $scholarship->status }}';
                }
            }
        });

        // Tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
