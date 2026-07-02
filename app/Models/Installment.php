<?php

namespace App\Models;

use App\Models\StudentPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inst_number',
        'academic_year',
        'student_year',
        'amount',
        'release_date',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'release_date' => 'date',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this installment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the student payments for this installment.
     */
    public function studentPayments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    /**
     * Get the number of students who have signed for this installment.
     */
    public function getSignedCountAttribute()
    {
        return $this->studentPayments()->where('status', 'approved')->count();
    }

    /**
     * Get the number of students who are pending.
     */
    public function getPendingCountAttribute()
    {
        return $this->studentPayments()->where('status', 'pending')->count();
    }

    /**
     * Get the total students count.
     */
    public function getTotalStudentsAttribute()
    {
        return $this->studentPayments()->count();
    }

    /**
     * Check if installment is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope a query to only include active installments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
