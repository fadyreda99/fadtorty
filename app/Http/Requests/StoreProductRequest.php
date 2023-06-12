<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'product_name' => 'required',
            'description' => 'required',
            'category_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'يرجى ادخال اسم المنتج',
            'category_id.required' => 'يرجى اختيار اسم القسم',
            'description.required' => 'يرجى ادخال وصف المنتج',
        ];
    }
}
