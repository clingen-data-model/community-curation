<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GeneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return Auth::user()->can('update genes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'symbol' => 'required|max:255',
            // 'hgnc_id' => 'required|regex:/HGNC:\d+/',
            'protocol_path' => 'nullable|file|mimes:pdf,doc,xlsx,csv,docx,txt,rtf|max:2000',
            'hypothesis_group' => 'nullable|string',
            'hypothesis_group_url' => 'nullable|url|required_with:hypothesis_group',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
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
            'regex' => 'HGNC IDs must be given in the form "HGNC:1234".',
            'hypothesis_group_url.required_with' => 'You must enter the url for your hypothes.is group so we can invite volunteers to join it.',
        ];
    }
}
