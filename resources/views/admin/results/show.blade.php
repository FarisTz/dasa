@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Result Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.results.index') }}">Results</a></div>
            <div class="breadcrumb-item">Details</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <!-- Result Details -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle text-primary"></i> Result Information</h4>
                        <div class="card-header-action">
                            <span class="badge badge-{{ $result->status_color }} badge-lg">
                                <i class="fas fa-{{ $result->status_icon }}"></i>
                                {{ ucfirst($result->status) }}
                            </span>
                            @if($result->is_suspended)
                                <span class="badge badge-danger badge-lg ml-2">
                                    <i class="fas fa-ban"></i> Suspended
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Student</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $result->student->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                                             alt="{{ $result->student->name }}"
                                                             class="rounded-circle mr-2"
                                                             width="30"
                                                             height="30">
                                                        {{ $result->student->name }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $result->student->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Academic Year</th>
                                                <td><strong>{{ $result->academic_year }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Student Year</th>
                                                <td>Year {{ $result->student_year }}</td>
                                            </tr>
                                            <tr>
                                                <th>Course Name</th>
                                                <td>{{ $result->course_name ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">GPA</th>
                                                <td>{{ $result->formatted_gpa }}</td>
                                            </tr>
                                            <tr>
                                                <th>CGPA</th>
                                                <td>{{ $result->formatted_cgpa }}</td>
                                            </tr>
                                            <tr>
                                                <th>Division</th>
                                                <td>{{ $result->division ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td>{{ $result->remarks ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Result File</th>
                                                <td>
                                                    @if($result->result_file_path)
                                                        <a href="{{ route('admin.results.download', $result->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No file</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if($result->admin_feedback)
                            <div class="mt-3">
                                <label><strong>Admin Feedback:</strong></label>
                                <div class="bg-light p-3 rounded">
                                    {{ $result->admin_feedback }}
                                </div>
                            </div>
                        @endif

                        @if($result->suspension_reason)
                            <div class="mt-3">
                                <label><strong>Suspension Reason:</strong></label>
                                <div class="bg-light p-3 rounded border-left border-danger">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                    {{ $result->suspension_reason }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Student's Other Results -->
                @if($studentResults->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-history text-primary"></i> Student's Other Results</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Academic Year</th>
                                            <th>Year</th>
                                            <th>GPA</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($studentResults as $otherResult)
                                            <tr>
                                                <td>{{ $otherResult->academic_year }}</td>
                                                <td>Year {{ $otherResult->student_year }}</td>
                                                <td>{{ $otherResult->formatted_gpa }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $otherResult->status_color }}">
                                                        {{ ucfirst($otherResult->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.results.show', $otherResult->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-tasks text-primary"></i> Actions</h4>
                    </div>
                    <div class="card-body">
                        @if($result->status == 'pending' || $result->status == 'under_review')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                This result is pending review.
                            </div>

                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('admin.results.approve', $result->id) }}" class="mb-3">
                                @csrf
                                <div class="form-group">
                                    <label>Feedback (Optional)</label>
                                    <textarea class="form-control" name="admin_feedback" rows="2" placeholder="Add feedback...">{{ old('admin_feedback') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Approve this result?')">
                                    <i class="fas fa-check-circle"></i> Approve Result
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form method="POST" action="{{ route('admin.results.reject', $result->id) }}" class="mb-3">
                                @csrf
                                <div class="form-group">
                                    <label>Feedback <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="admin_feedback" rows="2" placeholder="Provide reason for rejection..." required>{{ old('admin_feedback') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Reject this result?')">
                                    <i class="fas fa-times-circle"></i> Reject Result
                                </button>
                            </form>
                        @endif

                        @if($result->is_suspended)
                            <!-- Lift Suspension -->
                            <form method="POST" action="{{ route('admin.results.lift-suspension', $result->id) }}" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Lift suspension for this student?')">
                                    <i class="fas fa-check-circle"></i> Lift Suspension
                                </button>
                            </form>
                        @else
                            <!-- Suspend -->
                            <form method="POST" action="{{ route('admin.results.suspend', $result->id) }}" class="mb-3">
                                @csrf
                                <div class="form-group">
                                    <label>Suspension Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="suspension_reason" rows="2" placeholder="Provide reason for suspension..." required>{{ old('suspension_reason') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Suspend this student?')">
                                    <i class="fas fa-ban"></i> Suspend Student
                                </button>
                            </form>
                        @endif

                        <hr>
                        <div class="text-center">
                            <a href="{{ route('admin.results.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Back to Results
                            </a>
                            <a href="{{ route('admin.users.show', $result->student_id) }}" class="btn btn-info btn-block mt-2">
                                <i class="fas fa-user"></i> View Student Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
