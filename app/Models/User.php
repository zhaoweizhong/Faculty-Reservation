<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Searchable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sid', 'password', 'name', 'email', 'avatar_url', 'type' ,'type_num' ,'office', 'fields', 'intro', 'gpa', 'interested_fields'
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
        return $this->hasMany('App\Models\Message', 'receiver_id', 'sid');
    }

    public function messages_to_other()
    {
        return $this->hasMany('App\Models\Message', 'sender_id', 'sid');
    }

    public function appointments()
    {
        if ($this->type == 'student') {
            return $this->hasMany('App\Models\Appointment', 'student_id', 'sid');
        } else if ($this->type == 'faculty') {
            return $this->hasMany('App\Models\Appointment', 'faculty_id', 'sid');
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

    public function toSearchableArray()
    {
        return [
            'sid' => $this->sid,
            'name' => $this->name,
            'email' => $this->email,
            'department' => $this->department,
            'intro' => $this->intro,
            'fields' => $this->fields,
            'type_num' => $this->type_num,
        ];
    }
}
