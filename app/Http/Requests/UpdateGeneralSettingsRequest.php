<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSettingsRequest extends FormRequest
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
            'content.icons' => 'required|array',
            'content.description' => 'required|array',
            'content.settings' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'content.icons.required' => 'הכנס פרטי האייקונים',
            'content.description.required' => 'הכנס פרטי התיאור',
            'content.settings.required' => 'הכנס פרטי הגדרות האתר',
            'content.array' => 'המידע אינו בפורמט הנכון',
        ];
    }
}