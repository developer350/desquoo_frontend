<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductGalleryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'media_type' => 'required',
            'image' => 'required_if:media_type,image|file|mimes:jpeg,png,jpg,webp|max:300',
            'image_alt_text' => 'nullable|max:191',
            'video_thumbnail_image' => 'required_if:media_type,video|file|mimes:jpeg,png,jpg,webp|max:300',
            'video' => 'required_if:media_type,video|file|mimes:mp4,avi,mov|max:5120',
            'video_url_thumbnail_image' => 'required_if:media_type,video_url|file|mimes:jpeg,png,jpg,webp|max:300',
            'video_url' => 'nullable|url|required_if:media_type,video_url|max:500',
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
     * Defines custom error messages for validation rules
     */
    public function messages()
    {
        return [
            'sort_order.max' => 'The sort order may not exceed the maximum allowable value.'
        ];
    }
}
