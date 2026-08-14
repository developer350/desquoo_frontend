<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhyChooseUsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:191',
            'description' => 'required|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:2147483647'
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
