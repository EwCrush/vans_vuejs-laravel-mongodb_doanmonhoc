<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\VietHoaMoiChuCaiDau;

class ProductRequest extends FormRequest
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
            'productname' => ['required', new VietHoaMoiChuCaiDau, 'min:3' ],
            'originalprice' => ['required', 'numeric', 'gte:0'],
            'sellingprice' =>  ['required', 'numeric', 'gte:0'],
            'filename' => ['nullable', 'ends_with:jpeg,png,jpg,gif,svg'],
            'category_id' => ['required', 'exists:categories,_id'],
            'subcategory_id' => ['required', 'exists:categories,_id'],
        ];
    }

    public function messages()
    {
        return [
            //productname
            'productname.required' => ':attribute không được để trống',
            'productname.min' => ':attribute phải có độ dài tối thiểu :min ký tự',

            //originalprice
            'originalprice.required' => ':attribute không được để trống',
            'originalprice.numeric' => ':attribute nhập vào phải là chữ số',
            'originalprice.gte' => ':attribute phải có giá trị lớn hơn :value',

            //sellingprice
            'sellingprice.required' => ':attribute không được để trống',
            'sellingprice.numeric' => ':attribute nhập vào phải là chữ số',
            'sellingprice.gte' => ':attribute phải có giá trị lớn hơn :value',

            //filename
            'filename.ends_with' => ':attribute phải là dạng jpeg - png - jpg - gif - svg',

            //category_id
            'category_id.required' => ':attribute không được để trống',
            'category_id.exists' => ':attribute không tồn tại',

            //subcategory_id
            'subcategory_id.required' => ':attribute không được để trống',
            'subcategory_id.exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes()
    {
        return [
            'productname' => 'Tên sản phẩm',
            'originalprice' => 'Giá bán',
            'sellingprice' => 'Giá sale',
            'filename' => 'Hình ảnh',
            'category_id' => 'Loại sản phẩm chính',
            'subcategory_id' => 'Loại sản phẩm phụ',
        ];
    }
}
