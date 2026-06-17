<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInfo extends Model
{
    //
    protected $fillable = [
        'user_id',
        'gender',
        'birthdate',
        'place_of_birth',
        'nationality',
        'marital_status',
        'religion',
        'address',
        'region',
        'district',
        'phone_number',
        'id_type',
        'id_number',
        'disability',
        'birth_certificate_path',
        'kin_full_name',
        'kin_relationship',
        'kin_phone_number',
        'kin_address',
        'kin_district',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'birthdate' => 'date', // This casts birthdate to Carbon object
    ];

    /**
     * Get the user that owns the personal info.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
