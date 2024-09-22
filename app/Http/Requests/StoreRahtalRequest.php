<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRahtalRequest extends FormRequest
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
            'full_name' => 'required|string|min:2',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'full_name.required' => 'שם הרחת"ל דרוש.',
            'full_name.string' => 'שם הרחת"ל חייב להיות מחרוזת.',
            'full_name.min' => 'שם הרחת"ל צריך להכיל לפחות שני תווים.',
            'image.required' => 'דרוש קובץ תמונה.',
            'image.file' => 'התמונה חייבת להיות קובץ.',
            'image.mimes' => 'התמונה חייבת להיות מסוג: jpeg, png, jpg, gif.',
            'image.max' => 'גודל התמונה לא יכול לעלות על 2MB.',
        ];
    }
}
