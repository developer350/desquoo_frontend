<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeCmsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'section_one_title' => 'required|max:191',
            'section_one_description' => 'required|max:5000',
            'section_two_title' => 'nullable|max:191',
            'section_two_description' => 'required|max:5000',
            'section_three_title' => 'required|max:191',
            'section_four_title' => 'required|max:191',
            'section_five_title' => 'required|max:191',
            'section_five_description' => 'required|max:5000',
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
