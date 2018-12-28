<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\Api\AppointmentRequest;
use App\Transformers\AppointmentTransformer;

class AppointmentsController extends Controller
{
    public function store(AppointmentRequest $request) {
        if ($this->isFacultyAvailable($request->faculty_id, $request->start_time, $request->end_time)) {
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

    public function show(Appointment $appointment) {
        return $this->response->item($appointment, new AppointmentTransformer());
    }

    public function isFacultyAvailable($faculty_id, $start_time_str, $end_time_str) {
        $faculty = \App\Models\User::where('sid', $faculty_id)->first();
        $appointments = $faculty->appointments;
        $start_time = Carbon::parse($start_time_str);
        $end_time = Carbon::parse($end_time_str);

        $flag = true;
        foreach ($appointments as $determined_appointment){
            if ($determined_appointment->status != "cancelled" or $determined_appointment->status != "refused") {
                $reserved_start = Carbon::parse($determined_appointment->start_time);
                $reserved_end = Carbon::parse($determined_appointment->end_time);
                
                if ((($start_time->gte($reserved_end)) or ($end_time->lte($reserved_start)))){
                    $flag = true;
                } else {
                    $flag = false;
                }
                if ($flag == false) {
                    return false;
                }
            }
        }
        return true;
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

    public function userIndex(User $user, Request $request) {
        $appointments = $user->appointments();

        if ($request->filter != 'all') {
            $appointmentsPaginate = $appointments->where('status', $request->filter)->orderBy('created_at','desc')->paginate(7);
        }

        $appointmentsPaginate = $appointments->orderBy('created_at','desc')->paginate(7);
        return $this->response->paginator($appointmentsPaginate, new AppointmentTransformer())->setMeta(['detail' => [
            'total'     => $appointments->count(),
            'pending'   => $user->appointments()->where('status', 'pending')->count(),
            'confirmed' => $user->appointments()->where('status', 'confirmed')->count(),
            'refused'   => $user->appointments()->where('status', 'refused')->count(),
            'cancelled'  => $user->appointments()->where('status', 'cancelled')->count(),
        ]]);
    }

    public function setStatus(Request $request, Appointment $appointment) {
        if ($request->status == "cancel") {
            $appointment->cancel();
            $appointment->setInfo($request->info);
        } elseif ($request->status == "refuse") {
            $appointment->refuse();
            $appointment->setInfo($request->info);
        } elseif ($request->status == "confirm") {
            $appointment->confirm();
        } else {
            return $this->response->errorBadRequest('操作错误');
        }
        return $this->response->noContent()->setStatusCode(200);
    }
}