<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'filename' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'filename.required' => ':attribute không được để trống',
        ];
    }

    public function attributes()
    {
        return [
            'filename' => 'Hình ảnh'
        ];
    }
}
