<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuratorUploadIndexRequest extends FormRequest
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
            'where.user_id' => [
                'nullable',
            ],
            'where.upload_category_id' => [
                'nullable',
            ],
            'sort.field' => [
                'nullable',
            ],
            'sort.dir' => [
                'nullable',
            ],
            'with' => [
                'nullable',
            ],
            'with_deleted' => [
                'nullabel',
            ],
        ];
    }
}
