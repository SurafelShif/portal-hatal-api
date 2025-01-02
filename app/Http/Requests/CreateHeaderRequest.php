<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHeaderRequest extends FormRequest
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
            '*.image' => 'required|mimes:jpeg,jpg,png,jfif',
            '*.position' => 'required|integer',
        ];
    }


    public function messages(): array
    {
        return [
            '*.image.required' => 'התמונה הינה חובה',
            '*.position.required' => 'מיקום התמונה הינה חובה',
            '*.image.file' => 'התמונה חייבת להיות קובץ',
            '*.position.integer' => 'מיקום התמונה אינה בפורמט הנכון',
            '*.image.mimes' => 'התמונה חייבת להיות מסוג: jpeg, png, jpg, jfif.',
        ];
    }
}
