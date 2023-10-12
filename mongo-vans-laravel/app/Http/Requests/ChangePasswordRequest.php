<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'oldpassword' => ['required'],
            'newpassword' => ['required', 'confirmed', 'min:8', 'max:64', 'not_regex:/[`()_+\-=\[\]{};\':"\\\|,.<>\/?~]|\s/'],
        ];
    }

    public function messages()
    {
        return [
            'oldpassword.required' => ':attribute không được để trống',
            'newpassword.required' => ':attribute không được để trống',
            'newpassword.confirmed' => ':attribute xác nhận không trùng khớp',
            'newpassword.not_regex' => ':attribute chỉ được chứa chữ cái, chữ số và các ký tự !@#$%^&*',
            'newpassword.min' => ':attribute có độ dài tối thiểu là :min ký tự',
            'newpassword.max' => ':attribute có độ dài tối đa là :max ký tự',
        ];
    }

    public function attributes()
    {
        return [
            'oldpassword' => 'Mật khẩu cũ',
            'newpassword' => 'Mật khẩu mới',
        ];
    }
}
