@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Add Installment</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.installments.index') }}">Installments</a></div>
            <div class="breadcrumb-item">Add Installment</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle text-primary"></i> Create New Installment</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.installments.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Installment Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="form-control @error('inst_number') is-invalid @enderror"
                                           name="inst_number"
                                           placeholder="e.g., INST-2026-001"
                                           value="{{ old('inst_number') }}"
                                           required>
                                </div>
                                @error('inst_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Academic Year <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('academic_year') is-invalid @enderror" name="academic_year" required>
                                        <option value="">Select Academic Year</option>
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 1;
                                            $endYear = $currentYear + 5;
                                        @endphp
                                        @for($year = $endYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}/{{ $year + 1 }}" {{ old('academic_year') == $year . '/' . ($year + 1) ? 'selected' : '' }}>
                                                {{ $year }}/{{ $year + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('academic_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Student Year <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                    <select class="form-control @error('student_year') is-invalid @enderror" name="student_year" required>
                                        <option value="">Select Year</option>
                                        @for($year = 1; $year <= 6; $year++)
                                            <option value="{{ $year }}" {{ old('student_year') == $year ? 'selected' : '' }}>
                                                Year {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('student_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                    </div>
                                    <input type="number"
                                           class="form-control @error('amount') is-invalid @enderror"
                                           name="amount"
                                           placeholder="Enter amount"
                                           value="{{ old('amount') }}"
                                           step="0.01"
                                           required>
                                </div>
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Enter the amount in TSh (Tanzanian Shillings).</small>
                            </div>

                            <div class="form-group">
                                <label>Release Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <input type="date"
                                           class="form-control @error('release_date') is-invalid @enderror"
                                           name="release_date"
                                           value="{{ old('release_date') }}">
                                </div>
                                @error('release_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                    <label class="custom-control-label" for="is_active">
                                        <i class="fas fa-toggle-on text-success"></i>
                                        Active
                                    </label>
                                </div>
                                <small class="text-muted d-block">Toggle to activate or deactivate this installment.</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> This installment will be assigned to <strong>all beneficiaries</strong> automatically upon creation.
                        <br>
                        <small>You can also assign individual students later from the installment details page.</small>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Create Installment
                                </button>
                                <a href="{{ route('admin.installments.index') }}" class="btn btn-secondary btn-lg px-4">
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
