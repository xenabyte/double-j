<?php

namespace App\Models;

use App\Notifications\EmployeeResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Authenticatable implements CanResetPassword
{
    use Notifiable, SoftDeletes;
    use \Illuminate\Auth\Passwords\CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'othernames',
        'last_name',
        'email',
        'password',
        'dob',
        'phone',
        'address',
        'city',
        'state',
        'gender',
        'image',
        'cv',
        'cover_letter',
        'upload_folder',
        'job_posting_id',
        'client_id',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new EmployeeResetPassword($token));
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
