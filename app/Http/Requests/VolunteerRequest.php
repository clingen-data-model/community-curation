<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Surveys\SurveyOptions;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
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
        return Auth::user()->hasAnyRole(['programmer', 'super-admin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $timezones = SurveyOptions::timezones();

        $rules = parent::rule();
        $rules['timezone'] = ['sometimes', 'nullable', Rule::in(timezone_identifiers_list())];
        $rules['country_id'] = 'sometimes|nullable|exists:countries,id';
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
