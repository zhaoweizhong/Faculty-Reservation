<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['student_id', 'faculty_id', 'reserved_time', 'content'];

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
}
