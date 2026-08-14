<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PolicyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:191',
            'content' => 'required|max:10000',
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
}
