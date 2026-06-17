<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Motivation extends Model
{
    //  use HasFactory;

    protected $fillable = [
        'user_id',
        'motivation_letter',
        'academic_goals',
        'community_contribution',
        'additional_information',
    ];

    /**
     * Get the user that owns the motivation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
