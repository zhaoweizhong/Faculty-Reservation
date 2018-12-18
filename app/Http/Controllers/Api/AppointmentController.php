<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(AppointmentRequest $request)
    {
        $appointment = Appointment::create([
            'student_id' =>$request->student_id,
            'faculty_id' =>$request->faculty_id,
            'reserved_time' =>$request->reserved_time,
            'content' =>$request->content,
        ]);

        if($this->is_available()){
            $student->appointments()->save($appointment);
            $faculty->appointments()->save($appointment);
            return $this->response->item($appointment, new AppointmentTransformer());
        }
        
        //else
    }

    public function is_available(){
        $faculty_id = $this -> faculty_id;
        $reserved_time = $this -> reserved_time;
        $faculty = $this -> faculty();
        $available_time = $this -> available_time;
        $appointments = $faculty -> appointments() -> all();
        foreach ($appointments as $appointment) {
            // judge whether the reserved_time has been reserved


        }
        return TRUE;
    }

    public function delete(AppointmentRequest $request){
        $appointment = $this->appointment();
        $faculty = $appointment -> faculty();
        $student = $appointment -> student();
        $appointment -> delete();
        $student -> save();
        //update faculty's available time
        

        $faculty -> save();
    }

    public function update(AppointmentRequest $request){
        $appointment = $this->appointment();

        $attributes = $request->only(['student_id', 'faculty_id', 'reserved_time', 'content']);

        $appointment->update($attributes);

        return $this->response->item($appointment, new AppointmentTransformer());
    }


    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}