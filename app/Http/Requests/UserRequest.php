<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'min:2',
                'max:255',
            ],
            'last_name' => [
                'required',
                'min:2',
                'max:255',
            ],
            'email' => ['required', 'min:6', 'max:255'],
            'email' => ['required', 'min:6', 'max:255', Rule::unique('users', 'email')->ignore(request()->route('id'))],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     */
    public function attributes(): array
    {
        return [
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
        ];
    }
}
