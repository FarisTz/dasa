@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Application Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Applications</div>
        </div>
    </div>

    <div class="section-body">

         <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalApplications ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-secondary">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingApplications ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Submitted</h4>
                        </div>
                        <div class="card-body">
                            {{ $submittedApplications ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Under Review</h4>
                        </div>
                        <div class="card-body">
                            {{ $underReviewApplications ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Approved</h4>
                        </div>
                        <div class="card-body">
                            {{ ($approvedFullApplications ?? 0) + ($approvedPartialApplications ?? 0) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Rejected</h4>
                        </div>
                        <div class="card-body">
                            {{ $rejectedApplications ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        
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

        <div class="card">
            <div class="card-header">
                <h4>All Applications</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.applications.export') }}" class="btn btn-success">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-7">
                        <form method="GET" action="{{ route('admin.applications.index') }}" id="searchForm">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="search"
                                       placeholder="Search by applicant name, email, index number..."
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    @if(request('search') || request('status') || request('scholarship') || request('date_from') || request('date_to'))
                                        <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex justify-content-end flex-wrap">
                            <!-- Filter Dropdown -->
                            <div class="dropdown mr-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu dropdown-menu-right p-3" style="min-width: 280px;">
                                    <form method="GET" action="{{ route('admin.applications.index') }}" id="filterForm">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" onchange="this.form.submit()">
                                                <option value="">All Status</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                                <option value="approved_full" {{ request('status') == 'approved_full' ? 'selected' : '' }}>Approved Full</option>
                                                <option value="approved_partial" {{ request('status') == 'approved_partial' ? 'selected' : '' }}>Approved Partial</option>
                                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Scholarship</label>
                                            <select class="form-control" name="scholarship" onchange="this.form.submit()">
                                                <option value="">All Scholarships</option>
                                                @foreach($scholarships as $scholarship)
                                                    <option value="{{ $scholarship->id }}" {{ request('scholarship') == $scholarship->id ? 'selected' : '' }}>
                                                        {{ $scholarship->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Date Range</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From" onchange="this.form.submit()">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" placeholder="To" onchange="this.form.submit()">
                                                </div>
                                            </div>
                                        </div>
                                        @if(request('status') || request('scholarship') || request('date_from') || request('date_to'))
                                            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary btn-sm btn-block">
                                                <i class="fas fa-undo"></i> Reset Filters
                                            </a>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <!-- Bulk Actions -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tasks"></i> Bulk Actions
                                </button>
                                <div class="dropdown-menu dropdown-menu-right p-3" style="min-width: 200px;">
                                    <form method="POST" action="{{ route('admin.applications.bulk-action') }}" id="bulkActionForm">
                                        @csrf
                                        <input type="hidden" name="action" id="bulkActionType">
                                        <div class="form-group">
                                            <label>Select Action</label>
                                            <select class="form-control" id="bulkActionSelect">
                                                <option value="">Choose action...</option>
                                                <option value="under_review">Mark as Under Review</option>
                                                <option value="approved_full">Approve Full</option>
                                                <option value="approved_partial">Approve Partial</option>
                                                <option value="rejected">Reject</option>
                                                <option value="delete">Delete</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-block" onclick="return confirmBulkAction()">
                                            Apply to Selected
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Summary -->
                <div class="row mb-3">
                    <div class="col-12">
                        <span class="text-muted">
                            Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} applications
                            @if(request('search'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-search"></i> "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-tag"></i> {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                                </span>
                            @endif
                            @if(request('scholarship'))
                                @php
                                    $scholarship = $scholarships->firstWhere('id', request('scholarship'));
                                @endphp
                                @if($scholarship)
                                    <span class="badge badge-info ml-2">
                                        <i class="fas fa-award"></i> {{ $scholarship->title }}
                                    </span>
                                @endif
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Applications Table -->
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
                                <th>
                                    <a href="{{ route('admin.applications.index', array_merge(request()->query(), ['sort' => 'user_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Applicant
                                        @if(request('sort') == 'user_id')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Scholarship</th>
                                <th>Index Number</th>
                                <th>
                                    <a href="{{ route('admin.applications.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Status
                                        @if(request('sort') == 'status')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('admin.applications.index', array_merge(request()->query(), ['sort' => 'submitted_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Submitted
                                        @if(request('sort') == 'submitted_at')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Review Duration</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input application-checkbox" id="app_{{ $application->id }}" value="{{ $application->id }}">
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
                                        @php
                                            $indexNumber = null;
                                            if($application->user->oLevelEducation) {
                                                $indexNumber = $application->user->oLevelEducation->form_four_index_number;
                                            }
                                        @endphp
                                        {{ $indexNumber ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $application->status == 'submitted' ? 'primary' :
                                            ($application->status == 'pending' ? 'secondary' :
                                            ($application->status == 'under_review' ? 'warning' :
                                            ($application->status == 'approved_full' ? 'success' :
                                            ($application->status == 'approved_partial' ? 'info' : 'danger')))) }} badge-lg">
                                            <i class="fas fa-{{ $application->status == 'submitted' ? 'clock' :
                                                ($application->status == 'pending' ? 'hourglass-half' :
                                                ($application->status == 'under_review' ? 'search' :
                                                ($application->status == 'approved_full' ? 'check-circle' :
                                                ($application->status == 'approved_partial' ? 'check-circle' : 'times-circle')))) }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($application->submitted_at)
                                            <div>{{ $application->submitted_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $application->submitted_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Not submitted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->submitted_at)
                                            @php
                                                $days = $application->submitted_at->diffInDays(now());
                                            @endphp
                                            <span class="badge badge-{{ $days > 30 ? 'danger' : ($days > 15 ? 'warning' : 'info') }}">
                                                {{ $days }} days
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.applications.show', $application->id) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="View Application">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.applications.edit', $application->id) }}"
                                               class="btn btn-sm btn-warning"
                                               data-toggle="tooltip"
                                               title="Edit Application">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($application->status != 'approved_full' && $application->status != 'approved_partial' && $application->status != 'rejected')
                                                <a href="{{ route('admin.applications.review', $application->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   data-toggle="tooltip"
                                                   title="Review Application">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            @endif
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $application->id }}"
                                                    data-toggle="tooltip"
                                                    title="Delete Application">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $application->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this application?</p>
                                                        <p><strong>Applicant:</strong> {{ $application->user->name }}</p>
                                                        <p><strong>Scholarship:</strong> {{ $application->scholarship->title ?? 'N/A' }}</p>
                                                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <h5>No Applications Found</h5>
                                            <p class="text-muted">
                                                @if(request('search') || request('status') || request('scholarship') || request('date_from') || request('date_to'))
                                                    No applications match your search criteria.
                                                    <br>
                                                    <a href="{{ route('admin.applications.index') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-undo"></i> Clear Filters
                                                    </a>
                                                @else
                                                    There are no applications submitted yet.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="text-muted small">
                            Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} entries
                            <br>
                            Page {{ $applications->currentPage() }} of {{ $applications->lastPage() }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <div class="btn-group mr-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{ $applications->perPage() }} per page
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.applications.index', array_merge(request()->query(), ['per_page' => 10])) }}">10 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.applications.index', array_merge(request()->query(), ['per_page' => 25])) }}">25 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.applications.index', array_merge(request()->query(), ['per_page' => 50])) }}">50 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.applications.index', array_merge(request()->query(), ['per_page' => 100])) }}">100 per page</a>
                                </div>
                            </div>
                            {{ $applications->appends(request()->query())->links() }}
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
    .table td {
        vertical-align: middle;
    }
    .card-statistic-1 {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
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
        font-size: 13px;
        padding: 6px 12px;
    }
    .pagination {
        margin-bottom: 0;
    }
    .dropdown-menu {
        max-height: 400px;
        overflow-y: auto;
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

        // Auto-submit on filter change
        $('#filterForm select').on('change', function() {
            this.form.submit();
        });

        // Search with enter key
        $('#searchForm input').on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                this.form.submit();
            }
        });

        // Tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Bulk action confirmation
        window.confirmBulkAction = function() {
            const action = $('#bulkActionSelect').val();
            const selected = $('.application-checkbox:checked').length;

            if (!action) {
                alert('Please select an action.');
                return false;
            }

            if (selected === 0) {
                alert('Please select at least one application.');
                return false;
            }

            const actionLabels = {
                'under_review': 'Mark as Under Review',
                'approved_full': 'Approve Full',
                'approved_partial': 'Approve Partial',
                'rejected': 'Reject',
                'delete': 'Delete'
            };

            return confirm(`Are you sure you want to ${actionLabels[action]} ${selected} application(s)?`);
        };

        // Bulk action form submission
        $('#bulkActionSelect').on('change', function() {
            $('#bulkActionType').val(this.value);
        });

        // Update status via dropdown (quick action)
        $('.status-update').on('change', function() {
            const applicationId = $(this).data('id');
            const status = $(this).val();

            if (status) {
                $.ajax({
                    url: '{{ route("admin.applications.update-status") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: applicationId,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Failed to update status.');
                        }
                    },
                    error: function() {
                        alert('An error occurred.');
                    }
                });
            }
        });
    });
</script>
@endpush
