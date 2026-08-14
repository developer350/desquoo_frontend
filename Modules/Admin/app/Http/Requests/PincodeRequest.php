<?php

namespace Modules\Admin\Http\Requests;

use App\Models\Pincode;
use Illuminate\Foundation\Http\FormRequest;

class PincodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'pincodes' => 'required|array',
            'pincodes.*' => ['required', 'string', 'max:20'],
            'delivery_days' => 'required|integer|min:0|max:365',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $pincodes = $this->input('pincodes', []);
            $pincodes = explode(',', implode(',', $pincodes));
            $currentId = base64_decode($this->route('pincode'));
            $duplicates = [];

            foreach ($pincodes as $pincode) {
                $exists = Pincode::where('id', '!=', $currentId)
                    ->where(function ($query) use ($pincode) {
                        $query->where('pincodes', 'LIKE', $pincode.',%')
                            ->orWhere('pincodes', 'LIKE', '%,'.$pincode.',%')
                            ->orWhere('pincodes', 'LIKE', '%,'.$pincode)
                            ->orWhere('pincodes', '=', $pincode);
                    })
                    ->exists();

                if ($exists) {
                    $duplicates[] = $pincode;
                }
            }

            if (! empty($duplicates)) {
                $validator->errors()->add('pincodes', 'The following pincodes are already assigned: '.implode(', ', $duplicates));
            }
        });
    }
}
