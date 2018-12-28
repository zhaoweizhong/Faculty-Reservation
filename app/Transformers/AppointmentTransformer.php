<?php

namespace App\Transformers;

use App\Models\Appointment;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    public function transform(Appointment $appointment)
    {
        return [
            'id'         => $appointment->id,
            'student_id' => $appointment->student_id,
            'faculty_id' => $appointment->faculty_id,
            'start_time' => $appointment->start_time,
            'end_time'   => $appointment->end_time,
            'content'    => $appointment->content,
            'status'     => $appointment->status,
            'info'       => $appointment->info,
            'created_at' => $appointment->created_at,
            'faculty'    => User::where('sid', $appointment->faculty_id)->first(),
            'student'    => User::where('sid', $appointment->student_id)->first(),
        ];

    }
}
