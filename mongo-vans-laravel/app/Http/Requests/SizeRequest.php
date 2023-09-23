<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeRequest extends FormRequest
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
            'size' => ['required'],
            'quantity' => ['required', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'size.required' => ':attribute không được để trống',
            'quantity.required' => ':attribute không được để trống',
            'quantity.numeric' => ':attribute nhập vào phải là chữ số',
        ];
    }

    public function attributes()
    {
        return [
            'size' => 'Size',
            'quantity' => 'Số lượng'
        ];
    }
}
