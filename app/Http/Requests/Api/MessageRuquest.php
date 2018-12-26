<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receiver_id'=>'required|numeric|regex: /^[0-9]{8}$/|unique:users',
            'content'=>'required',
        ];
    }
}    