<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class ImageRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {

        $rules = [
            'image' => 'required',
        ];

        return $rules;
    }

      public function messages() {
          return [
              'image.image' => '文件类型错误',
          ];
      }
}
