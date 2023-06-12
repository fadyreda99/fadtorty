<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $id = $this->request->get('id');
        return [
            'category_name' => 'required|max:255|unique:categories,category_name,' . $id,
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'يرجى ادخال اسم القسم',
            'category_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجى ادخال وصف القسم',
        ];
    }
}
