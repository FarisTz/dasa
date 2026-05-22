<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ALevelEducation extends Model
{
    protected $table = 'a_level_educations';
    
    protected $fillable = [
        'school_name',
        'form_six_index_number',
        'division',
        'points',
        'end_of_study_year',
        'preferred_university',
        'form_six_certificate_path',
        'applicant_id'
    ];

    protected $casts = [
        'end_of_study_year' => 'integer',
        'points' => 'integer',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
