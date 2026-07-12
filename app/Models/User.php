<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone_number',
        'profile_photo_path',
        'email_verified_at',
         'is_academic_suspended',
        'academic_suspended_at',
        'academic_suspension_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function personalInfo(): HasOne
    {
        return $this->hasOne(PersonalInfo::class);
    }
    public function oLevelEducation(): HasOne
    {
        return $this->hasOne(OLevelEducation::class);
    }
    public function aLevelEducation(): HasOne
    {
        return $this->hasOne(ALevelEducation::class);
    }
    public function motivation(): HasOne
    {
        return $this->hasOne(Motivation::class);
    }
    public function applications(): HasOne
    {
        return $this->hasOne(Application::class);
    }




     /**
     * Get the academic results for the user.
     */
    public function academicResults()
    {
        return $this->hasMany(AcademicResult::class, 'student_id');
    }

    /**
     * Get the latest academic result for the user.
     */
    public function latestAcademicResult()
    {
        return $this->hasOne(AcademicResult::class, 'student_id')->latest();
    }

    /**
     * Get approved academic results.
     */
    public function approvedAcademicResults()
    {
        return $this->academicResults()->where('status', 'approved');
    }

    /**
     * Check if user is academically suspended.
     */
    public function isAcademicallySuspended(): bool
    {
        return $this->is_academic_suspended;
    }

    /**
     * Check if user is beneficiary.
     */
    public function isBeneficiary(): bool
    {
        return $this->role === 'beneficiary';
    }

    /**
     * Suspend user academically.
     */
    public function suspendAcademically(string $reason = null)
    {
        $this->is_academic_suspended = true;
        $this->academic_suspended_at = now();
        $this->academic_suspension_reason = $reason;
        $this->save();
    }

    /**
     * Lift academic suspension.
     */
    public function liftAcademicSuspension()
    {
        $this->is_academic_suspended = false;
        $this->academic_suspended_at = null;
        $this->academic_suspension_reason = null;
        $this->save();
    }


  
    /**
     * Get the student payments for the user.
     */
    public function studentPayments()
    {
        return $this->hasMany(StudentPayment::class, 'student_id');
    }








}
