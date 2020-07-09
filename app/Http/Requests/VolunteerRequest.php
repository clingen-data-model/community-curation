<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class VolunteerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'sometimes|required|min:2|max:255',
            'last_name' => 'sometimes|required|min:2|max:255',
            'email' => 'sometimes|required|email:rfc,dns',
            'country_id' => 'sometimes|required|exists:countries,id',
            'timezone' => [
                'sometimes',
                'required',
                Rule::in(timezone_identifiers_list()),
                'not_in:UTC'
            ]
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'first_name' => 'first and last name',
            'last_name' => 'first and last name',
            'country_id' => 'country',
            'timezone' => 'closest city'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'A :attribute is required',
        ];
    }
}
