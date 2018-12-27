<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Appointment extends Model
{
    use Searchable;
    
    protected $fillable = ['student_id', 'faculty_id', 'start_time', 'end_time', 'content', 'status', 'info'];

    /**
    * 获得对应用户
    *
    * @return User
    */
    public function student() {
        return $this->belongsTo('App\Models\User', 'student_id', 'sid');
    }

    public function faculty() {
        return $this->belongsTo('App\Models\User', 'faculty_id', 'sid');
    }

    public function cancel() {
        $this->attributes['status'] = 'canceled';
        $this->save();
    }

    public function refuse() {
        $this->attributes['status'] = 'refused';
        $this->save();
    }

    public function setInfo($info) {
        $this->attributes['info'] = $info;
        $this->save();
    }

    public function confirm() {
        $this->attributes['status'] = 'confirmed';
        $this->save();
    }
}
