@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Ticket Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.support.index') }}">Support</a></div>
            <div class="breadcrumb-item">Ticket #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</div>
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
            <!-- Main Ticket Info -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-ticket-alt text-primary"></i>
                            Ticket #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                        </h4>
                        <div class="card-header-action">
                            <span class="badge badge-{{ $ticket->status_color }} badge-lg">
                                <i class="fas fa-{{ $ticket->status_icon }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                            <a href="{{ route('admin.support.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5>{{ $ticket->subject }}</h5>
                        <p class="text-muted">
                            <i class="fas fa-user"></i> {{ $ticket->user->name }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-envelope"></i> {{ $ticket->user->email }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-tag"></i> {{ ucfirst($ticket->category) }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-calendar-alt"></i> {{ $ticket->created_at->format('F d, Y H:i A') }}
                        </p>
                        <div class="bg-light p-4 rounded mt-3" style="white-space: pre-wrap; line-height: 1.8;">
                            {{ $ticket->message }}
                        </div>

                        @if($ticket->resolved_at)
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-check-circle"></i>
                                    Resolved on: {{ $ticket->resolved_at->format('F d, Y H:i A') }}
                                </small>
                            </div>
                        @endif

                        @if($ticket->closed_at)
                            <div class="mt-2">
                                <small class="text-secondary">
                                    <i class="fas fa-times-circle"></i>
                                    Closed on: {{ $ticket->closed_at->format('F d, Y H:i A') }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Replies -->
                @if($ticket->replies->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-comments text-primary"></i> Replies ({{ $ticket->replies->count() }})</h4>
                        </div>
                        <div class="card-body">
                            @foreach($ticket->replies as $reply)
                                <div class="reply-item {{ $reply->is_admin ? 'admin-reply' : 'user-reply' }} mb-3">
                                    <div class="d-flex align-items-start">
                                        <img src="{{ $reply->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                             alt="{{ $reply->user->name }}"
                                             class="rounded-circle mr-3"
                                             width="40"
                                             height="40">
                                        <div class="reply-content flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $reply->user->name }}</strong>
                                                    @if($reply->is_admin)
                                                        <span class="badge badge-primary ml-2">Admin</span>
                                                    @else
                                                        <span class="badge badge-secondary ml-2">User</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="bg-light p-3 rounded mt-2" style="white-space: pre-wrap; line-height: 1.8;">
                                                {{ $reply->message }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Admin Reply Form -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-reply text-primary"></i> Add Reply</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.support.reply', $ticket->id) }}">
                            @csrf
                            <div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          name="message"
                                          rows="4"
                                          placeholder="Type your reply here..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Update Status</label>
                                <select class="form-control" name="status">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send Reply
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Ticket Information</h6>
                        <hr>
                        <p class="mb-1"><strong>Category:</strong> {{ ucfirst($ticket->category) }}</p>
                        <p class="mb-1"><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                        <p class="mb-1"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                        <p class="mb-1"><strong>Created:</strong> {{ $ticket->created_at->diffForHumans() }}</p>
                        <p class="mb-1"><strong>Replies:</strong> {{ $ticket->replies->count() }}</p>

                        <hr>
                        <h6>Actions</h6>

                        <!-- Assign Admin -->
                        @if(!$ticket->assigned_to)
                            <form method="POST" action="{{ route('admin.support.assign', $ticket->id) }}" class="mt-2">
                                @csrf
                                <div class="form-group">
                                    <label>Assign to Admin</label>
                                    <select class="form-control" name="assigned_to" required>
                                        <option value="">Select Admin...</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-user-check"></i> Assign Ticket
                                </button>
                            </form>
                        @else
                            <p class="text-success">
                                <i class="fas fa-user-check"></i>
                                Assigned to: {{ $ticket->assignedTo->name ?? 'Unknown' }}
                            </p>
                            <form method="POST" action="{{ route('admin.support.assign', $ticket->id) }}" class="mt-2">
                                @csrf
                                <div class="form-group">
                                    <label>Reassign</label>
                                    <select class="form-control" name="assigned_to">
                                        <option value="">Select Admin...</option>
                                        @foreach($admins as $admin)
                                            @if($admin->id != $ticket->assigned_to)
                                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-exchange-alt"></i> Reassign
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.support.assign', $ticket->id) }}" class="mt-2">
                                @csrf
                                <input type="hidden" name="assigned_to" value="">
                                <button type="submit" class="btn btn-secondary btn-block">
                                    <i class="fas fa-user-slash"></i> Unassign
                                </button>
                            </form>
                        @endif

                        <!-- Status Update -->
                        <form method="POST" action="{{ route('admin.support.status', $ticket->id) }}" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label>Update Status</label>
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('styles')
<style>
    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }
    .reply-item {
        padding: 15px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e9ecef;
    }
    .reply-item.admin-reply {
        background: #f0f7ff;
        border-color: #b8d4f0;
    }
    .reply-item.user-reply {
        background: #f8f9fa;
        border-color: #dee2e6;
    }
    .reply-content {
        max-width: 100%;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush
