<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUploadRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'file' => 'required|file',
            'user_id' => 'required|exists:users,id',
            'upload_category_id' => 'nullable|exists:upload_categories,id',
            'notes' => 'nullable|max:65535',
        ];
    }
}
