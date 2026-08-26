@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Support Center</h1>
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

        <!-- Create Ticket Form -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle text-primary"></i> Create New Ticket</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('beneficiary.support.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('subject') is-invalid @enderror"
                                       name="subject"
                                       placeholder="Brief summary of your issue"
                                       value="{{ old('subject') }}"
                                       required>
                                @error('subject')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="scholarship" {{ old('category') == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                                    <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment</option>
                                    <option value="installment" {{ old('category') == 'installment' ? 'selected' : '' }}>Installment</option>
                                    <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account</option>
                                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10 col-md-12">
                            <div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          name="message"
                                          rows="4"
                                          placeholder="Describe your issue in detail..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12">
                            <div class="form-group">
                                <label>Priority</label>
                                <select class="form-control @error('priority') is-invalid @enderror" name="priority">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                <i class="fas fa-paper-plane"></i> Submit Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- My Tickets -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-ticket-alt text-primary"></i> My Tickets</h4>
                <div class="card-header-action">
                    <span class="badge badge-primary">{{ $tickets->total() }} Total</span>
                </div>
            </div>
            <div class="card-body">
                @if($tickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>#{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <strong>{{ $ticket->subject }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($ticket->message, 50) }}</small>
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
                                            <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('beneficiary.support.show', $ticket->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($ticket->isOpen())
                                                    <form method="POST" action="{{ route('beneficiary.support.close', $ticket->id) }}" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to close this ticket?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="text-muted small">
                                Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $tickets->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">You have no support tickets.</p>
                        <p>Create a new ticket above to get help.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
@push('styles')
<style>
    .badge {
        font-size: 12px;
        padding: 4px 10px;
    }
</style>
@endpush
