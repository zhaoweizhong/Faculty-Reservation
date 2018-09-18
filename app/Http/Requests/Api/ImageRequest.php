<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class ImageRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {

        $rules = [
            'image' => 'required|image',
        ];

        return $rules;
    }

      /*public function messages() {
          return [
              'image.dimensions' => '图片的清晰度不够，宽和高需要 200px 以上',
          ];
      }*/
}
