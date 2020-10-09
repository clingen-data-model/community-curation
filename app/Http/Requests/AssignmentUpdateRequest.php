<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // if (\Auth::guest()) {
        //     return false;
        // }

        // return \Auth::user()->hasAnyRole('admin', 'programer')
        //         || \Auth::user()->isCoordinator;
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
            'assignment_status_id' => [
                'required',
                'exists:assignment_statuses,id',
            ],
        ];
    }
}
