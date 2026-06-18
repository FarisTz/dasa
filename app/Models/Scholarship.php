<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'amount',
        'deadline',
        'eligibility_criteria',
        'application_process',
        'contact_information',
    ];
    protected $casts = [
        'deadline' => 'date', // This casts deadline to Carbon object
    ];
}
