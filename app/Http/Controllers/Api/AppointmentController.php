<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function store(AppointmentRequest $request)
    {
        $appointment = Appointment::create([
            'student_id' => $request->student_id,
            'faculty_id' => $request->faculty_id,
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => Carbon::parse($request->end_time),
            'content' => $request->content,
        ]);

        if($this -> is_available($appointment)){
            $student = $appointment->student();
            $faculty = $appointment->faculty();
            $student -> appointments()->save($appointment);
            $faculty -> appointments()->save($appointment);
            return $this->response->item($appointment, new AppointmentTransformer());
        }        
        //if not available
        else{
        
        }

    }

    public function is_available(Appointment $appointment){
        $faculty_id = $appointment -> faculty_id;

        $start_time = $appointment -> start_time; 

        $end_time = $appointment -> end_time;

        $faculty = $appointment -> faculty();

        $available_time = $faculty -> available_time;//a 2-dimension array
       
        $appointments = $faculty -> appointments() -> all();

        for($i = 0; $i < count($available_time); $i++){
            $start = Carbon::parse($available_time[$i][0]);
            $end = Carbon::parse($available_time[$i][1]);
            //judge whether in available_time[i]'s range
            if($start_time->gte($start) and $end_time -> lte($end)){
                //judge whether this time is available
                foreach ($appointments as $determined_appointment){
                    $reserved_start = $determined_appointment -> $start_time;
                    $reserved_end = $determined_appointment -> $end_time;
                    
                    if(($start_time->lte($reserved_start) and $end_time -> lte($reserved_start)) 
                        or ($start_time->gte($reserved_start) and $end_time -> gte($reserved_start))){
                        return TRUE;
                    }
                    else{
                        return FALSE;
                    }
                }
            }
            else{
                return FALSE;
            }
        }

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

        $attributes = $request->only(['student_id', 'faculty_id', 'start_time', 'end_time', 'content']);

        $appointment->update($attributes);

        return $this->response->item($appointment, new AppointmentTransformer());
    }

}