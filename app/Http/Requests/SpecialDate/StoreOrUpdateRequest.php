<?php

namespace App\Http\Requests\SpecialDate;

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
            'type' => ['required'],
            'grade_id' => ['required'],
            'subject_id' => ['required'],
            'year' => ['required'],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
