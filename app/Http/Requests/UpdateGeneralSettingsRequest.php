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
            'icons' => 'nullable|array',
            'description' => 'nullable|string',
            'hero' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'icons.array' => 'האייקונים אינם',
            'description.string' => 'התת כותרת אינו בפורמט הנכון',
            'hero.array' => 'מידע האתר אינו בפורמט הנכון',
        ];
    }
}
