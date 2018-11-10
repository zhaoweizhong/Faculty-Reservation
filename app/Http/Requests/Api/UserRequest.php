<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'sid'      => 'required|numeric|regex: /^[0-9]{8}$/|unique:users',
                    'password' => 'required|string|min   : 6',
                    'name'     => 'required|string|max   : 255',
                    'email'    => 'required|email',
                    'type'     => 'required|string|in    : student,faculty',
                ];
                break;
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'name'              => 'string|max: 255',
                    'email'             => 'email',
                    'avatar_url'        => 'url',
                    'intro'             => 'string|max: 255',
                    'department'        => 'string|max: 80',
                    'office'            => 'string|max: 80',
                    'fields'            => 'string|max: 255',
                    'major'             => 'string|max: 80',
                    'available_time'    => 'string|max: 255',
                    'gpa'               => 'regex     : /^[0-4]{1}\.[0-9]{2}$/',
                    'interested_fields' => 'string|max: 255',
                ];
                break;
        }
    }
}
