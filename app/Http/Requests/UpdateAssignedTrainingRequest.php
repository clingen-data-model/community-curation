<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignedTrainingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'trained_at' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'A date completed is required.',
            'date' => 'This must be a valid date.',
        ];
    }
}
