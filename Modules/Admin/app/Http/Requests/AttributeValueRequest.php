<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeValueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'value' => [
                'required',
                'max:191',
                Rule::unique('attribute_values')
                    ->where('attribute_id', base64_decode($this->route('attribute')))
                    ->whereNull('deleted_at')
                    ->ignore($this->route('value') ? base64_decode($this->route('value')) : null),
            ],
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
