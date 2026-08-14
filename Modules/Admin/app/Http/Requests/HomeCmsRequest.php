<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeCmsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'section_one_title' => 'required|max:191',
            'section_one_image' => 'required|file|mimes:jpeg,png,jpg,webp|max:300',
            'section_one_image_alt_text' => 'nullable|max:191',
            'section_two_title' => 'required|max:191',
            'section_three_title' => 'required|max:191',
            'section_three_description' => 'required|max:5000',
            'section_four_title' => 'required|max:191',
            'section_four_description' => 'required|max:5000',
            'section_five_title' => 'required|max:191',
            'section_six_title' => 'required|max:191',
            'section_six_description' => 'required|max:10000',
            'section_six_image' => 'required|file|mimes:jpeg,png,jpg,webp|max:300',
            'section_six_image_alt_text' => 'nullable|max:191',
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
