<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:191',
            'image' => 'required|file|mimes:jpeg,png,jpg,webp|max:300',
            'home_image' => 'nullable|required_if:show_in_homepage,1|file|mimes:jpeg,png,jpg,webp,svg|max:300',
            'image_alt_text' => 'nullable|max:191',
            'banner_title' => 'required|max:191',
            'banner' => 'required|file|mimes:jpeg,png,jpg,webp|max:300',
            'banner_mobile' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:300',
            'banner_alt_text' => 'nullable|max:191',
            'sort_order' => 'nullable|integer|min:0|max:2147483647',
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
