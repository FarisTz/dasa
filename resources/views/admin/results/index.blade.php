@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Academic Results Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Results</div>
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

        <!-- Statistics -->
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Total</h4></div>
                        <div class="card-body">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Pending</h4></div>
                        <div class="card-body">{{ $stats['pending'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Under Review</h4></div>
                        <div class="card-body">{{ $stats['under_review'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Approved</h4></div>
                        <div class="card-body">{{ $stats['approved'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Rejected</h4></div>
                        <div class="card-body">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header"><h4>Suspended</h4></div>
                        <div class="card-body">{{ $stats['suspended'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list"></i> All Results</h4>
                <div class="card-header-action">
                    <form method="GET" action="{{ route('admin.results.index') }}" class="form-inline">
                        <input type="text" class="form-control mr-2" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <select class="form-control mr-2" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.results.bulk-action') }}" id="bulkActionForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" name="action" id="bulkAction" required>
                                <option value="">Bulk Action</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                                <option value="suspend">Suspend</option>
                                <option value="lift_suspension">Lift Suspension</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-primary" onclick="return confirmBulkAction()">
                                <i class="fas fa-tasks"></i> Apply to Selected
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
                                    <th>Student</th>
                                    <th>Academic Year</th>
                                    <th>Student Year</th>
                                    <th>GPA</th>
                                    <th>Status</th>
                                    <th>Suspended</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $result)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input result-checkbox" id="result_{{ $result->id }}" value="{{ $result->id }}" name="result_ids[]">
                                                <label class="custom-control-label" for="result_{{ $result->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $results->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $result->student->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $result->student->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="30"
                                                     height="30">
                                                <div>
                                                    <div>{{ $result->student->name }}</div>
                                                    <small class="text-muted">{{ $result->student->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><strong>{{ $result->academic_year }}</strong></td>
                                        <td>Year {{ $result->student_year }}</td>
                                        <td>{{ $result->formatted_gpa }}</td>
                                        <td>
                                            <span class="badge badge-{{ $result->status_color }}">
                                                <i class="fas fa-{{ $result->status_icon }}"></i>
                                                {{ ucfirst($result->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($result->is_suspended)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-ban"></i> Suspended
                                                </span>
                                            @else
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Active
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $result->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $result->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.results.show', $result->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($result->result_file_path)
                                                    <a href="{{ route('admin.results.download', $result->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <h5>No Results Found</h5>
                                                <p class="text-muted">No academic results available.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="text-muted small">
                            Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} results
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $results->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#selectAll').on('change', function() {
            $('.result-checkbox').prop('checked', this.checked);
        });

        $('.result-checkbox').on('change', function() {
            if ($('.result-checkbox:checked').length === $('.result-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    });

    function confirmBulkAction() {
        const action = $('#bulkAction').val();
        const selected = $('.result-checkbox:checked').length;

        if (!action) {
            alert('Please select an action.');
            return false;
        }

        if (selected === 0) {
            alert('Please select at least one result.');
            return false;
        }

        return confirm(`Are you sure you want to ${action.replace('_', ' ')} ${selected} result(s)?`);
    }
</script>
@endpush
