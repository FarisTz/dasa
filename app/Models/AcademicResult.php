<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year',
        'student_year',
        'course_name',
        'gpa',
        'cgpa',
        'division',
        'remarks',
        'result_file_path',
        'result_file_name',
        'status',
        'admin_feedback',
        'reviewed_by',
        'reviewed_at',
        'is_suspended',
        'suspension_reason',
        'suspended_at',
        'suspension_lifted_at',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'cgpa' => 'decimal:2',
        'is_suspended' => 'boolean',
        'reviewed_at' => 'datetime',
        'suspended_at' => 'datetime',
        'suspension_lifted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student who owns the result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the admin who reviewed the result.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'under_review' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'resubmit' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get status icon.
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'clock',
            'under_review' => 'spinner',
            'approved' => 'check-circle',
            'rejected' => 'times-circle',
            'resubmit' => 'redo',
            default => 'circle',
        };
    }

    /**
     * Get formatted GPA.
     */
    public function getFormattedGpaAttribute(): string
    {
        return $this->gpa ? number_format($this->gpa, 2) : 'N/A';
    }

    /**
     * Get formatted CGPA.
     */
    public function getFormattedCgpaAttribute(): string
    {
        return $this->cgpa ? number_format($this->cgpa, 2) : 'N/A';
    }

    /**
     * Check if result is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if result is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if result is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if student is suspended for this result.
     */
    public function isSuspended(): bool
    {
        return $this->is_suspended;
    }

    /**
     * Scope for pending results.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved results.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected results.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for suspended students.
     */
    public function scopeSuspended($query)
    {
        return $query->where('is_suspended', true);
    }
}
