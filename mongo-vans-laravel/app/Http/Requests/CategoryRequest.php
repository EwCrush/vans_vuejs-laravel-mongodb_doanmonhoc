<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'categoryname' => ['required', 'min:3']
        ];
    }

    public function messages()
    {
        return [
            'categoryname.required' => ':attribute không được để trống',
            'categoryname.min' => ':attribute phải có độ dài tối thiểu :min ký tự',
        ];
    }

    public function attributes()
    {
        return [
            'categoryname' => 'Tên loại sản phẩm'
        ];
    }
}
