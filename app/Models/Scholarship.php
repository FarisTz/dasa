<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    //
    protected $guarded = [

    ];
    protected $casts = [
        'deadline' => 'date', // This casts deadline to Carbon object
    ];



     /**
     * Get the user who created this scholarship.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the applications for this scholarship.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the applications count for this scholarship.
     */
    public function applicationsCount()
    {
        return $this->applications()->count();
    }

    /**
     * Scope a query to only include open scholarships.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
                     ->where('deadline', '>=', now());
    }

    /**
     * Scope a query to only include active scholarships.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'open')
                     ->where('deadline', '>=', now());
    }

    /**
     * Scope a query to only include available scholarships.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'open')
                     ->where('deadline', '>=', now());
    }

    /**
     * Check if scholarship is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open' && $this->deadline >= now();
    }

    /**
     * Check if scholarship is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if scholarship is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed' || $this->deadline < now();
    }

    /**
     * Check if deadline has passed.
     */
    public function isDeadlinePassed(): bool
    {
        return $this->deadline < now();
    }

    /**
     * Get the days until deadline.
     */
    public function getDaysUntilDeadlineAttribute()
    {
        if ($this->deadline < now()) {
            return 0;
        }
        return now()->diffInDays($this->deadline);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'open' => 'success',
            'draft' => 'warning',
            'closed' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the status badge icon.
     */
    public function getStatusBadgeIconAttribute()
    {
        return match($this->status) {
            'open' => 'door-open',
            'draft' => 'edit',
            'closed' => 'door-closed',
            default => 'circle',
        };
    }

    /**
     * Get the formatted deadline.
     */
    public function getFormattedDeadlineAttribute()
    {
        return $this->deadline ? $this->deadline->format('F d, Y') : null;
    }

    /**
     * Get the deadline status.
     */
    public function getDeadlineStatusAttribute()
    {
        if ($this->isDeadlinePassed()) {
            return 'expired';
        }
        return 'active';
    }

    /**
     * Get the applications count with status.
     */
    public function getApplicationsStatsAttribute()
    {
        return [
            'total' => $this->applications()->count(),
            'pending' => $this->applications()->where('status', 'pending')->count(),
            'submitted' => $this->applications()->where('status', 'submitted')->count(),
            'under_review' => $this->applications()->where('status', 'under_review')->count(),
            'approved_full' => $this->applications()->where('status', 'approved_full')->count(),
            'approved_partial' => $this->applications()->where('status', 'approved_partial')->count(),
            'rejected' => $this->applications()->where('status', 'rejected')->count(),
        ];
    }
}
