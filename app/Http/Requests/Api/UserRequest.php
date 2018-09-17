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
        return [
            'sid' => 'required|numeric|regex:/^[0-9]{8}$/|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'type' => 'required|string|in:student,faculty',
        ];
    }
}
