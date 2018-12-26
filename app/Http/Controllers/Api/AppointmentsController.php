<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentsController extends Controller
{
    public function store(AppointmentRequest $request) {
        if (isFacultyAvailable($request->faculty_id, $request->start_time, $request->end_time)) {
            $appointment = Appointment::create([
                'student_id' => $request->student_id,
                'faculty_id' => $request->faculty_id,
                'start_time' => Carbon::parse($request->start_time),
                'end_time'   => Carbon::parse($request->end_time),
                'content'    => $request->content,
                'status'     => 'pending',
            ]);
            return $this->response->item($appointment, new AppointmentTransformer())->setStatusCode(201);
        } else {
            return $this->response->errorBadRequest('导师在所选时间内不空闲');
        }
    }

    public static function isFacultyAvailable($faculty_id, $start_time_str, $end_time_str) {
        $faculty = App\Models\User::find($faculty_id);       
        $appointments = $faculty->appointments()->all();
        $start_time = Carbon::parse($start_time_str);
        $end_time = Carbon::parse($end_time_str);

        foreach ($appointments as $determined_appointment){
            if ($determined_appointment->status != "canceled" or $determined_appointment->status != "refused") {
                $reserved_start = Carbon::parse($determined_appointment->start_time);
                $reserved_end = Carbon::parse($determined_appointment->end_time);
                
                if (!(($start_time->gte($reserved_end)) or ($end_time->lte($reserved_start)))){
                    return false;
                }
            }
        }
    }

    public function update(AppointmentRequest $request, Appointment $appointment) {
        if ($request->has('start_time') && $request->has('end_time')) {
            if (!isFacultyAvailable($request->faculty_id, $request->start_time, $request->end_time)) {
                return $this->response->errorBadRequest('导师在所选时间内不空闲');
            }
        } elseif ($request->has('start_time') && !($request->has('end_time'))) {
            if (!isFacultyAvailable($request->faculty_id, $request->start_time, $appointment->end_time)) {
                return $this->response->errorBadRequest('导师在所选时间内不空闲');
            }
        } elseif (!($request->has('start_time')) && $request->has('end_time')) {
            if (!isFacultyAvailable($request->faculty_id, $appointment->start_time, $request->end_time)) {
                return $this->response->errorBadRequest('导师在所选时间内不空闲');
            }
        }
        $attributes = $request->only(['student_id', 'faculty_id', 'start_time', 'end_time', 'content']);
        $appointment->update($attributes);

        return $this->response->item($appointment, new AppointmentTransformer())->setStatusCode(200);;
    }

    public function userIndex(User $user) {
        $appointments = $user->appointments()->recent()->paginate(20);

        return $this->response->paginator($appointments, new AppointmentTransformer());
    }

    public function setStatus(Request $request, Appointment $appointment) {
        if ($request->status == "cancel") {
            $appointment->cancel($appointment);
            $appointment->setInfo($appointment, $request->info);
        } elseif ($request->status == "refuse") {
            $appointment->refuse($appointment);
            $appointment->setInfo($appointment, $request->info);
        } elseif ($request->status == "accept") {
            $appointment->accept($appointment);
        } else {
            return $this->response->errorBadRequest('操作错误');
        }
        return $this->response->noContent()->setStatusCode(200);;
    }
}