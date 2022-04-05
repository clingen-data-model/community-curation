<?php

namespace App\Http\Requests;

use App\CustomSurvey;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomSurveyRequest extends FormRequest
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
        $customSurvey = new CustomSurvey();
        if ($this->route('id')) {
            $customSurvey = CustomSurvey::findOrFail($this->route('id'));
        }

        return [
            'curation_group_id' => 'required|exists:curation_groups,id',
            'volunteer_type_id' => 'required|exists:volunteer_types,id',
            'name' => [
                        'required',
                        'not_regex:/[\[\]{}\|\\/\~#<>\\\]/',
                        Rule::unique('custom_surveys')->ignore($customSurvey->id)
                    ]
        ];
    }

    public function messages()
    {
        return [
            'name.not_regex' => 'The custom survey name may not include any of the following characters: [ ] { } | \ ” % ~ # < >',
        ];
    }
    
}
