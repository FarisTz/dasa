@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Installment Management</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Installments</div>
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
                <h4>All Installments</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.installments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Installment
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Inst. Number</th>
                                <th>Academic Year</th>
                                <th>Student Year</th>
                                <th>Amount</th>
                                <th>Release Date</th>
                                <th>Status</th>
                                <th>Signed</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($installments as $installment)
                                <tr>
                                    <td>{{ $installments->firstItem() + $loop->index }}</td>
                                    <td><strong>{{ $installment->inst_number }}</strong></td>
                                    <td>{{ $installment->academic_year }}</td>
                                    <td>Year {{ $installment->student_year }}</td>
                                    <td><strong>TSh {{ number_format($installment->amount, 2) }}</strong></td>
                                    <td>
                                        @if($installment->release_date)
                                            {{ $installment->release_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $installment->is_active ? 'success' : 'danger' }}">
                                            {{ $installment->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $installment->signed_count }} / {{ $installment->total_students }}
                                        </span>
                                    </td>
                                    <td>{{ $installment->creator->name ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.installments.show', $installment->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.installments.edit', $installment->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.installments.toggle-status', $installment->id) }}"
                                               class="btn btn-sm btn-{{ $installment->is_active ? 'danger' : 'success' }}"
                                               onclick="return confirm('Are you sure you want to {{ $installment->is_active ? 'deactivate' : 'activate' }} this installment?')">
                                                <i class="fas fa-{{ $installment->is_active ? 'times' : 'check' }}"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $installment->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $installment->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete installment <strong>{{ $installment->inst_number }}</strong>?</p>
                                                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.installments.destroy', $installment->id) }}" method="POST">
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
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <h5>No Installments Found</h5>
                                            <p class="text-muted">Create your first installment to get started.</p>
                                            <a href="{{ route('admin.installments.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus"></i> Add Installment
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="text-muted small">
                            Showing {{ $installments->firstItem() ?? 0 }} to {{ $installments->lastItem() ?? 0 }} of {{ $installments->total() }} entries
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $installments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
