<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ALevelEducation extends Model
{
    use HasFactory;

    protected $table = 'a_level_education';

    protected $fillable = [
        'user_id',
        'school_name',
        'form_six_index_number',
        'division',
        'points',
        'end_of_study_year',
        'preferred_university',
        'form_six_certificate_path',
    ];

    protected $casts = [
        'end_of_study_year' => 'integer',
        'points' => 'integer',
    ];

    /**
     * Get the user that owns the A-Level education record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the division label.
     */
    public function getDivisionLabelAttribute()
    {
        $divisions = [
            'I' => 'Division I (Excellent)',
            'II' => 'Division II (Very Good)',
            'III' => 'Division III (Good)',
            'IV' => 'Division IV (Satisfactory)',
            '0' => 'Division 0 (Failed)',
        ];

        return $divisions[$this->division] ?? $this->division;
    }

    /**
     * Get the certificate URL.
     */
    public function getCertificateUrlAttribute()
    {
        if ($this->form_six_certificate_path) {
            return asset('storage/' . $this->form_six_certificate_path);
        }
        return null;
    }
}
