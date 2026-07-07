@extends('layouts.app')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>User Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Users</div>
        </div>
    </div>

    <div class="section-body">

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Users</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalUsers ?? $users->total() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Active Users</h4>
                        </div>
                        <div class="card-body">
                            {{ $activeUsers ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pending Applications</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingApplications ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Inactive Users</h4>
                        </div>
                        <div class="card-body">
                            {{ $inactiveUsers ?? 0 }}
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
                <h4>All Users</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="search"
                                       placeholder="Search by name, email, phone, or ID..."
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
                                    <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="form-control" name="role" onchange="this.form.submit()">
                                                <option value="">All Roles</option>
                                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="applicant" {{ request('role') == 'applicant' ? 'selected' : '' }}>Applicant</option>
                                                <option value="reviewer" {{ request('role') == 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" onchange="this.form.submit()">
                                                <option value="">All Status</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                        </div>
                                        @if(request('role') || request('status'))
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm btn-block">
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
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                            @if(request('search'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-search"></i> "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('role'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-user-tag"></i> {{ ucfirst(request('role')) }}
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="badge badge-info ml-2">
                                    <i class="fas fa-circle"></i> {{ ucfirst(request('status')) }}
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>
                                    <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Name
                                        @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Application Status</th>
                                <th>
                                    <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="text-dark">
                                        Registered
                                        @if(request('sort') == 'created_at')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort text-muted"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id == auth()->id())
                                                    <span class="badge badge-warning ml-1">You</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    </td>
                                    <td>{{ $user->phone_number ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'reviewer' ? 'warning' : 'info') }}">
                                            {{ ucfirst($user->role ?? 'applicant') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->application_status)
                                            <span class="badge badge-{{ $user->application_status == 'submitted' ? 'primary' :
                                                ($user->application_status == 'under_review' ? 'warning' :
                                                ($user->application_status == 'approved' ? 'success' :
                                                ($user->application_status == 'rejected' ? 'danger' : 'secondary'))) }}">
                                                {{ ucfirst(str_replace('_', ' ', $user->application_status)) }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Not Applied</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($user->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="View User">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="btn btn-sm btn-warning"
                                               data-toggle="tooltip"
                                               title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id != auth()->id())
                                                <a href="{{ route('admin.users.destroy', $user->id) }}"
                                                    id="delete"
                                                        class="btn btn-sm btn-danger">

                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h5>No Users Found</h5>
                                            <p class="text-muted">
                                                @if(request('search') || request('role') || request('status'))
                                                    No users match your search criteria.
                                                    <br>
                                                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-undo"></i> Clear Filters
                                                    </a>
                                                @else
                                                    There are no users registered yet.
                                                    <br>
                                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-plus"></i> Add First User
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
                            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                            <br>
                            Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <div class="btn-group mr-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    {{ $users->perPage() }} per page
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.users.index', array_merge(request()->query(), ['per_page' => 10])) }}">10 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.users.index', array_merge(request()->query(), ['per_page' => 25])) }}">25 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.users.index', array_merge(request()->query(), ['per_page' => 50])) }}">50 per page</a>
                                    <a class="dropdown-item" href="{{ route('admin.users.index', array_merge(request()->query(), ['per_page' => 100])) }}">100 per page</a>
                                </div>
                            </div>
                            {{ $users->appends(request()->query())->links() }}
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
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 28px;
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
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
        margin: 0;
    }
    .card-statistic-1 .card-body {
        font-size: 24px;
        font-weight: 700;
        padding: 0;
        color: #2d2d2d;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
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

        // Delete confirmation
        $('.btn-danger[data-toggle="modal"]').on('click', function() {
            const modalId = $(this).data('target');
            $(modalId).modal('show');
        });
    });
</script>
@endpush
