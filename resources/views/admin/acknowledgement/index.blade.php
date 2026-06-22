@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Acknowledgement Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Acknowledgement Management</div>
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

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Submitted</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalSubmitted }}
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
                            <h4>Pending Review</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingReview }}
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
                            {{ $approved }}
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
                            {{ $rejected }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h4><i class="fas fa-tools"></i> Actions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('admin.acknowledgement.template') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-upload"></i> Upload Template
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.acknowledgement.download-template') }}" class="btn btn-success btn-block">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.acknowledgement.template') }}" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Delete Template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list"></i> Submitted Acknowledgement Letters</h4>
                <div class="card-header-action">
                    <form method="GET" action="{{ route('admin.acknowledgement.index') }}" class="form-inline">
                        <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <button class="btn btn-primary ml-2" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.acknowledgement.bulk-update-type') }}" id="bulkActionForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" name="user_type" id="bulkUserType" required>
                                <option value="">Bulk Update User Type</option>
                                <option value="beneficiary">Beneficiary</option>
                                <option value="applicant">Applicant</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning" onclick="return confirmBulkAction()">
                                <i class="fas fa-users-cog"></i> Apply to Selected
                            </button>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-success" onclick="bulkApprove()">
                                <i class="fas fa-check"></i> Approve Selected
                            </button>
                            <button type="button" class="btn btn-danger" onclick="bulkReject()">
                                <i class="fas fa-times"></i> Reject Selected
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="selectAll">
                                            <label class="custom-control-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th>#</th>
                                    <th>Applicant</th>
                                    <th>Scholarship</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Submitted Date</th>
                                    <th>Letter</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input application-checkbox" id="app_{{ $application->id }}" value="{{ $application->id }}" name="application_ids[]">
                                                <label class="custom-control-label" for="app_{{ $application->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $applications->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $application->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $application->user->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="35"
                                                     height="35">
                                                <div>
                                                    <div><strong>{{ $application->user->name }}</strong></div>
                                                    <small class="text-muted">{{ $application->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $application->scholarship->title ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $application->scholarship->academic_year ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $application->user->role == 'beneficiary' ? 'success' : ($application->user->role == 'admin' ? 'danger' : ($application->user->role == 'coordinator' ? 'warning' : 'info')) }}">
                                                {{ ucfirst($application->user->role ?? 'applicant') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $application->acknowledgement_status_color }}">
                                                <i class="fas fa-{{ $application->acknowledgement_status_icon }}"></i>
                                                {{ ucfirst($application->acknowledgement_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($application->acknowledgement_letter_submitted_at)
                                                <div>{{ $application->acknowledgement_letter_submitted_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $application->acknowledgement_letter_submitted_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->acknowledgement_letter_path)
                                                <a href="{{ route('admin.acknowledgement.view-letter', $application->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Not uploaded</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.acknowledgement.update-user-type', $application->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="user_type" value="beneficiary">
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Make this user a beneficiary?')">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-file-signature"></i>
                                                </div>
                                                <h5>No Acknowledgement Letters Submitted</h5>
                                                <p class="text-muted">No applicants have submitted acknowledgement letters yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="text-muted small">
                            Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} entries
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $applications->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Reject Acknowledgement Letters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.acknowledgement.bulk-reject') }}" id="bulkRejectForm">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject the selected acknowledgement letters?</p>
                    <div class="form-group">
                        <label>Rejection Reason</label>
                        <textarea class="form-control" name="rejection_reason" rows="3" placeholder="Provide a reason for rejection...">Please review and resubmit your acknowledgement letter.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Selected
                    </button>
                </div>
            </form>
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
    .empty-state {
        padding: 40px 0;
    }
    .empty-state-icon {
        font-size: 60px;
        width: 120px;
        height: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f8f9fa;
        margin-bottom: 20px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All checkbox
        $('#selectAll').on('change', function() {
            $('.application-checkbox').prop('checked', this.checked);
        });

        // Update Select All when individual checkboxes change
        $('.application-checkbox').on('change', function() {
            if ($('.application-checkbox:checked').length === $('.application-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    });

    function confirmBulkAction() {
        const selected = $('.application-checkbox:checked').length;
        const userType = $('#bulkUserType').val();

        if (!userType) {
            alert('Please select a user type.');
            return false;
        }

        if (selected === 0) {
            alert('Please select at least one application.');
            return false;
        }

        return confirm(`Are you sure you want to update ${selected} user(s) to ${userType}?`);
    }

    function bulkApprove() {
        const selected = $('.application-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one application.');
            return;
        }

        if (confirm(`Are you sure you want to approve ${selected.length} acknowledgement letter(s)?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.acknowledgement.bulk-approve') }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            selected.each(function() {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'application_ids[]';
                input.value = $(this).val();
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    }

    function bulkReject() {
        const selected = $('.application-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one application.');
            return;
        }

        // Show the modal
        $('#bulkRejectModal').modal('show');

        // Populate the form with selected IDs
        $('#bulkRejectForm').find('input[name="application_ids[]"]').remove();
        selected.each(function() {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'application_ids[]';
            input.value = $(this).val();
            $('#bulkRejectForm').append(input);
        });
    }
</script>
@endpush
