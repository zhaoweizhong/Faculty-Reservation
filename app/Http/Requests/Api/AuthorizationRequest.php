<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class AuthorizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sid' => 'required|numeric|regex:/^[0-9]{8}$/',
            'password' => 'required|string|min:6',
        ];
    }
}
