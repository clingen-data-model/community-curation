<?php

namespace App\Http\Requests;

use App\Contracts\TrainingTopicContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrainingSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return Auth::user()->can('create trainings');
        return true;
        // return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'topic_type' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!in_array(TrainingTopicContract::class, class_implements($value))) {
                            $fail('The topic type is not valid.');
                        }
                    },
                ],
            'topic_id' => 'required',
            'url' => 'required|url',
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ];
    }

    public function messages()
    {
        return [
            'topic_id.required' => 'A topic is required.',
            'url.required' => 'A URL is required.',
            'starts_at.required' => 'A start date and time are required.',
            'starts_at.date' => 'You must provide a valid start date and time.',
            'ends_at.required' => 'An end date and time are required.',
            'ends_at.date' => 'You must provide a valid end date and time.',
            'ends_at.after' => 'End date and time must be after start date and time.',
        ];
    }

    public function attributes()
    {
        return [
            'starts_at' => 'start date and time',
            'ends_at' => 'end date and time',
            'url' => 'URL',
        ];
    }
}
