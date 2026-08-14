<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'note' => 'nullable|string|max:1000',
            'same_bill_address' => 'required|boolean',
            'shippingAddressId' => 'required|exists:user_addresses,id',
            'billingAddressId' => 'required_if:same_bill_address,0|exists:user_addresses,id',
        ];
    }
}
