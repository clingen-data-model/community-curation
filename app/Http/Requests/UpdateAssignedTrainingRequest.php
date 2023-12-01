<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignedTrainingRequest extends FormRequest
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
            'trained_at' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'A date completed is required.',
            'date' => 'This must be a valid date.',
        ];
    }
}
