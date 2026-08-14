<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'app.name' => 'required|string|max:191',
            'currency.code' => 'required|string|max:10',
            'currency.symbol' => 'required|string|max:5',
            'catalog.sku_prefix' => 'required|string|max:20',
            'order.prefix' => 'required|string|max:20',
            'tax.percentage' => 'required|numeric|min:0|max:100',
            'shipping.flat_rate' => 'required|numeric|min:0',
            'contact.support_email' => 'nullable|email|max:191',
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
