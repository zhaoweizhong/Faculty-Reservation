<?php

namespace App\Transformers;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    public function transform(Appointment $appointment)
    {
        
        return [
            'id' => $appointment->id,
            'student_id' => $appointment ->student_id,
            'faculty_id' => $appointment->faculty_id,
            'reserved_time' => $appointment->reserved_time,
            'content' => $appointment->content,
        ];

    }
}
