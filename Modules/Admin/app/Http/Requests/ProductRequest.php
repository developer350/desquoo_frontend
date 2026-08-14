<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:191',
            'sku' => 'nullable|max:191',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'required|max:2000',
            'features' => 'required|max:10000',
            'dimensions' => 'required|max:10000',
            'warranty_shipping' => 'required|max:10000',
            'materials_certifications' => 'required|max:10000',
            'image' => 'required|file|mimes:jpeg,png,jpg,webp|max:300',
            'image_alt_text' => 'nullable|max:191',
            'sort_order' => 'nullable|integer|min:0|max:2147483647',
            'type' => 'required|in:single,variable',
            'is_addon' => 'nullable|boolean',
            'meta_title' => 'nullable|max:191',
            'meta_description' => 'nullable|max:3000',
            'meta_keywords' => 'nullable|max:1000',
            'other_meta_tags' => 'nullable|max:5000',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define custom error messages for specific validation rules.
     */
    public function messages()
    {
        return [
            'sort_order.max' => 'The sort order may not exceed the maximum allowable value.'
        ];
    }
}
