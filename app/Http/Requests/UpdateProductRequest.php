<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $id = $this->request->get('pro_id');
        return [
            'product_name' => 'required|max:255' . $id,
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'يرجى ادخال اسم المنتج',
            'description.required' => 'يرجى ادخال وصف المنتج',
        ];
    }
}
