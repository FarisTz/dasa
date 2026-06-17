<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    protected $fillable = [
        'user_id',
        'scholarship_id',
        'status',
        'admin_notes',
        'submitted_at',
    ];
   protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the user/applicant that owns the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scholarship that this application is for.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }


}
