<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\VolunteerRequestContract;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class VolunteerRequest extends FormRequest implements VolunteerRequestContract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        // return Auth::user()->hasAnyRole(['programmer', 'super-admin', 'admin']);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $volunteer = null;
        if (request()->has('id')) {
            $volunteer = User::find(request()->id);
        }

        if (request()->route()->parameter('volunteer')) {
            $volunteer = User::find(request()->route()->parameter('volunteer'));
        }

        $rules = [
            'first_name' => 'sometimes|required|min:2|max:255',
            'last_name' => 'sometimes|required|min:2|max:255',
            'email' => [
                'sometimes',
                'required',
                'email:rfc,dns',
                ($volunteer)
                    ? (new Unique('users', 'email'))->ignore($volunteer->id)
                    : (new Unique('users', 'email')),                
            ],
            'hypothesis_id' => [
                Rule::unique('users', 'hypothesis_id')->ignore($volunteer->id),
            ],
            'country_id' => 'sometimes|required|exists:countries,id',
            'timezone' => [
                'sometimes',
                'required',
                Rule::in(timezone_identifiers_list()),
                'not_in:UTC',
            ],
        ];

        return $rules;
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
            'timezone' => 'closest city',
            'hypothesis_id' => 'Hypothesis ID',
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
            'hypothesis_id.unique' => 'It looks like you already have a record in the CCDB. To prevent duplication, if you want to add a new activity, please email volunteer@clinicalgenome.org'
        ];
    }
}
