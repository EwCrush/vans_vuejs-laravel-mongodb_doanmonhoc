<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'otp' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'newpassword' => ['required', 'confirmed', 'min:8', 'max:64', 'not_regex:/[`()_+\-=\[\]{};\':"\\\|,.<>\/?~]|\s/'],
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => ':attribute không được để trống',
            'email.required' => ':attribute không được để trống',
            'email.exists' => ':attribute không tồn tại',
            'email.email' => ':attribute không đúng định dạng',
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
            'otp' => 'Mã OTP',
            'email' => 'Email',
            'newpassword' => 'Mật khẩu',
        ];
    }
}
