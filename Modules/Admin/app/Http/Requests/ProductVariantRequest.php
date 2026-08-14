<?php

namespace Modules\Admin\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ProductVariantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'sku' => 'nullable|max:191',
            'short_description' => 'nullable|max:2000',
            'features' => 'nullable|max:10000',
            'dimensions' => 'nullable|max:10000',
            'warranty_shipping' => 'nullable|max:10000',
            'materials_certifications' => 'nullable|max:10000',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:300',
            'image_alt_text' => 'nullable|max:191',
        ];

        if ($this->isMethod('post')) {
            $rules['attribute_values'] = [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $variantValues = Arr::flatten($value);

                    $exists = ProductVariant::where(
                        'product_id',
                        base64_decode($this->route('product'))
                    )
                        ->where('variant_name', implode(',', $variantValues))
                        ->exists();

                    if ($exists) {
                        $fail('The selected variant already exists.');
                    }
                },
            ];
        } else {
            $rules['attribute_values'] = 'nullable|array';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
