@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Upload Academic Result</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('beneficiary.results.index') }}">Results</a></div>
            <div class="breadcrumb-item">Upload</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-upload text-primary"></i> Upload New Result</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('beneficiary.results.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Academic Year <span class="text-danger">*</span></label>
                                <select class="form-control @error('academic_year') is-invalid @enderror" name="academic_year" required>
                                    <option value="">Select Academic Year</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                    @endphp
                                    @for($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}/{{ $year + 1 }}" {{ old('academic_year') == $year . '/' . ($year + 1) ? 'selected' : '' }}>
                                            {{ $year }}/{{ $year + 1 }}
                                        </option>
                                    @endfor
                                </select>
                                @error('academic_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if(in_array(old('academic_year'), $existingYears))
                                    <span class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> You have already uploaded a result for this year.
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Student Year <span class="text-danger">*</span></label>
                                <select class="form-control @error('student_year') is-invalid @enderror" name="student_year" required>
                                    <option value="">Select Year</option>
                                    @for($year = 1; $year <= 6; $year++)
                                        <option value="{{ $year }}" {{ old('student_year') == $year ? 'selected' : '' }}>
                                            Year {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('student_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Course Name</label>
                                <input type="text"
                                       class="form-control @error('course_name') is-invalid @enderror"
                                       name="course_name"
                                       placeholder="Enter course name"
                                       value="{{ old('course_name') }}">
                                @error('course_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>GPA (0-4)</label>
                                <input type="number"
                                       class="form-control @error('gpa') is-invalid @enderror"
                                       name="gpa"
                                       placeholder="Enter GPA"
                                       value="{{ old('gpa') }}"
                                       step="0.01"
                                       min="0"
                                       max="5">
                                @error('gpa')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="form-group">
                                <label>Division</label>
                                <select class="form-control @error('division') is-invalid @enderror" name="division">
                                    <option value="">Select Division</option>
                                    <option value="First Class" {{ old('division') == 'First Class' ? 'selected' : '' }}>First Class</option>
                                    <option value="Second Class Upper" {{ old('division') == 'Second Class Upper' ? 'selected' : '' }}>Second Class Upper</option>
                                    <option value="Second Class Lower" {{ old('division') == 'Second Class Lower' ? 'selected' : '' }}>Second Class Lower</option>
                                    <option value="Third Class" {{ old('division') == 'Third Class' ? 'selected' : '' }}>Third Class</option>
                                    <option value="Pass" {{ old('division') == 'Pass' ? 'selected' : '' }}>Pass</option>
                                </select>
                                @error('division')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror"
                                          name="remarks"
                                          rows="3"
                                          placeholder="Additional remarks...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Result File <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input @error('result_file') is-invalid @enderror"
                                           id="result_file"
                                           name="result_file"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required>
                                    <label class="custom-file-label" for="result_file">Choose file...</label>
                                </div>
                                @error('result_file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Allowed: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Uploaded results will be reviewed by the administrator. You will be notified once your result is approved or rejected.
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-upload"></i> Upload Result
                                </button>
                                <a href="{{ route('beneficiary.results.index') }}" class="btn btn-secondary btn-lg px-4">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Update file input label
        $('#result_file').on('change', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose file...');
        });
    });
</script>
@endpush
