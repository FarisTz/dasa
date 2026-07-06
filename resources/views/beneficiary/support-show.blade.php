@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Ticket Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('beneficiary.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('beneficiary.support') }}">Support</a></div>
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
                    <span class="badge badge-{{ $ticket->priority_color }} badge-lg ml-2">
                        <i class="fas fa-flag"></i>
                        {{ ucfirst($ticket->priority) }}
                    </span>
                    <a href="{{ route('beneficiary.support') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $ticket->subject }}</h5>
                        <p class="text-muted">
                            <i class="fas fa-user"></i> {{ $ticket->user->name }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-tag"></i> {{ ucfirst($ticket->category) }}
                            <span class="mx-2">|</span>
                            <i class="fas fa-calendar-alt"></i> {{ $ticket->created_at->format('F d, Y H:i A') }}
                        </p>
                        <div class="bg-light p-4 rounded mt-3" style="white-space: pre-wrap; line-height: 1.8;">
                            {{ $ticket->message }}
                        </div>

                        @if($ticket->assigned_to)
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-user-check"></i>
                                    Assigned to: {{ $ticket->assignedTo->name ?? 'Unknown' }}
                                </small>
                            </div>
                        @endif

                        @if($ticket->resolved_at)
                            <div class="mt-2">
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
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6>Ticket Information</h6>
                                <hr>
                                <p class="mb-1"><strong>Category:</strong> {{ ucfirst($ticket->category) }}</p>
                                <p class="mb-1"><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                                <p class="mb-1"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                                <p class="mb-1"><strong>Created:</strong> {{ $ticket->created_at->diffForHumans() }}</p>
                                <p class="mb-0"><strong>Replies:</strong> {{ $ticket->replies->count() }}</p>

                                @if($ticket->isOpen())
                                    <hr>
                                    <a href="{{ route('beneficiary.support.close', $ticket->id) }}"
                                       class="btn btn-secondary btn-block"
                                       onclick="return confirm('Are you sure you want to close this ticket?')">
                                        <i class="fas fa-times"></i> Close Ticket
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
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
                                                <span class="badge badge-secondary ml-2">You</span>
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

        <!-- Reply Form -->
        @if($ticket->isOpen())
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-reply text-primary"></i> Add Reply</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('beneficiary.support.reply', $ticket->id) }}">
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Reply
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                This ticket is <strong>{{ ucfirst($ticket->status) }}</strong>. You cannot add replies to closed or resolved tickets.
            </div>
        @endif
    </div>
</section>

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
