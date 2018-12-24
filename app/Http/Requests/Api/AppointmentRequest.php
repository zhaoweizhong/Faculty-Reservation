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
                    'student_id' => 'required|numeric|regex: /^[0-9]{8}$/|unique:users',
                    'faculty_id' => 'required|numeric|regex: /^[0-9]{8}$/|unique:users',
                    'start_time' => 'string|max: 255',
                    'end_time' => 'string|max: 255',
                    'content' => 'string|max: 255',
                ];
                break;
            case 'PATCH':
                return [
                    'start_time' => 'string|max: 255',
                    'end_time' => 'string|max: 255',
                    'content' => 'string|max: 255',
                ];
                break;
            case 'DELETE':
                return [];
                break;
        }
    }
}
