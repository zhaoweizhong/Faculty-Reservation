<?php

namespace App\Transformers;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    public function transform(Appointment $appointment)
    {
        
        if ($appointment->student()){
            return [
                'id' => $appointment->id,
                'sid' => $appointment->student_id,
                'reserved_time' => $appointment->reserved_time,
                'content' => $appointment->content,
            ];
        }
        else if ($appointment->faculty()){
            return [
                'id' => $appointment->id,
                'sid' => $appointment->faculty_id,
                'reserved_time' => $appointment->reserved_time,
                'content' => $appointment->content,
            ];
        }

    }
}
