<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class SiteSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $imageRule = function ($maxKb = 2048) {
            return ['nullable', function ($attribute, $value, $fail) use ($maxKb) {
                if ($value instanceof UploadedFile) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, ['jpeg', 'png', 'jpg', 'webp', 'svg'])) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' must be an image (jpeg, png, jpg, webp, svg).');
                    }
                    if ($value->getSize() > $maxKb * 1024) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' must not exceed ' . round($maxKb / 1024, 1) . 'MB.');
                    }
                }
            }];
        };

        return [
            'address' => 'required|max:2000',
            'email' => 'required|email|max:191',
            'phone_number' => 'required|string|max:191',
            'whatsapp_number' => 'nullable|string|max:191',
            'map_link' => 'nullable|url|max:191',
            'working_hours' => 'required|max:191',
            'header_logo' => $imageRule(2048),
            'header_logo_alt_text' => 'nullable|max:191',
            'header_mobile_logo' => $imageRule(2048),
            'header_mobile_logo_alt_text' => 'nullable|max:191',
            'footer_logo' => $imageRule(2048),
            'footer_logo_alt_text' => 'nullable|max:191',
            'auth_image' => $imageRule(5120),
            'auth_image_alt_text' => 'nullable|max:191',
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
