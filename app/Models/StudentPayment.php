<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'student_id',
        'amount',
        'status',
        'confirmed_at',
        'otp',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the installment for this payment.
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    /**
     * Get the student for this payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Check if payment is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status badge icon.
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'clock',
            'confirmed' => 'check-circle',
            'approved' => 'check-double',
            'rejected' => 'times-circle',
            default => 'circle',
        };
    }

    /**
     * Generate OTP for confirmation.
     */
    public static function generateOTP(): string
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
}
