<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['student_id', 'faculty_id', 'start_time', 'end_time', 'content', 'status', 'info'];

    /**
    * 获得对应用户
    *
    * @return User
    */
    public function student() {
        return $this->belongsTo('App\Models\User', 'student_id');
    }

    public function faculty() {
        return $this->belongsTo('App\Models\User', 'faculty_id');
    }

    public function cancel() {
        $this->attributes['status'] = 'canceled';
    }

    public function refuse() {
        $this->attributes['status'] = 'refused';
    }

    public function setInfo($info) {
        $this->attributes['info'] = $info;
    }

    public function accept() {
        $this->attributes['status'] = 'accepted';
    }
}
