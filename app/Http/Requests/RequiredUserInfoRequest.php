<?php

namespace App\Http\Requests;

use App\Surveys\SurveyOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequiredUserInfoRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'timezone' => [
                'required',
                Rule::in(collect((new SurveyOptions())->timezones())->pluck('id')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'country_id.required' => 'We need to know your country for reporting purposes.',
            'country_id.exists' => 'Invalid country given.',
            'timezone.required' => 'We need to know the city nearest you to determine your timezone',
            'timezone.in' => 'The timezone you supplied is not valid.',
        ];
    }
}
