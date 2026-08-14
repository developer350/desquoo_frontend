<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|max:191',
            'sort_order' => 'nullable|integer|min:0|max:2147483647'
        ];

        if ($this->isMethod('post')) {
            $rules['values'] = 'required|array|min:1';
            $rules['values.*'] = 'required|string|max:191|distinct';
        }

        return $rules;
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
