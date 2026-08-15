@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>My Academic Results</h1>
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

        <!-- Suspension Warning -->
        @if($isSuspended)
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>You are currently suspended from receiving payments.</strong>
                <p class="mb-0 mt-2">Please contact the administrator for more information.</p>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-upload text-primary"></i> Upload New Result</h5>
                        <p class="text-muted">Upload your academic results for the current year.</p>
                        @if($isSuspended)
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-ban"></i> Suspended - Cannot Upload
                            </button>
                        @else
                            <a href="{{ route('beneficiary.results.create') }}" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Result
                            </a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Total Results</small>
                                        <h5 class="font-weight-bold">{{ $results->total() }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Latest Status</small>
                                        @if($latestResult)
                                            <span class="badge badge-{{ $latestResult->status_color }} d-block mt-1">
                                                {{ ucfirst($latestResult->status) }}
                                            </span>
                                        @else
                                            <span class="text-muted">No results</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-list"></i> My Results</h4>
            </div>
            <div class="card-body">
                @if($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Academic Year</th>
                                    <th>Student Year</th>
                                    <th>GPA</th>

                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td>{{ $results->firstItem() + $loop->index }}</td>
                                        <td><strong>{{ $result->academic_year }}</strong></td>
                                        <td>Year {{ $result->student_year }}</td>
                                        <td>{{ $result->formatted_gpa }}</td>
                                       
                                        <td>
                                            <span class="badge badge-{{ $result->status_color }}">
                                                <i class="fas fa-{{ $result->status_icon }}"></i>
                                                {{ ucfirst($result->status) }}
                                            </span>
                                            @if($result->is_suspended)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-ban"></i> Suspended
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $result->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $result->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('beneficiary.results.show', $result->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($result->result_file_path)
                                                    <a href="{{ route('beneficiary.results.download', $result->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                                @if($result->status == 'pending')
                                                    <form method="POST" action="{{ route('beneficiary.results.destroy', $result->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this result?')">
                                                            <i class="fas fa-trash"></i>
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
                                Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} results
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $results->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No results uploaded yet.</p>
                        @if(!$isSuspended)
                            <a href="{{ route('beneficiary.results.create') }}" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Your First Result
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
