<?php

namespace App\Models;

use App\Notifications\ClientResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'phone',
        'company_address',
        'industry',
        'company_name',
        'company_email',
        'company_phone',
        'logo',
        'upload_folder',
        'slug',
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
        $this->notify(new ClientResetPassword($token));
    }

    public function isBiodataComplete(){
        return 
        $this->company_name &&
        $this->logo &&
        $this->phone &&
        $this->company_address &&
        $this->company_email &&
        $this->industry &&
        $this->company_phone;
    }
}
