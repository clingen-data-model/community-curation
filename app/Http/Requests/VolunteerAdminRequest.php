<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VolunteerAdminRequest extends VolunteerRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasAnyRole(['programmer', 'super-admin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['timezone'] = ['sometimes', 'nullable', Rule::in(timezone_identifiers_list())];
        $rules['country_id'] = 'sometimes|nullable|exists:countries,id';
        return $rules;
    }
}
