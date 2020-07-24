<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('create notes') || \Auth::use()->can('update notes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'notable_type' => 'required',
            'notable_id' => 'required',
            'content' => 'required'
        ];
    }
}
