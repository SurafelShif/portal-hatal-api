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
            'content' => 'required|array',
            'type' => 'required|string'
        ];
    }
    public function messages(): array
    {
        return [
            'content.required' => 'הכנס פרטי הגדרות האתר ',
            'content.json' => 'המידע אינו בפורמט הנכון',
            'type.required' => 'הכנס את סוג הגדרות האתר ',
            'type.string' => 'סוג הגדרות האתר אינו בפורמט הנכון ',
        ];
    }
}
