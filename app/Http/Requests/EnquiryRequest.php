<?php

namespace App\Http\Requests;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'model' => ['required', Rule::in(['BulkOrderEnquiry', 'OfficeEnquiry'])],
            'name' => 'required|max:191',
            'phone_number' => 'required',
            'email' => 'required|email',
            'g-recaptcha-response' => [new Recaptcha],
        ];
    }
}

