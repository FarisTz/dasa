<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OLevelEducation extends Model
{
    protected $table = 'o_level_educations';
    
    protected $fillable = [
        'school_name',
        'form_four_index_number',
        'division',
        'points',
        'end_of_study_year',
        'form_four_certificate_path',
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
