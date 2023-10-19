<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\VietHoaMoiChuCaiDau;

class ConsigneeRequest extends FormRequest
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
            'fullnameConsignee' => ['required', 'min:3', 'max:30', new VietHoaMoiChuCaiDau, 'not_regex:/[`!@#$%^&*()_+\-=\[\]{};\':"\\\|,.<>\/?~]|\d/'],
            'phoneConsignee' => ['required', 'numeric', 'starts_with:0', 'regex:/^[0-9]{10}$/'],
            'addressConsignee' => ['required']
        ];
    }

    public function messages()
    {
        return [
            //full name
            'fullnameConsignee.required' => ':attribute không được để trống',
            'fullnameConsignee.min' => ':attribute có độ dài tối thiểu là :min ký tự',
            'fullnameConsignee.max' => ':attribute có độ dài tối đa là :max ký tự',
            'fullnameConsignee.not_regex' => ':attribute không được chứa chữ số và các ký tự đặc biệt',

            //phone
            'phoneConsignee.required' => ':attribute không được để trống',
            'phoneConsignee.numeric' => ':attribute nhập vào phải là chữ số và không được chứa khoảng trắng',
            'phoneConsignee.starts_with' => ':attribute phải bắt đầu là chữ số 0',
            'phoneConsignee.regex' => ':attribute phải có độ dài là 10 chữ số',

            //address
            'addressConsignee.required' => ':attribute không được để trống',
        ];
    }

    public function attributes()
    {
        return [
            'fullnameConsignee' => 'Họ tên người nhận',
            'phoneConsignee' => 'Số điện thoại người nhận',
            'addressConsignee' => 'Địa chỉ người nhận',
        ];
    }
}
