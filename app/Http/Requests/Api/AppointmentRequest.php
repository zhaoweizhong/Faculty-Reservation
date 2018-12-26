<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch($this->method()){
            case 'POST':
                return [
                    'student_id' => 'required|numeric',
                    'faculty_id' => 'required|numeric',
                    'start_time' => 'required|string|max:255',
                    'end_time'   => 'required|string|max:255',
                    'content'    => 'required|string|max:255',
                ];
                break;
            case 'PATCH':
                return [
                    'start_time' => 'string|max:255',
                    'end_time'   => 'string|max:255',
                    'content'    => 'string|max:255',
                ];
                break;
        }
    }
}
