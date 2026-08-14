<?php

namespace App\Http\Requests;

use App\Rules\ValidGstin;
use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required|string|max:20',
            'landmark' => 'nullable|string|max:255',
            'gstnumber' => [
                'nullable',
                'string',
                'size:15',
                new ValidGstin,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'gstnumber.string' => 'GST number must be a valid string.',
            'gstnumber.size' => 'GST number must be exactly 15 characters.',
        ];
    }
}
