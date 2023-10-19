<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOTPRequest extends FormRequest
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
            'otp' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => ':attribute không được để trống',
            'email.required' => ':attribute không được để trống',
            'email.exists' => ':attribute không tồn tại',
            'email.email' => ':attribute không đúng định dạng',
        ];
    }

    public function attributes()
    {
        return [
            'otp' => 'Mã OTP',
            'email' => 'Email'
        ];
    }
}
