<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductBulkOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'min_quantity' => 'required|integer|min:1|max:999999',
            'max_quantity' => 'required|integer|min:1|max:999999|gte:min_quantity',
            'discount_percentage' => 'required|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0|max:2147483647',
            'status' => 'nullable|boolean',
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
