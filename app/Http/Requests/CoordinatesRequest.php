<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CoordinatesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public $validator = null;
    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function messages()
    {
        return [
            'latitude.required'=>'You haven`t provided a latitude!',
            'latitude.regex'=>'You haven`t provided a valid latitude!',
            'longitude.required'=>'You haven`t provided a longitude!',
            'longitude.regex'=>'You haven`t provided a valid longitude!',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitude'=>'required|regex:#\d{1,2}\.\d{5}#',
            'longitude'=>'required|regex:#\d{1,3}\.\d{5}#',
        ];
    }
}
