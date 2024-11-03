<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteWebsitesRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*' => 'uuid', // Allow any additional items, ensuring each is a string
        ];
    }

    public function messages()
    {
        return [
            '*.string' => 'נא לשלוח את הפורמט הנכון עבור מזהה האתר (UUID)',

        ];
    }
}
