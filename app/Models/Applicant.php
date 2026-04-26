<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'full_name',
        'gender',
        'birthdate',
        'place_of_birth',
        'nationality',
        'marital_status',
        'religion',
        'address',
        'region',
        'district',
        'email',
        'phone_number',
        'zanzibar_national_id',
        'passport_number',
        'disability',
        'birth_certificate_path',
        'user_id',
        // Next of Kin Information
        'kin_full_name',
        'kin_relationship',
        'kin_phone_number',
        'kin_religion',
        'kin_address',
        'kin_region',
        'kin_district'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'disability' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
