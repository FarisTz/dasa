<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scholarship_id',
        'status',
        'admin_notes',
        'submitted_at',
        'acknowledgement_letter_path',
        'acknowledgement_letter_submitted_at',
        'acknowledgement_status',
        'acknowledgement_admin_notes'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'acknowledgement_letter_submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scholarship that owns the application.
     */
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    /**
     * Check if acknowledgement letter is submitted.
     */
    public function isAcknowledgementSubmitted(): bool
    {
        return $this->acknowledgement_status === 'submitted' ||
               $this->acknowledgement_status === 'approved';
    }

    /**
     * Check if user can submit acknowledgement letter.
     */
    public function canSubmitAcknowledgement(): bool
    {
        return in_array($this->status, ['approved_full', 'approved_partial']) &&
               $this->acknowledgement_status === 'pending';
    }

    /**
     * Get acknowledgement status badge color.
     */
    public function getAcknowledgementStatusColorAttribute(): string
    {
        return match($this->acknowledgement_status) {
            'pending' => 'warning',
            'submitted' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get acknowledgement status badge icon.
     */
    public function getAcknowledgementStatusIconAttribute(): string
    {
        return match($this->acknowledgement_status) {
            'pending' => 'clock',
            'submitted' => 'file-alt',
            'approved' => 'check-circle',
            'rejected' => 'times-circle',
            default => 'circle',
        };
    }
}
