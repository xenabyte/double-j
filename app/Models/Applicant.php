<?php

namespace App\Models;

use App\Notifications\ApplicantResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 
        'password',
        'title',
        'othernames',
        'last_name',
        'dob',
        'phone',
        'address',
        'city',
        'state',
        'gender',
        'cv',
        'cover_letter',
        'image',
        'slug',
        'upload_folder',
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
        $this->notify(new ApplicantResetPassword($token));
    }

    public function isBiodataComplete(){
        return 
        $this->title &&
        $this->othernames &&
        $this->last_name &&
        $this->dob &&
        $this->phone &&
        $this->address &&
        $this->city &&
        $this->state &&
        $this->gender &&
        $this->cv &&
        $this->cover_letter &&
        $this->image;
    }
}
