<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sid', 'password', 'name', 'email', 'avatar_url', 'type' ,'office', 'fields', 'intro', 'available_time', 'gpa', 'interested_fields'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin'
    ];

    // Rest omitted for brevity

    public function messages_to_me()
    {
        return $this->hasMany('App\Models\Message', 'receiver_id');
    }

    public function messages_to_other()
    {
        return $this->hasMany('App\Models\Message', 'sender_id');
    }

    public function appointments()
    {
        if ($this->type == 'student') {
            return $this->hasMany('App\Models\Appointment', 'student_id');
        } else if ($this->type == 'faculty') {
            return $this->hasMany('App\Models\Appointment', 'faculty_id');
        }
        return null;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
