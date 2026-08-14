<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoogleReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:191',
            'avatar' => 'nullable|file|image|max:300',
            'rating' => 'required|numeric|min:1|max:5',
            'sort_order' => 'required|numeric',
            'review' => 'nullable|max:5000',
            'status' => 'required|boolean',
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
