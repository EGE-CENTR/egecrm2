<?php

namespace App\Http\Requests\PaymentAdditional;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purpose' => ['required'],
            'year' => ['required'],
            'sum' => ['required', 'numeric'],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
