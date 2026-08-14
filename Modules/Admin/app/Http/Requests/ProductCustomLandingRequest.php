<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCustomLandingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'product_id' => 'required|exists:products,id',
            'slug' => ['required', Rule::unique('product_custom_landings', 'slug')
                ->ignore(base64_decode($this->route('product_custom_landing')))
                ->whereNull('deleted_at'), function ($attribute, $value, $fail) {
                    $reservedRoutes = ['office-design', 'blog', 'refund-policy', 'terms-and-conditions', 'privacy-policy', 'bulk-order', 'product-category', 'shop', 'product', 'order-failed', 'order-invoice', 'order-confirmation', 'login', 'signup', 'otp', 'my-account', 'logout'];
                    if (in_array(strtolower($value), $reservedRoutes)) {
                        $fail('The '.$attribute.' name is reserved by the system. Please choose a different slug. Reserved names: '.implode(', ', $reservedRoutes));
                    }
                }],
            'show_height_calculator' => 'nullable|boolean',
            'height_calculator_title' => 'nullable|required_if:show_height_calculator,1',
            'sitting_desk_image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:300|required_if:show_height_calculator,1',
            'standing_desk_image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:300|required_if:show_height_calculator,1',
            'show_assembly_section' => 'nullable|boolean',
            'assembly_title' => 'nullable|required_if:show_assembly_section,1',
            'assembly_image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:300|required_if:show_assembly_section,1',
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
