<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\VolunteerRequestContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VolunteerAdminRequest extends VolunteerRequest implements VolunteerRequestContract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdminOrHigher();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['volunteer_type_id'] = 'sometimes|required|exists:volunteer_types,id';
        $rules['volunteer_status_id'] = 'sometimes|required|exists:volunteer_statuses,id';
        $rules['timezone'] = ['sometimes', 'nullable', Rule::in(timezone_identifiers_list())];
        $rules['country_id'] = 'sometimes|nullable|exists:countries,id';

        return $rules;
    }
}
