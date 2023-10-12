<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'product' => ['required', 'exists:products,_id'],
            'size' => ['required'],
            'quantity' => ['required', 'numeric', 'gt:0']
        ];
    }

    public function messages()
    {
        return [
            //product
            'product.required' => ':attribute không được để trống',
            'product.exists' => ':attribute không tồn tại',

            //size
            'size.required' => ':attribute chưa được chọn',

            //quantity
            'quantity.required' => ':attribute không được để trống',
            'quantity.numeric' => ':attribute nhập vào phải là chữ số',
            'quantity.gt' => 'Vui lòng chọn số lượng',
        ];
    }

    public function attributes()
    {
        return [
            'product' => 'Sản phẩm',
            'size' => 'Size',
            'quantity' => 'Số lượng'
        ];
    }
}
