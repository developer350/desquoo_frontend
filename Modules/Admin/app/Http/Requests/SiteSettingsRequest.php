<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'address' => 'required|max:2000',
            'email' => 'required|email|max:191',
            'phone_number' => 'required|string|max:191',
            'whatsapp_number' => 'nullable|string|max:191',
            'map_link' => 'nullable|url|max:191',
            'working_hours' => 'required|max:191',
            'header_logo' => 'required|file|mimes:jpeg,png,jpg,webp,svg|max:300',
            'header_logo_alt_text' => 'nullable|max:191',
            'footer_logo' => 'required|file|mimes:jpeg,png,jpg,webp,svg|max:300',
            'footer_logo_alt_text' => 'nullable|max:191',
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
