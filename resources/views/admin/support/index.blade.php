@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Support Tickets</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Support</div>
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
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['total'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Open</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['open'] }}
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
                            <h4>Resolved</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['resolved'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-secondary">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Closed</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['closed'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Urgent</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['urgent'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list"></i> All Tickets</h4>
                <div class="card-header-action">
                    <form method="GET" action="{{ route('admin.support.index') }}" class="form-inline">
                        <input type="text" class="form-control mr-2" name="search" placeholder="Search..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.support.bulk-action') }}" id="bulkActionForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" name="action" id="bulkAction" required>
                                <option value="">Bulk Action</option>
                                <option value="resolve">Resolve</option>
                                <option value="close">Close</option>
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
                                    <th>User</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input ticket-checkbox" id="ticket_{{ $ticket->id }}" value="{{ $ticket->id }}" name="ticket_ids[]">
                                                <label class="custom-control-label" for="ticket_{{ $ticket->id }}"></label>
                                            </div>
                                        </td>
                                        <td>#{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $ticket->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $ticket->user->name }}"
                                                     class="rounded-circle mr-2"
                                                     width="30"
                                                     height="30">
                                                <div>
                                                    <div>{{ $ticket->user->name }}</div>
                                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->subject }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($ticket->message, 40) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $ticket->category_color }}">
                                                {{ ucfirst($ticket->category) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $ticket->priority_color }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $ticket->status_color }}">
                                                <i class="fas fa-{{ $ticket->status_icon }}"></i>
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($ticket->assigned_to)
                                                {{ $ticket->assignedTo->name ?? 'Unknown' }}
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </div>
                                                <h5>No Tickets Found</h5>
                                                <p class="text-muted">No support tickets available.</p>
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
                            Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $tickets->appends(request()->query())->links() }}
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
        text-transform: uppercase;
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
        $('#selectAll').on('change', function() {
            $('.ticket-checkbox').prop('checked', this.checked);
        });

        $('.ticket-checkbox').on('change', function() {
            if ($('.ticket-checkbox:checked').length === $('.ticket-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    });

    function confirmBulkAction() {
        const action = $('#bulkAction').val();
        const selected = $('.ticket-checkbox:checked').length;

        if (!action) {
            alert('Please select an action.');
            return false;
        }

        if (selected === 0) {
            alert('Please select at least one ticket.');
            return false;
        }

        return confirm(`Are you sure you want to ${action} ${selected} ticket(s)?`);
    }
</script>
@endpush
