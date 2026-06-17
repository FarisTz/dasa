@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Scholarship Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Scholarships</div>
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

        <div class="card">
            <div class="card-header">
                <h4>All Scholarships</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Scholarship
                    </a>
                    <a href="{{ route('admin.scholarships.export') }}" class="btn btn-success">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('admin.scholarships.index') }}" id="searchForm">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="search"
                                       placeholder="Search by title, description, academic year..."
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    @if(request('search') || request('status') || request('academic_year'))
                                        <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu dropdown-menu-right p-3" style="min-width: 250px;">
                                    <form method="GET" action="{{ route('admin.scholarships.index') }}" id="filterForm">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" onchange="this.form.submit()">
                                                <option value="">All Status</option>
                                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Academic Year</label>
                                            <select class="form-control" name="academic_year" onchange="this.form.submit()">
                                                <option value="">All Years</option>
                                                @foreach($academicYears as $year)
                                                    <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if(request('status') || request('academic_year'))
                                            <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary btn-sm btn-block">
                                                <i class="fas fa-undo"></i> Reset Filters
                                            </a>
                                        @endif
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
                            Showing {{ $scholarships->firstItem() ?? 0 }} to {{ $scholarships->lastItem() ?? 0 }} of {{ $scholarships->total() }} scholarships
                            @if(request('search'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-search"></i> "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-tag"></i> {{ ucfirst(request('status')) }}
                                </span>
                            @endif
                            @if(request('academic_year'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-calendar"></i> {{ request('academic_year') }}
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Scholarships Table -->
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
                                    <a href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['sort' => 'title', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Title
                                        @if(request('sort') == 'title')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Academic Year</th>
                                <th>
                                    <a href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['sort' => 'deadline', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Deadline
                                        @if(request('sort') == 'deadline')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Status
                                        @if(request('sort') == 'status')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Created By</th>
                                <th>
                                    <a href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Created
                                        @if(request('sort') == 'created_at')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Applications</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scholarships as $scholarship)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input scholarship-checkbox" id="scholarship_{{ $scholarship->id }}" value="{{ $scholarship->id }}">
                                            <label class="custom-control-label" for="scholarship_{{ $scholarship->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $scholarships->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $scholarship->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($scholarship->description, 60) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $scholarship->academic_year }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ now()->gt($scholarship->deadline) ? 'danger' : 'success' }}">
                                            {{ $scholarship->deadline->format('M d, Y') }}
                                            @if(now()->gt($scholarship->deadline))
                                                <i class="fas fa-exclamation-circle"></i>
                                            @else
                                                <i class="fas fa-clock"></i> {{ $scholarship->deadline->diffForHumans() }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $scholarship->status == 'open' ? 'success' : ($scholarship->status == 'draft' ? 'warning' : 'danger') }} badge-lg">
                                            <i class="fas fa-{{ $scholarship->status == 'open' ? 'door-open' : ($scholarship->status == 'draft' ? 'edit' : 'door-closed') }}"></i>
                                            {{ ucfirst($scholarship->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $scholarship->creator->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                 alt="{{ $scholarship->creator->name }}"
                                                 class="rounded-circle mr-2"
                                                 width="25"
                                                 height="25">
                                            <small>{{ $scholarship->creator->name }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $scholarship->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $scholarship->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <i class="fas fa-file-alt"></i> {{ $scholarship->applications_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.scholarships.show', $scholarship->id) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="View Scholarship">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.scholarships.edit', $scholarship->id) }}"
                                               class="btn btn-sm btn-warning"
                                               data-toggle="tooltip"
                                               title="Edit Scholarship">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($scholarship->status != 'closed')
                                                <a href="{{ route('admin.scholarships.toggle-status', $scholarship->id) }}"
                                                   class="btn btn-sm btn-{{ $scholarship->status == 'draft' ? 'success' : 'danger' }}"
                                                   data-toggle="tooltip"
                                                   title="{{ $scholarship->status == 'draft' ? 'Open' : 'Close' }} Scholarship"
                                                   onclick="return confirm('Are you sure you want to {{ $scholarship->status == 'draft' ? 'open' : 'close' }} this scholarship?')">
                                                    <i class="fas fa-{{ $scholarship->status == 'draft' ? 'door-open' : 'door-closed' }}"></i>
                                                </a>
                                            @endif
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $scholarship->id }}"
                                                    data-toggle="tooltip"
                                                    title="Delete Scholarship">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $scholarship->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this scholarship?</p>
                                                        <p><strong>Title:</strong> {{ $scholarship->title }}</p>
                                                        <p><strong>Academic Year:</strong> {{ $scholarship->academic_year }}</p>
                                                        <p class="text-danger"><small>This action cannot be undone. All associated applications will also be deleted.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.scholarships.destroy', $scholarship->id) }}" method="POST">
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
                                    <td colspan="10" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-award"></i>
                                            </div>
                                            <h5>No Scholarships Found</h5>
                                            <p class="text-muted">
                                                @if(request('search') || request('status') || request('academic_year'))
                                                    No scholarships match your search criteria.
                                                    <br>
                                                    <a href="{{ route('admin.scholarships.index') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-undo"></i> Clear Filters
                                                    </a>
                                                @else
                                                    There are no scholarships created yet.
                                                    <br>
                                                    <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-plus"></i> Create First Scholarship
                                                    </a>
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
                            Showing {{ $scholarships->firstItem() ?? 0 }} to {{ $scholarships->lastItem() ?? 0 }} of {{ $scholarships->total() }} entries
                            <br>
                            Page {{ $scholarships->currentPage() }} of {{ $scholarships->lastPage() }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <div class="btn-group mr-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{ $scholarships->perPage() }} per page
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['per_page' => 10])) }}">10 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['per_page' => 25])) }}">25 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['per_page' => 50])) }}">50 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.scholarships.index', array_merge(request()->query(), ['per_page' => 100])) }}">100 per page</a>
                                </div>
                            </div>
                            {{ $scholarships->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Scholarships</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalScholarships ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Open Scholarships</h4>
                        </div>
                        <div class="card-body">
                            {{ $openScholarships ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Draft</h4>
                        </div>
                        <div class="card-body">
                            {{ $draftScholarships ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Closed</h4>
                        </div>
                        <div class="card-body">
                            {{ $closedScholarships ?? 0 }}
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
    .btn-group .btn {
        margin: 0 2px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All checkbox
        $('#selectAll').on('change', function() {
            $('.scholarship-checkbox').prop('checked', this.checked);
        });

        // Update Select All when individual checkboxes change
        $('.scholarship-checkbox').on('change', function() {
            if ($('.scholarship-checkbox:checked').length === $('.scholarship-checkbox').length) {
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
            const selected = $('.scholarship-checkbox:checked').length;

            if (!action) {
                alert('Please select an action.');
                return false;
            }

            if (selected === 0) {
                alert('Please select at least one scholarship.');
                return false;
            }

            const actionLabels = {
                'open': 'Open',
                'close': 'Close',
                'delete': 'Delete'
            };

            return confirm(`Are you sure you want to ${actionLabels[action]} ${selected} scholarship(s)?`);
        };

        // Bulk action form submission
        $('#bulkActionSelect').on('change', function() {
            $('#bulkActionType').val(this.value);
        });
    });
</script>
@endpush
