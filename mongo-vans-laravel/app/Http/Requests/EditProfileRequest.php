<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\VietHoaMoiChuCaiDau;

class EditProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'min:3', 'max:30', new VietHoaMoiChuCaiDau, 'not_regex:/[`!@#$%^&*()_+\-=\[\]{};\':"\\\|,.<>\/?~]|\d/'],
            'email' => ['required', 'email', 'max:64'],
            'phone' => ['required', 'numeric', 'starts_with:0', 'regex:/^[0-9]{10}$/'],
            'filename' => ['nullable', 'ends_with:jpeg,png,jpg,gif,svg'],
        ];
    }

    public function messages()
    {
        return [
            //full name
            'fullname.required' => ':attribute không được để trống',
            'fullname.min' => ':attribute có độ dài tối thiểu là :min ký tự',
            'fullname.max' => ':attribute có độ dài tối đa là :max ký tự',
            'fullname.not_regex' => ':attribute không được chứa chữ số và các ký tự đặc biệt',

            //email
            'email.required' => ':attribute không được để trống',
            'email.email' => ':attribute yêu cầu phải đúng định dạng',
            'email.max' => ':attribute có độ dài tối đa là :max ký tự',
            'email.unique' => ':attribute này đã được sử dụng',

            //phone
            'phone.required' => ':attribute không được để trống',
            'phone.numeric' => ':attribute nhập vào phải là chữ số và không được chứa khoảng trắng',
            'phone.starts_with' => ':attribute phải bắt đầu là chữ số 0',
            'phone.regex' => ':attribute phải có độ dài là 10 chữ số',

            //filename
            'filename.ends_with' => ':attribute phải là dạng jpeg - png - jpg - gif - svg',
        ];
    }

    public function attributes()
    {
        return [
            'fullname' => 'Họ tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'filename' => 'Hình ảnh',
        ];
    }
}
