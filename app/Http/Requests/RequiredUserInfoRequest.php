<?php

namespace App\Http\Requests;

use App\Surveys\SurveyOptions;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RequiredUserInfoRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'timezone' => [
                'required',
                Rule::in(collect((new SurveyOptions())->timezones())->pluck('id'))
            ]
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'We need to know your country for reporting purposes.',
            'country_id.exists' => 'Invalid country given.',
            'timezone.required' => 'We need to know the city nearest you to determine your timezone',
            'timezone.in' => 'The timezone you supplied is not valid.'
        ];
    }
}
